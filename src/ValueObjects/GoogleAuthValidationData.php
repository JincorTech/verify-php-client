<?php

namespace JincorTech\VerifyClient\ValueObjects;

use InvalidArgumentException;
use JincorTech\VerifyClient\Abstracts\ValidationData;
use JincorTech\VerifyClient\VerificationMethod\GoogleAuthVerification;

/**
 * Class GoogleAuthValidationData
 *
 * @package JincorTech\VerifyClient\ValueObjects
 */
class GoogleAuthValidationData extends ValidationData
{

    /**
     * @var string $code
     */
    private $code;

    /**
     * @var bool $removeSecret
     */
    private $removeSecret;

    /**
     * GoogleAuthValidationData constructor.
     *
     * @param Uuid   $verificationId
     * @param string $code
     * @param bool   $removeSecret
     */
    public function __construct(Uuid $verificationId, string $code, bool $removeSecret = false)
    {
        parent::__construct($verificationId);

        if (empty($code)) {
            throw new InvalidArgumentException('Code is empty');
        }

        $this->code = $code;
        $this->removeSecret = $removeSecret;
    }

    /**
     * Method type
     *
     * @return string
     */
    public function getMethodType(): string
    {
        return GoogleAuthVerification::METHOD_TYPE;
    }

    /**
     * Request parameters
     *
     * @return array
     */
    public function getRequestParameters(): array
    {
        return [
            'code'         => $this->code,
            'removeSecret' => $this->removeSecret,
        ];
    }
}
