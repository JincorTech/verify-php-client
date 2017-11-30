<?php

namespace JincorTech\VerifyClient\Abstracts;

use Exception;

/**
 * VerificationMethod
 *
 * @package JincorTech\VerifyClient\Abstracts
 */
abstract class VerificationMethod
{
    const METHOD_EMAIL = 'email';
    const METHOD_GOOGLE_AUTH = 'google_auth';
    const ALLOWABLE_METHODS = [self::METHOD_EMAIL, self::METHOD_GOOGLE_AUTH];

    /**
     * @return string
     */
    public abstract function getMethodType(): string;

    /**
     * @return array
     */
    public abstract function getRequestParameters(): array;


    /**
     * @param string $methodType
     * @throws Exception
     */
    public function validateMethodType(string $methodType): void
    {
        if (!in_array($methodType, self::ALLOWABLE_METHODS)) {
            throw new Exception('Unsupported method type');
        }
    }
}
