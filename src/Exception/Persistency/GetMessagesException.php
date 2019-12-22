<?php

namespace Team1\Exception\Persistency;

use Team1\Exception\AbstractException;
use Throwable;

/**
 * Class GetMessagesException
 * @package Team1\Exception\Persistency
 */
class GetMessagesException extends AbstractException
{
    /**
     * GetMessagesException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Could not get the messages", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
