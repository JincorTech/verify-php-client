<?php

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use JincorTech\VerifyClient\Abstracts\VerificationMethod;
use JincorTech\VerifyClient\ValueObjects\EmailValidationData;
use JincorTech\VerifyClient\ValueObjects\EmailInvalidationData;
use JincorTech\VerifyClient\ValueObjects\EmailVerificationDetails;
use JincorTech\VerifyClient\ValueObjects\VerificationResult;
use JincorTech\VerifyClient\VerificationMethod\EmailVerification;
use JincorTech\VerifyClient\Exceptions\InvalidCodeException;
use JincorTech\VerifyClient\ValueObjects\GoogleAuthValidationData;
use JincorTech\VerifyClient\ValueObjects\GoogleAuthInvalidationData;
use JincorTech\VerifyClient\ValueObjects\GoogleAuthVerificationDetails;
use JincorTech\VerifyClient\VerificationMethod\GoogleAuthVerification;
use JincorTech\VerifyClient\ValueObjects\Uuid;
use JincorTech\VerifyClient\VerifyClient;
use JincorTech\VerifyClient\Interfaces\VerifyService;

class VerifyClientCest
{
    const CONSUMER = 'test@test.com';
    const TEMPLATE = '{{{CODE}}}';
    const VERIFICATION_ID = 'd6b78279-db85-467e-b965-c938d043ffac';
    const VERIFICATION_CODE = 'boCMVNxsP6fV192zkjpNkLS8M';
    const VERIFICATION_EXPIRED_ON = 123456;
    const VERIFICATION_TOTP_URI = 'otpauth://totp/:test@test.com?secret=CK53DOA3R';

    /**
     * @var VerifyClient
     */
    private $verifyClient;

    /**
     * @var array
     */
    private $container;

    /**
     * @var MockHandler
     */
    private $mockHandler;

    /**
     * @var ClientInterface
     */
    private $mockHttpClient;

    /**
     * @var Uuid
     */
    private $verificationId;

    public function _before(UnitTester $I)
    {
        $this->verificationId = new Uuid(self::VERIFICATION_ID);

        $this->container = [];
        $history = Middleware::history($this->container);

        $this->mockHandler = new MockHandler();
        $handler = HandlerStack::create($this->mockHandler);
        $handler->push($history);

        $this->mockHttpClient = new Client(['handler' => $handler]);
        $this->verifyClient = new VerifyClient($this->mockHttpClient);
    }

    public function _after(UnitTester $I)
    {
    }

    public function canCreateVerifyClient(UnitTester $I)
    {
        $I->assertInstanceOf(VerifyClient::class, new VerifyClient(new Client()));
        $I->assertInstanceOf(VerifyService::class, new VerifyClient(new Client()));
    }

    public function initiateByEmailVerificationResponseCode200(UnitTester $I)
    {
        $responseBody = json_encode([
            'status' => 200,
            'verificationId' => $this->verificationId->getValue(),
            'expiredOn' => 123456,
            'consumer' => self::CONSUMER,
            'payload' => 'payload_data',
        ]);

        $this->addResponseToHandler($responseBody, 200);

        $emailMethod = new EmailVerification();
        $emailMethod->setConsumer(self::CONSUMER)
            ->setTemplate(self::TEMPLATE)
            ->setForcedVerificationId($this->verificationId)
            ->setExpiredOn(self::VERIFICATION_EXPIRED_ON)
            ->setPayload('payload_data');

        $verificationDetails = $this->verifyClient->initiate($emailMethod);
        $I->assertInstanceOf(EmailVerificationDetails::class, $verificationDetails);
        $I->assertEquals('200', $verificationDetails->getStatus());
        $I->assertEquals(self::VERIFICATION_ID, $verificationDetails->getVerificationId());
        $I->assertEquals(self::VERIFICATION_EXPIRED_ON, $verificationDetails->getExpiredOn());
        $I->assertEquals(self::CONSUMER, $verificationDetails->getConsumer());
        $I->assertEquals('payload_data', $verificationDetails->getPayload());
    }

    public function initiateByEmailVerificationResponseCode404(UnitTester $I)
    {
        $responseBody = json_encode([
            'status' => 404,
            'error' => 'Method not supported'
        ]);

        $this->addResponseToHandler($responseBody, 404);

        $I->expectException(ClientException::class, function () {
            $this->verifyClient->initiate(new EmailVerification());
        });
    }

    public function initiateByEmailVerificationResponseCode422(UnitTester $I)
    {
        $responseBody = json_encode([
            'status' => 422,
            'error' => 'Invalid request',
            'details' => [
                'path' => 'generateCode.length',
                'error' => 'Incorrect number format'
            ]
        ]);

        $this->addResponseToHandler($responseBody, 422);

        $I->expectException(ClientException::class, function () {
            $this->verifyClient->initiate(new EmailVerification());
        });
    }

    public function validateByEmailVerificationResponseCode200(UnitTester $I)
    {
        $responseBody = json_encode([
            'status' => 200,
            'data' => [
                'verificationId' => self::VERIFICATION_ID,
                'consumer' => self::CONSUMER,
                'expiredOn' =>  self::VERIFICATION_EXPIRED_ON,
                'payload' => 'payload_data',
            ]
        ]);

        $this->addResponseToHandler($responseBody, 200);

        $resultValidate = $this->verifyClient->validate(
            new EmailValidationData(
                $this->verificationId,
                self::VERIFICATION_CODE)
        );
        $I->assertInstanceOf(VerificationResult::class, $resultValidate);
        $I->assertEquals(self::CONSUMER, $resultValidate->getConsumer());
        $I->assertEquals(self::VERIFICATION_EXPIRED_ON, $resultValidate->getExpiredOn());
        $I->assertEquals(self::VERIFICATION_ID, $resultValidate->getVerificationId());
        $I->assertEquals(200, $resultValidate->getStatus());
        $I->assertEquals('payload_data', $resultValidate->getPayload());
    }

    public function validateByEmailVerificationResponseCode404(UnitTester $I)
    {
        $responseBody = json_encode([
            'status' => 404,
            'error' => 'Not Found',
        ]);

        $this->addResponseToHandler($responseBody, 404);

        $I->expectException(ClientException::class, function () {
            $this->verifyClient->validate(
                new EmailValidationData(
                    $this->verificationId,
                    self::VERIFICATION_CODE
                )
            );
        });
    }

    public function validateByEmailVerificationResponseCode422(UnitTester $I)
    {
        $responseBody = json_encode([
            'status' => 422,
            'error' => 'Invalid code',
        ]);

        $this->addResponseToHandler($responseBody, 422);

        $I->expectException(new InvalidCodeException('Invalid Code'), function () {
            $this->verifyClient->validate(
                new EmailValidationData(
                    $this->verificationId,
                    self::VERIFICATION_CODE
                )
            );
        });
    }

    public function invalidateByEmailVerificationResponseCode200(UnitTester $I)
    {
        $responseBody = json_encode([
            'status' => 200,
        ]);

        $this->addResponseToHandler($responseBody, 200);

        $result = $this->verifyClient->invalidate(
            new EmailInvalidationData($this->verificationId)
        );

        $I->assertTrue($result);
    }

    public function invalidateByEmailVerificationResponseCode404(UnitTester $I)
    {
        $responseBody = json_encode([
            'status' => 404,
            'error' => 'Not found'
        ]);

        $this->addResponseToHandler($responseBody, 404);

        $I->expectException(ClientException::class, function () {
            $this->verifyClient->invalidate(
                new EmailInvalidationData($this->verificationId)
            );
        });
    }

    public function initiateByGoogleAuthVerificationResponseCode200(UnitTester $I)
    {
        $responseBody = json_encode([
            'status' => 200,
            'consumer' => self::CONSUMER,
            'verificationId' => $this->verificationId->getValue(),
            'totpUri' => self::VERIFICATION_TOTP_URI,
            'expiredOn' => 123456,
        ]);

        $this->addResponseToHandler($responseBody, 200);

        $googleAuthMethod = new GoogleAuthVerification();
        $googleAuthMethod->setConsumer(self::CONSUMER)
            ->setTemplate(self::TEMPLATE)
            ->setForcedVerificationId($this->verificationId)
            ->setExpiredOn(self::VERIFICATION_EXPIRED_ON);

        $verificationDetails = $this->verifyClient->initiate($googleAuthMethod);
        $I->assertInstanceOf(GoogleAuthVerificationDetails::class, $verificationDetails);
        $I->assertEquals('200', $verificationDetails->getStatus());
        $I->assertEquals(self::VERIFICATION_ID, $verificationDetails->getVerificationId());
        $I->assertEquals(self::VERIFICATION_EXPIRED_ON, $verificationDetails->getExpiredOn());
        $I->assertEquals(self::VERIFICATION_TOTP_URI, $verificationDetails->getTotpUri());
        $I->assertEquals(self::CONSUMER, $verificationDetails->getConsumer());
        $I->assertEquals('', $verificationDetails->getPayload());
    }

    public function initiateByGoogleAuthVerificationResponseCode404(UnitTester $I)
    {
        $responseBody = json_encode([
            'status' => 404,
            'error' => 'Method not supported'
        ]);

        $this->addResponseToHandler($responseBody, 404);

        $I->expectException(ClientException::class, function () {
            $this->verifyClient->initiate(new EmailVerification());
        });
    }

    public function initiateByGoogleAuthVerificationResponseCode422(UnitTester $I)
    {
        $responseBody = json_encode([
            'status' => 422,
            'error' => 'Invalid request',
            'details' => [
                'path' => 'generateCode.length',
                'error' => 'Incorrect number format'
            ]
        ]);

        $this->addResponseToHandler($responseBody, 422);

        $I->expectException(ClientException::class, function () {
            $this->verifyClient->initiate(new EmailVerification());
        });
    }

    public function validateByGoogleAuthVerificationResponseCode200(UnitTester $I)
    {
        $responseBody = json_encode([
            'status' => 200,
            'data' => [
                'verificationId' => self::VERIFICATION_ID,
                'consumer' => self::CONSUMER,
                'expiredOn' =>  self::VERIFICATION_EXPIRED_ON
            ]
        ]);

        $this->addResponseToHandler($responseBody, 200);

        $resultValidate = $this->verifyClient->validate(
            new GoogleAuthValidationData(
                $this->verificationId,
                self::VERIFICATION_CODE,
                true)
        );
        $I->assertInstanceOf(VerificationResult::class, $resultValidate);
        $I->assertEquals(self::CONSUMER, $resultValidate->getConsumer());
        $I->assertEquals(self::VERIFICATION_EXPIRED_ON, $resultValidate->getExpiredOn());
        $I->assertEquals(self::VERIFICATION_ID, $resultValidate->getVerificationId());
        $I->assertEquals(200, $resultValidate->getStatus());
    }

    public function validateByGoogleAuthVerificationResponseCode404(UnitTester $I)
    {
        $responseBody = json_encode([
            'status' => 404,
            'error' => 'Not Found',
        ]);

        $this->addResponseToHandler($responseBody, 404);

        $I->expectException(ClientException::class, function () {
            $this->verifyClient->validate(
                new GoogleAuthValidationData(
                    $this->verificationId,
                    self::VERIFICATION_CODE
                )
            );
        });
    }

    public function validateByGoogleAuthVerificationResponseCode422(UnitTester $I)
    {
        $responseBody = json_encode([
            'status' => 422,
            'error' => 'Invalid code',
        ]);

        $this->addResponseToHandler($responseBody, 422);

        $I->expectException(new InvalidCodeException('Invalid Code'), function () {
            $this->verifyClient->validate(
                new GoogleAuthValidationData(
                    $this->verificationId,
                    self::VERIFICATION_CODE
                )
            );
        });
    }

    public function invalidateByGoogleAuthVerificationResponseCode200(UnitTester $I)
    {
        $responseBody = json_encode([
            'status' => 200,
        ]);

        $this->addResponseToHandler($responseBody, 200);

        $result = $this->verifyClient->invalidate(
            new GoogleAuthInvalidationData($this->verificationId)
        );

        $I->assertTrue($result);
    }

    public function invalidateByGoogleAuthVerificationResponseCode404(UnitTester $I)
    {
        $responseBody = json_encode([
            'status' => 404,
            'error' => 'Not found'
        ]);

        $this->addResponseToHandler($responseBody, 404);

        $I->expectException(ClientException::class, function () {
            $this->verifyClient->invalidate(
                new GoogleAuthInvalidationData($this->verificationId)
            );
        });
    }

    public function getVerificationByEmailVerificationResponseCode200(UnitTester $I)
    {
        $responseBody = json_encode([
            'status' => 200,
            'data' => [
                'verificationId' => $this->verificationId->getValue(),
                'expiredOn' => 123456,
                'consumer' => self::CONSUMER,
                'payload' => 'payload_data',
            ]
        ]);

        $this->addResponseToHandler($responseBody, 200);


        $verificationDetails = $this->verifyClient->getVerification(self::VERIFICATION_ID, VerificationMethod::METHOD_EMAIL);
        $I->assertInstanceOf(EmailVerificationDetails::class, $verificationDetails);
        $I->assertEquals('200', $verificationDetails->getStatus());
        $I->assertEquals(self::VERIFICATION_ID, $verificationDetails->getVerificationId());
        $I->assertEquals(self::VERIFICATION_EXPIRED_ON, $verificationDetails->getExpiredOn());
        $I->assertEquals(self::CONSUMER, $verificationDetails->getConsumer());
        $I->assertEquals('payload_data', $verificationDetails->getPayload());
    }

    public function getVerificationByGoogleAuthVerificationResponseCode200(UnitTester $I)
    {
        $responseBody = json_encode([
            'status' => 200,
            'data' => [
                'consumer' => self::CONSUMER,
                'verificationId' => $this->verificationId->getValue(),
                'totpUri' => self::VERIFICATION_TOTP_URI,
                'expiredOn' => 123456,
            ]
        ]);

        $this->addResponseToHandler($responseBody, 200);

        $verificationDetails = $this->verifyClient->getVerification(self::VERIFICATION_ID, VerificationMethod::METHOD_GOOGLE_AUTH);
        $I->assertInstanceOf(GoogleAuthVerificationDetails::class, $verificationDetails);
        $I->assertEquals('200', $verificationDetails->getStatus());
        $I->assertEquals(self::VERIFICATION_ID, $verificationDetails->getVerificationId());
        $I->assertEquals(self::VERIFICATION_EXPIRED_ON, $verificationDetails->getExpiredOn());
        $I->assertEquals(self::VERIFICATION_TOTP_URI, $verificationDetails->getTotpUri());
        $I->assertEquals(self::CONSUMER, $verificationDetails->getConsumer());
        $I->assertEquals('', $verificationDetails->getPayload());
    }

    /**
     * @param $responseBody
     * @param $status
     */
    private function addResponseToHandler($responseBody, $status)
    {
        $this->mockHandler->append(
            new Response($status, [
                'Content-Type' => 'application/json',
            ], $responseBody)
        );
    }
}
