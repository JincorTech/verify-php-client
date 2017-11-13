<?php

namespace JincorTech\VerifyClient\Abstracts;

use JincorTech\VerifyClient\ValueObjects\Uuid;

/**
 * Class InvalidationData
 *
 * @package JincorTech\VerifyClient\Abstracts
 */
abstract class InvalidationData
{
    /**
     * @var Uuid
     */
    private $verificationId;

    /**
     * BaseInvalidationData constructor.
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
     * @return Uuid
     */
    public function getVerificationId(): Uuid
    {
        return $this->verificationId;
    }
}
