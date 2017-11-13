<?php

namespace JincorTech\VerifyClient\VerificationMethod;

use JincorTech\VerifyClient\Interfaces\VerificationMethod;
use JincorTech\VerifyClient\Traits\BaseVerificationMethodTrait;

/**
 * Class EmailVerification
 *
 * @package JincorTech\VerifyClient\ValueObjects
 */
class EmailVerification implements VerificationMethod
{
    use BaseVerificationMethodTrait;

    const METHOD_TYPE = 'email';
    const MIN_LENGTH = 2;
}
