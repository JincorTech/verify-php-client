<?php

namespace JincorTech\VerifyClient\VerificationMethod;

use JincorTech\VerifyClient\Abstracts\VerificationMethod;
use JincorTech\VerifyClient\Traits\BaseVerificationMethodTrait;

/**
 * Class EmailVerification
 *
 * @package JincorTech\VerifyClient\ValueObjects
 */
class EmailVerification extends VerificationMethod
{
    use BaseVerificationMethodTrait;

    const METHOD_TYPE = self::METHOD_EMAIL;
    const MIN_LENGTH = 2;
}
