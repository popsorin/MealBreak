<?php
/*
 * Written by Andrei
 */

namespace Team1\Exception\Persistency;

use Team1\Exception\AbstractException;

/**
 * Class AccountDeletionFailedException
 * @package Team1\Exception\Persistency
 */
class AccountDeletionFailedException extends AbstractException
{
    /**
     * AccountDeletionFailedException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Failed to delete an account", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
