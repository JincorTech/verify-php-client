<?php

namespace JincorTech\VerifyClient\VerificationMethod;

use InvalidArgumentException;
use JincorTech\VerifyClient\Interfaces\VerificationMethod;
use JincorTech\VerifyClient\Traits\BaseVerificationMethodTrait;

/**
 * Class GoogleAuthVerification
 *
 * @package JincorTech\VerifyClient\ValueObjects
 */
class GoogleAuthVerification implements VerificationMethod
{
    use BaseVerificationMethodTrait;

    const METHOD_TYPE = 'google_auth';
    const MIN_LENGTH = 2;

    /**
     * @var string
     */
    private $issuer;

    /**
     * @param string $issuer
     *
     * @return self
     */
    public function setIssuer(string $issuer): self
    {
        if (empty($issuer)) {
            throw new InvalidArgumentException('Issuer is empty');
        }
        $this->issuer = $issuer;

        return $this;
    }

    /**
     * @return array
     */
    public function getRequestParameters(): array
    {
        $parameters = [
            'consumer' => $this->consumer,
            'template' => $this->template,
            'policy'   => $this->policy,
            'issuer'   => $this->issuer,
        ];

        if ($this->generateCode) {
            $parameters['generateCode'] = $this->generateCode;
        }

        if ($this->forcedVerificationId) {
            $parameters['policy']['forcedVerificationId'] = $this->forcedVerificationId->getValue();
        }

        if ($this->forcedCode) {
            $parameters['policy']['forcedCode'] = $this->forcedCode;
        }

        return $parameters;
    }
}
