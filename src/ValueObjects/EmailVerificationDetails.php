<?php

namespace JincorTech\VerifyClient\ValueObjects;

use JincorTech\VerifyClient\Abstracts\VerificationDetails;

/**
 * Class EmailVerificationDetails
 *
 * @package JincorTech\VerifyClient
 */
class EmailVerificationDetails extends VerificationDetails
{
    /**
     * @var int
     */
    private $attempts;

    /**
     * EmailVerificationDetails constructor.
     *
     * @param int    $status
     * @param Uuid   $verificationId
     * @param string $expiredId
     * @param int    $attempts
     */
    public function __construct(int $status, Uuid $verificationId, string $expiredId, int $attempts = 0)
    {
        parent::__construct($status, $verificationId, $expiredId);

        $this->attempts = $attempts;
    }

    /**
     * Attempts
     *
     * @return int
     */
    public function getAttempts(): int
    {
        return $this->attempts;
    }
}
