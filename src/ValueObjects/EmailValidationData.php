<?php

namespace JincorTech\VerifyClient\ValueObjects;

use InvalidArgumentException;
use JincorTech\VerifyClient\Abstracts\ValidationData;
use JincorTech\VerifyClient\VerificationMethod\EmailVerification;

/**
 * Class EmailValidationData
 *
 * @package JincorTech\VerifyClient\ValueObjects
 */
class EmailValidationData extends ValidationData
{
    /**
     * @var string $code
     */
    private $code;

    /**
     * EmailValidationData constructor.
     *
     * @param Uuid   $verificationId
     * @param string $code
     */
    public function __construct(Uuid $verificationId, string $code)
    {
        parent::__construct($verificationId);

        if (empty($code)) {
            throw new InvalidArgumentException('Code is empty');
        }

        $this->code = $code;
    }

    /**
     * HTTP Code
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Method type
     *
     * @return string
     */
    public function getMethodType(): string
    {
        return EmailVerification::METHOD_TYPE;
    }

    /**
     * Request parameters
     *
     * @return array
     */
    public function getRequestParameters(): array
    {
        return [
            'code' => $this->getCode()
        ];
    }
}
