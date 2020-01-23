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
class PasswordRepeatNullError extends AbstractException
{
    public function __construct($message = "This field cannot be empty", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}