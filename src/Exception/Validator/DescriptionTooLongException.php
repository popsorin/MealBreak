<?php

namespace Popsorin\Exception\Validator;

use Team1\Exception\AbstractException;
use Throwable;

/**
 * Class DescriptionTooLongException
 * @package Popsorin\Exception\Validator
 */
class DescriptionTooLongException extends AbstractException
{
    public function __construct($message = "Maximum number of characters is 255", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}