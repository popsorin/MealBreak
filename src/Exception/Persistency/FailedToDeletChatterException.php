<?php

namespace Team1\Exception\Persistency;

use Team1\Exception\AbstractException;
use Throwable;

/**
 * Class FailedToDeletChatterException
 * @package Team1\Exception\Persistency
 */
class FailedToDeletChatterException extends AbstractException
{
    /**
     * FailedToDeletChatterException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Failed to delete chatter", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
