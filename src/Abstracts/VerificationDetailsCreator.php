<?php

namespace JincorTech\VerifyClient\Abstracts;

use Exception;
use JincorTech\VerifyClient\ValueObjects\EmailVerificationDetails;
use JincorTech\VerifyClient\ValueObjects\GoogleAuthVerificationDetails;

/**
 * Class VerificationDetailsCreator
 *
 * @package JincorTech\VerifyClient\Abstracts
 */
abstract class VerificationDetailsCreator
{
    /**
     * @param string $methodType
     * @param array $data
     *
     * @return VerificationDetails
     *
     * @throws Exception
     */
    public static function create(string $methodType, array $data): VerificationDetails
    {
        switch ($methodType) {
            case VerificationMethod::METHOD_EMAIL:
                return new EmailVerificationDetails($data);
            case VerificationMethod::METHOD_GOOGLE_AUTH:
                return new GoogleAuthVerificationDetails($data);
            default:
                throw new Exception('Unsupported method type');
        }
    }
}
