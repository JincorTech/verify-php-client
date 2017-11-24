<?php

namespace JincorTech\VerifyClient\ValueObjects;

use InvalidArgumentException;

/**
 * Class VerificationResult
 *
 * @package JincorTech\VerifyClient\ValueObjects
 */
class VerificationResult
{
    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $consumer;

    /**
     * @var string
     */
    private $verificationId;

    /**
     * @var integer
     */
    private $expiredOn;

    /**
     * @var string
     */
    private $payload = '';

    /**
     * VerificationDetails constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->validateData($data, ['status', 'data']);
        $this->validateData($data['data'], ['verificationId', 'consumer', 'expiredOn']);

        $this->status = $data['status'];
        $this->consumer = $data['data']['consumer'];
        $this->verificationId = $data['data']['verificationId'];
        $this->expiredOn = $data['data']['expiredOn'];

        if (array_key_exists('payload', $data['data'])) {
            $this->payload = $data['data']['payload'];
        }
    }

    /**
     * Status
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
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

    /**
     * ValidationId
     *
     * @return string
     */
    public function getVerificationId(): string
    {
        return $this->verificationId;
    }

    /**
     * ExpiredOn
     *
     * @return int
     */
    public function getExpiredOn(): int
    {
        return $this->expiredOn;
    }

    /**
     * @return string
     */
    public function getPayload(): string
    {
        return $this->payload;
    }

    /**
     * @param array $data
     * @param array $requiredKeys
     *
     * @throws InvalidArgumentException
     */
    public function validateData(array $data, array $requiredKeys)
    {
        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $data) && !empty($data[$key])) {
                throw new InvalidArgumentException(sprintf('%s field is required', $key));
            }
        }
    }
}
