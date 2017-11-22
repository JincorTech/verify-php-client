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
     * GoogleAuthVerificationDetails constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->validateData($data, ['totpUri']);

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
}
