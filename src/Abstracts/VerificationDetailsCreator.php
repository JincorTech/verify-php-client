<?php

namespace JincorTech\VerifyClient\Abstracts;

use Exception;
use JincorTech\VerifyClient\ValueObjects\EmailVerificationDetails;
use JincorTech\VerifyClient\ValueObjects\GoogleAuthVerificationDetails;
use JincorTech\VerifyClient\ValueObjects\Uuid;

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
            case 'email':
                return new EmailVerificationDetails(
                    $data['status'],
                    new Uuid($data['verificationId']),
                    $data['expiredOn'],
                    0
                );
            case 'google_auth':
                return new GoogleAuthVerificationDetails(
                    $data['status'],
                    new Uuid($data['verificationId']),
                    $data['expiredOn'],
                    $data['consumer'],
                    $data['totpUri']
                );
            default:
                throw new Exception('Unsupported method type');
                break;
        }
    }
}
