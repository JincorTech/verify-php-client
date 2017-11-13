<?php

namespace JincorTech\VerifyClient\Abstracts;

use InvalidArgumentException;
use JincorTech\VerifyClient\ValueObjects\Uuid;

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
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->validateData($data, ['status', 'verificationId', 'expiredOn']);

        $this->status = $data['status'];
        $this->verificationId = new Uuid($data['verificationId']);
        $this->expiredOn = $data['expiredOn'];
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
