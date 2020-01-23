<?php

/*
 * Written by Popa Alexandru
 */

namespace Team1\Exception\Validator;


use Team1\Exception\AbstractException;
use Throwable;

/**
 * Class WrongEmailFormatException
 * @package Team1\Exception\Validator
 */
class PasswordNullError extends AbstractException
{
    public function __construct($message = "Password cannot be empty", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}