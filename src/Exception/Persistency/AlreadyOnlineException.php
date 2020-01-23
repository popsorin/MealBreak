<?php

namespace Team1\Exception\Persistency;

use Team1\Exception\AbstractException;
use Throwable;

/**
 * Class AlreadyOnlineException
 * @package Team1\Exception\Persistency
 */
class AlreadyOnlineException extends AbstractException
{
    /**
     * AlreadyOnlineException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "User already logged", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
