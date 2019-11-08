<?php

namespace Team1\Exception\Persistency;

use Team1\Exception\AbstractException;
use Throwable;

/**
 * Class ReturnAllFailedException
 * @package Team1\Exception\Persistency
 */
class ReturnAllFailedException extends AbstractException
{
    /**
     * ReturnAllFailedException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Failed to return all the users", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
