<?php

namespace JincorTech\VerifyClient\Interfaces;

/**
 * Interface VerificationMethod
 *
 * @package JincorTech\VerifyClient\Interfaces
 */
interface VerificationMethod
{
    /**
     * @return string
     */
    public function getMethodType(): string;

    /**
     * @return array
     */
    public function getRequestParameters(): array;
}
