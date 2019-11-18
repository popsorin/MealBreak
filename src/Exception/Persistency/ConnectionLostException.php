<?php

/*
 * Written by Pop Sorin
 */

namespace Team1\Exception\Persistency;

use Team1\Exception\AbstractException;
use Throwable;

/**
 * Class ConnectionLostException
 * @package Team1\Exception\Persistency
 */
class ConnectionLostException extends AbstractException
{
    /**
     * ConnectionLostException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Connection lost", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
