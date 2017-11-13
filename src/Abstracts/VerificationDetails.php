<?php

namespace JincorTech\VerifyClient\Abstracts;

/**
 * Class VerificationDetails
 *
 * @package JincorTech\VerifyClient\Abstracts
 */
abstract class VerificationDetails
{
    /**
     * @var string
     */
    protected $status;

    /**
     * @var string
     */
    protected $verificationId;

    /**
     * @var integer
     */
    protected $expiredOn;


    /**
     * VerificationDetails constructor.
     *
     * @param string $status
     * @param string $verificationId
     * @param int    $expiredId
     */
    public function __construct(string $status, string $verificationId, int $expiredId)
    {
        $this->status = $status;
        $this->verificationId = $verificationId;
        $this->expiredOn = $expiredId;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getVerificationId(): string
    {
        return $this->verificationId;
    }

    /**
     * @return int
     */
    public function getExpiredOn(): int
    {
        return $this->expiredOn;
    }
}
