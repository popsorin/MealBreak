<?php

namespace Popsorin\Exception\Persistency;

use Team1\Exception\AbstractException;
use Throwable;

/**
 * Class PasswordTooShortException
 * @package Popsorin\Exception\Persistency
 */
class PasswordTooShortException extends AbstractException
{
    /**
     * PasswordTooShortException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Password needs to be at least 8 characters", $code = 0, Throwable $previous = null)
    {
    parent::__construct($message, $code, $previous);
    }
}
