<?php

/*
 * Written by Pop Sorin
 */

namespace Team1\Exception\Persistency;

use Team1\Exception\AbstractException;
use Throwable;

/**
 * Class UserNotFoundException
 * @package Team1\Exception\Persistency
 */
class UserNotFoundException extends AbstractException
{
    /**
     * UserNotFoundException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "User not found", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
