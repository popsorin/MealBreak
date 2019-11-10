<?php

namespace Team1\Exception\Index;

use Team1\Exception\AbstractException;
use Throwable;

/**
 * Class PasswordIsNotTheSameException
 * @package Team1\Exception\Index
 */
class PasswordIsNotTheSameException extends AbstractException
{
    public function __construct($message = "Passwords are not the same", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}