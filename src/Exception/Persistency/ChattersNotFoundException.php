<?php

namespace Team1\Exception\Persistency;

use Team1\Exception\AbstractException;
use Throwable;

/**
 * Class ChattersNotFoundException
 * @package Team1\Exception\Persistency
 */
class ChattersNotFoundException extends AbstractException
{
    /**
     * ChattersNotFoundException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Chatter not found", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
