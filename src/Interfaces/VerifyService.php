<?php

namespace JincorTech\VerifyClient\Interfaces;

use JincorTech\VerifyClient\Abstracts\InvalidationData;
use JincorTech\VerifyClient\Abstracts\ValidationData;
use JincorTech\VerifyClient\Abstracts\VerificationDetails;
use JincorTech\VerifyClient\ValueObjects\VerificationResult;

/**
 * Interface VerifyServiceInterface
 *
 * @package JincorTech\VerifyClient
 */
interface VerifyService
{
    /**
     * @param VerificationMethod $verificationMethod
     *
     * @return mixed
     */
    public function initiate(VerificationMethod $verificationMethod): VerificationDetails;

    /**
     * @param ValidationData $validationData
     *
     * @return VerificationResult
     */
    public function validate(ValidationData $validationData): VerificationResult;

    /**
     * @param InvalidationData $invalidationData
     *
     * @return bool
     */
    public function invalidate(InvalidationData $invalidationData): bool;
}
