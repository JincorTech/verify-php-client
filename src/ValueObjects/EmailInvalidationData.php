<?php

namespace JincorTech\VerifyClient\ValueObjects;

use JincorTech\VerifyClient\Abstracts\InvalidationData;
use JincorTech\VerifyClient\VerificationMethod\EmailVerification;

/**
 * Class EmailInvalidationData
 *
 * @package JincorTech\VerifyClient\ValueObjects
 */
class EmailInvalidationData extends InvalidationData
{
    /**
     * Method type
     *
     * @return string
     */
    public function getMethodType(): string
    {
        return EmailVerification::METHOD_TYPE;
    }
}
