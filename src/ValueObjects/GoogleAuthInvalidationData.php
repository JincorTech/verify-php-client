<?php

namespace JincorTech\VerifyClient\ValueObjects;

use JincorTech\VerifyClient\Abstracts\InvalidationData;
use JincorTech\VerifyClient\VerificationMethod\GoogleAuthVerification;

/**
 * Class GoogleAuthInvalidationData
 *
 * @package JincorTech\VerifyClient\ValueObjects
 */
class GoogleAuthInvalidationData extends InvalidationData
{
    /**
     * Method type
     *
     * @return string
     */
    public function getMethodType(): string
    {
        return GoogleAuthVerification::METHOD_TYPE;
    }
}
