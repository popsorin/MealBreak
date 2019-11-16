<?php

/*
 * Written by Pop Sorin
 */

namespace Team1\Exception\Persistency;

use Team1\Exception\AbstractException;
use Throwable;

/**
 * Class DeletionFailedException
 * @package Team1\Exception\Persistency
 */
class DeletionFailedException extends AbstractException
{
    /**
     * DeletionFailedException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Failed to delete an user", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
