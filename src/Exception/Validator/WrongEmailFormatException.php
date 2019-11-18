<?php

/*
 * Written by Pop Sorin
 */

namespace Team1\Exception\Validator;


use Team1\Exception\AbstractException;
use Throwable;

/**
 * Class WrongEmailFormatException
 * @package Team1\Exception\Validator
 */
class WrongEmailFormatException extends AbstractException
{
    public function __construct($message = "Wrong email format", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}