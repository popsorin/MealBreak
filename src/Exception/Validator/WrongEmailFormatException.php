<?php


namespace Popsorin\Exception\Validator;


use Team1\Exception\AbstractException;
use Throwable;

/**
 * Class WrongEmailFormatException
 * @package Popsorin\Exception\Validator
 */
class WrongEmailFormatException extends AbstractException
{
    public function __construct($message = "Wrong email format", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}