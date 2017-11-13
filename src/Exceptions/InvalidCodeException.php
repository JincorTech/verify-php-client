<?php

namespace JincorTech\VerifyClient\Exceptions;

use Exception;

/**
 * Class InvalidCodeException
 *
 * @package JincorTech\VerifyClient\Exceptions
 */
class InvalidCodeException extends Exception
{
    /**
     * @var int $attempts
     */
    private $attempts;

    /**
     * InvalidCodeException constructor.
     *
     * @param string $message
     * @param int    $attempts
     */
    public function __construct($message = '', int $attempts = 0)
    {
        parent::__construct($message);

        $this->attempts = $attempts;
    }
}
