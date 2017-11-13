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
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->validateData($data, ['consumer', 'totpUri']);

        $this->consumer = $data['consumer'];
        $this->totpUri = $data['totpUri'];
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
