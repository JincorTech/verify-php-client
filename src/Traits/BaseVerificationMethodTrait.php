<?php

namespace JincorTech\VerifyClient\Traits;

use InvalidArgumentException;
use JincorTech\VerifyClient\Interfaces\GenerateCode;
use JincorTech\VerifyClient\ValueObjects\Uuid;

/**
 * Trait BaseVerificationMethodTrait
 *
 * @package JincorTech\VerifyClient\Traits
 */
trait BaseVerificationMethodTrait
{
    /**
     * @var string $consumer
     */
    private $consumer;

    /**
     * @var string $template
     */
    private $template;

    /**
     * @var array $policy
     */
    private $policy = [];

    /**
     * @var array $generateCode
     */
    private $generateCode;

    /**
     * @var Uuid $forcedVerificationId
     */
    private $forcedVerificationId;

    /**
     * @var string $forcedCode
     */
    private $forcedCode;

    /**
     * @var string $payload
     */
    private $payload;


    /**
     * @return string
     */
    public function getMethodType(): string
    {
        return static::METHOD_TYPE;
    }

    /**
     * @param string $forcedCode
     *
     * @return self
     */
    public function setForcedCode(string $forcedCode): self
    {
        if (empty($forcedCode)) {
            throw new InvalidArgumentException('ForcedCode is empty');
        }

        $this->forcedCode = $forcedCode;

        return $this;
    }

    /**
     * @param Uuid $forcedVerificationId
     *
     * @return self
     */
    public function setForcedVerificationId(Uuid $forcedVerificationId): self
    {
        $this->forcedVerificationId = $forcedVerificationId;

        return $this;
    }

    /**
     * @param string $expiredOn
     *
     * @return self
     */
    public function setExpiredOn(string $expiredOn): self
    {
        if (empty($expiredOn)) {
            throw new InvalidArgumentException('ExpiredOn is empty');
        }

        $this->policy['expiredOn'] = $expiredOn;

        return $this;
    }

    /**
     * @param string $consumer
     *
     * @return self
     */
    public function setConsumer(string $consumer): self
    {
        if (empty($consumer)) {
            throw new InvalidArgumentException('Consumer is empty');
        }

        $this->consumer = $consumer;

        return $this;
    }

    /**
     * @param string $template
     *
     * @return self
     */
    public function setTemplate(string $template): self
    {
        if (empty($template)) {
            throw new InvalidArgumentException('Template is empty');
        }

        $this->template = [
            'body' => $template,
        ];

        return $this;
    }

    /**
     * @param array $symbolsSet
     * @param int   $length
     *
     * @return self
     */
    public function setGenerateCode(array $symbolsSet, int $length): self
    {
        if ($length <= self::MIN_LENGTH) {
            throw new InvalidArgumentException('Too short length');
        }

        foreach ($symbolsSet as $set) {
            if (!is_string($set) || empty($set) || !in_array($set, GenerateCode::ALLOWABLE_VALUES)) {
                throw new InvalidArgumentException('Invalid symbol set');
            }
        }

        $this->generateCode = [
            'symbolSet' => $symbolsSet,
            'length'    => $length,
        ];

        return $this;
    }


    /**
     * @param string $payload
     *
     * @return self
     */
    public function setPayload(string $payload): self
    {
        if (empty($payload)) {
            throw new InvalidArgumentException('Payload is empty');
        }

        $this->payload = $payload;

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

        if ($this->payload) {
            $parameters['payload'] = $this->payload;
        }

        return $parameters;
    }
}
