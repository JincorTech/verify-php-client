<?php


namespace JincorTech\VerifyClient\ValueObjects;


use InvalidArgumentException;


/**
 * Class Uuid
 *
 * @package JincorTech\VerifyClient\ValueObjects
 */
class Uuid
{
    const VALID_UUID_FORMAT_REGEX = '/^[\da-f]{8}-([\da-f]{4}-){3}[\da-f]{12}|[\d]{32}$/';

    /**
     * @var string $value
     */
    private $value;

    /**
     * Uuid constructor.
     *
     * @param string $value
     */
    public function __construct(string $value)
    {
        if (empty($value) || !preg_match(
            Uuid::VALID_UUID_FORMAT_REGEX,
            $value
        )
        ) {
            throw new InvalidArgumentException('Uuid is not well formatted');
        }
        $this->value = $value;
    }

    /**
     * Value
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }
}
