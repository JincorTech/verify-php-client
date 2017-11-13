<?php

use JincorTech\VerifyClient\VerificationMethod\EmailVerification;
use JincorTech\VerifyClient\Interfaces\GenerateCode;
use JincorTech\VerifyClient\ValueObjects\Uuid;
use JincorTech\VerifyClient\Interfaces\VerificationMethod;

class EmailVerificationVerificationMethodCest
{
    const CONSUMER = 'test@test.com';
    const TEMPLATE = '{{{CODE}}}';
    const VERIFICATION_ID = 'd6b78279-db85-467e-b965-c938d043ffac';
    const VERIFICATION_CODE = 'boCMVNxsP6fV192zkjpNkLS8M';
    const VERIFICATION_EXPIRED_ON = '123456';

    private $verificationId;

    public function _before(UnitTester $I)
    {
        $this->verificationId = new Uuid(self::VERIFICATION_ID);
    }

    public function createEmailVerification(UnitTester $I)
    {
        $emailMethod = new EmailVerification();

        $I->assertInstanceOf(VerificationMethod::class, $emailMethod);
        $I->assertEquals(EmailVerification::METHOD_TYPE, $emailMethod->getMethodType());
    }

    public function setValidParameters(UnitTester $I)
    {
        $emailMethod = new EmailVerification();

        $I->assertArrayHasKey('consumer', $emailMethod->getRequestParameters());
        $I->assertArrayHasKey('template', $emailMethod->getRequestParameters());
        $I->assertArrayHasKey('policy', $emailMethod->getRequestParameters());
        $I->assertEquals([], ($emailMethod->getRequestParameters())['policy']);

        $emailMethod->setConsumer(self::CONSUMER);
        $I->assertEquals(self::CONSUMER, ($emailMethod->getRequestParameters())['consumer']);

        $emailMethod->setTemplate(self::TEMPLATE);
        $I->assertEquals(self::TEMPLATE, ($emailMethod->getRequestParameters())['template']['body']);

        $emailMethod->setForcedCode(self::VERIFICATION_CODE);
        $I->assertEquals(self::VERIFICATION_CODE, ($emailMethod->getRequestParameters())['policy']['forcedCode']);

        $emailMethod->setForcedVerificationId($this->verificationId);
        $I->assertEquals(
            self::VERIFICATION_ID,
            ($emailMethod->getRequestParameters())['policy']['forcedVerificationId']
        );

        $emailMethod->setExpiredOn(self::VERIFICATION_EXPIRED_ON);
        $I->assertEquals(
            self::VERIFICATION_EXPIRED_ON,
            ($emailMethod->getRequestParameters())['policy']['expiredOn']
        );

        $emailMethod->setGenerateCode(
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
            ($emailMethod->getRequestParameters())['generateCode']['symbolSet']
        );
        $I->assertEquals(16, ($emailMethod->getRequestParameters())['generateCode']['length']);
    }

    public function setWrongParameters(UnitTester $I)
    {
        $emailMethod = new EmailVerification();

        $I->expectException(
            new \InvalidArgumentException('Consumer is empty'),
            function () use ($emailMethod) {
                $emailMethod->setConsumer('');
            }
        );

        $I->expectException(
            new \InvalidArgumentException('Template is empty'),
            function () use ($emailMethod) {
                $emailMethod->setTemplate('');
            }
        );

        $I->expectException(
            new \InvalidArgumentException('ForcedCode is empty'),
            function () use ($emailMethod) {
                $emailMethod->setForcedCode('');
            }
        );

        $I->expectException(
            new \InvalidArgumentException('ExpiredOn is empty'),
            function () use ($emailMethod) {
                $emailMethod->setExpiredOn('');
            }
        );

        $I->expectException(
            new InvalidArgumentException('Invalid symbol set'),
            function () use ($emailMethod) {
                $emailMethod->setGenerateCode([''], 16);
            }
        );

        $I->expectException(
            new InvalidArgumentException('Too short length'),
            function () use ($emailMethod) {
                $emailMethod->setGenerateCode([GenerateCode::DIGITS], 1);
            }
        );
    }
}
