<?php

namespace JincorTech\VerifyClient\Abstracts;

use JincorTech\VerifyClient\ValueObjects\Uuid;

/**
 * Class ValidationData
 *
 * @package JincorTech\VerifyClient\Abstracts
 */
abstract class ValidationData
{
    /**
     * @var Uuid
     */
    private $verificationId;

    /**
     * BaseValidationData constructor.
     *
     * @param Uuid $verificationId
     */
    public function __construct(Uuid $verificationId)
    {
        $this->verificationId = $verificationId;
    }

    /**
     * @return string
     */
    abstract public function getMethodType(): string;

    /**
     * @return array
     */
    abstract public function getRequestParameters(): array;

    /**
     * @return Uuid
     */
    public function getVerificationId(): Uuid
    {
        return $this->verificationId;
    }
}
