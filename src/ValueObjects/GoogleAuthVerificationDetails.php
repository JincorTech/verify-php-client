<?php

namespace JincorTech\VerifyClient\ValueObjects;

use JincorTech\VerifyClient\Abstracts\VerificationDetails;

/**
 * Class GoogleAuthVerificationDetails
 *
 * @package JincorTech\VerifyClient
 */
class GoogleAuthVerificationDetails extends VerificationDetails
{
    /**
     * @var string $totpUri
     */
    private $totpUri;

    /**
     * @var string $consumer
     */
    private $consumer;

    /**
     * GoogleAuthVerificationDetails constructor.
     *
     * @param int    $status
     * @param Uuid   $verificationId
     * @param string $expiredId
     * @param string $consumer
     * @param string $totpUri
     */
    public function __construct(int $status, Uuid $verificationId, string $expiredId, string $consumer, string $totpUri)
    {
        parent::__construct($status, $verificationId, $expiredId);

        if (empty($consumer)) {
            throw new \InvalidArgumentException('Consumer is empty');
        }

        if (empty($totpUri)) {
            throw new \InvalidArgumentException('Totp URI is empty');
        }

        $this->consumer = $consumer;
        $this->totpUri = $totpUri;
    }

    /**
     * TOTP URI
     *
     * @return string
     */
    public function getTotpUri(): string
    {
        return $this->totpUri;
    }

    /**
     * Consumer
     *
     * @return string
     */
    public function getConsumer(): string
    {
        return $this->consumer;
    }
}
