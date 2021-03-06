<?php

namespace JincorTech\VerifyClient\Interfaces;

/**
 * Interface GenerateCodeInterface
 *
 * Provide common constants for generate a code
 *
 * @package JincorTech\VerifyClient\Interfaces
 */
interface GenerateCode
{
    const DIGITS = 'DIGITS';
    const LOWERCASE_ALPHAS = 'alphas';
    const UPPERCASE_ALPHAS = 'ALPHAS';
    const ALLOWABLE_VALUES = [
        self::UPPERCASE_ALPHAS,
        self::LOWERCASE_ALPHAS,
        self::DIGITS,
    ];
}
