<?php

use JincorTech\VerifyClient\Interfaces\GenerateCode;
use JincorTech\VerifyClient\VerificationMethod\GoogleAuthVerification;
use JincorTech\VerifyClient\ValueObjects\Uuid;
use JincorTech\VerifyClient\Interfaces\VerificationMethod;

class GoogleAuthVerificationVerificationMethodCest
{
    const CONSUMER = 'test@test.com';
    const TEMPLATE = '{{{CODE}}}';
    const FROM_EMAIL = 'noreply@jincor.com';
    const FROM_NAME = 'Robot';
    const SUBJECT = 'Subject';
    const VERIFICATION_ID = 'd6b78279-db85-467e-b965-c938d043ffac';
    const VERIFICATION_CODE = 'boCMVNxsP6fV192zkjpNkLS8M';
    const VERIFICATION_EXPIRED_ON = '60 min';

    private $verificationId;

    public function _before(UnitTester $I)
    {
        $this->verificationId = new Uuid(self::VERIFICATION_ID);
    }

    public function createGoogleAuthVerification(UnitTester $I)
    {
        $googleAuthMethod = new GoogleAuthVerification();

        $I->assertInstanceOf(VerificationMethod::class, $googleAuthMethod);
        $I->assertEquals($googleAuthMethod->getMethodType(), GoogleAuthVerification::METHOD_TYPE);
    }

    public function setValidParameters(UnitTester $I)
    {
        $googleAuthMethod = new GoogleAuthVerification();

        $I->assertArrayHasKey('consumer', $googleAuthMethod->getRequestParameters());
        $I->assertArrayHasKey('template', $googleAuthMethod->getRequestParameters());
        $I->assertArrayHasKey('policy', $googleAuthMethod->getRequestParameters());
        $I->assertEquals([], ($googleAuthMethod->getRequestParameters())['policy']);

        $googleAuthMethod->setConsumer(self::CONSUMER);
        $I->assertEquals(self::CONSUMER, ($googleAuthMethod->getRequestParameters())['consumer']);

        $googleAuthMethod->setTemplate(self::TEMPLATE);
        $I->assertEquals(self::TEMPLATE, ($googleAuthMethod->getRequestParameters())['template']['body']);

        $googleAuthMethod->setFromEmail(self::FROM_EMAIL);
        $I->assertEquals(self::FROM_EMAIL, ($googleAuthMethod->getRequestParameters())['template']['fromEmail']);

        $googleAuthMethod->setFromName(self::FROM_NAME);
        $I->assertEquals(self::FROM_NAME, ($googleAuthMethod->getRequestParameters())['template']['fromName']);

        $googleAuthMethod->setSubject(self::SUBJECT);
        $I->assertEquals(self::SUBJECT, ($googleAuthMethod->getRequestParameters())['template']['subject']);

        $googleAuthMethod->setForcedCode(self::VERIFICATION_CODE);
        $I->assertEquals(self::VERIFICATION_CODE, ($googleAuthMethod->getRequestParameters())['policy']['forcedCode']);

        $googleAuthMethod->setForcedVerificationId($this->verificationId);
        $I->assertEquals(
            self::VERIFICATION_ID, ($googleAuthMethod->getRequestParameters())['policy']['forcedVerificationId']
        );

        $googleAuthMethod->setExpiredOn(self::VERIFICATION_EXPIRED_ON);
        $I->assertEquals(self::VERIFICATION_EXPIRED_ON, ($googleAuthMethod->getRequestParameters())['policy']['expiredOn']);

        $googleAuthMethod->setGenerateCode(
            [
                GenerateCode::DIGITS,
                GenerateCode::LOWERCASE_ALPHAS,
                GenerateCode::UPPERCASE_ALPHAS
            ],
            16
        );

        $I->assertArraySubset(
            [
                GenerateCode::DIGITS,
                GenerateCode::LOWERCASE_ALPHAS,
                GenerateCode::UPPERCASE_ALPHAS
            ],
            ($googleAuthMethod->getRequestParameters())['generateCode']['symbolSet']
        );
        $I->assertEquals(16, ($googleAuthMethod->getRequestParameters())['generateCode']['length']);

        $googleAuthMethod->setIssuer('Jincor');
        $I->assertEquals('Jincor', ($googleAuthMethod->getRequestParameters())['issuer']);

        $googleAuthMethod->setPayload('payload_data');
        $I->assertEquals(
            'payload_data',
            ($googleAuthMethod->getRequestParameters())['payload']
        );
    }

    public function setWrongParameters(UnitTester $I)
    {
        $googleAuthMethod = new GoogleAuthVerification();
        $I->expectException(
            new InvalidArgumentException('Consumer is empty'),
            function () use ($googleAuthMethod) {
                $googleAuthMethod->setConsumer('');
            }
        );

        $I->expectException(
            new InvalidArgumentException('Template is empty'),
            function () use ($googleAuthMethod) {
                $googleAuthMethod->setTemplate('');
            }
        );

        $I->expectException(
            new InvalidArgumentException('From email is empty'),
            function () use ($googleAuthMethod) {
                $googleAuthMethod->setFromEmail('');
            }
        );

        $I->expectException(
            new InvalidArgumentException('From name is empty'),
            function () use ($googleAuthMethod) {
                $googleAuthMethod->setFromName('');
            }
        );

        $I->expectException(
            new InvalidArgumentException('Subject is empty'),
            function () use ($googleAuthMethod) {
                $googleAuthMethod->setSubject('');
            }
        );

        $I->expectException(
            new InvalidArgumentException('ForcedCode is empty'),
            function () use ($googleAuthMethod) {
                $googleAuthMethod->setForcedCode('');
            }
        );

        $I->expectException(
            new InvalidArgumentException('ExpiredOn is empty'),
            function () use ($googleAuthMethod) {
                $googleAuthMethod->setExpiredOn('');
            }
        );

        $I->expectException(
            new InvalidArgumentException('Invalid symbol set'),
            function () use ($googleAuthMethod) {
                $googleAuthMethod->setGenerateCode([''], 16);
            }
        );

        $I->expectException(
            new InvalidArgumentException('Invalid symbol set'),
            function () use ($googleAuthMethod) {
                $googleAuthMethod->setGenerateCode(['FOO', 'BAR'], 16);
            }
        );

        $I->expectException(
            new InvalidArgumentException('Too short length'),
            function () use ($googleAuthMethod) {
                $googleAuthMethod->setGenerateCode([GenerateCode::DIGITS], 1);
            }
        );

        $I->expectException(
            new InvalidArgumentException('Issuer is empty'),
            function () use ($googleAuthMethod) {
                $googleAuthMethod->setIssuer('');
            }
        );

        $I->expectException(
            new InvalidArgumentException('Payload is empty'),
            function () use ($googleAuthMethod) {
                $googleAuthMethod->setPayload('');
            }
        );
    }
}
