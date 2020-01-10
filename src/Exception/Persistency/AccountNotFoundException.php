<?php
/*
 * Written by Andrei
 */


namespace Team1\Exception\Persistency;

use Team1\Exception\AbstractException;

/**
 * Class AccountNotFoundException
 * @package Team1\Exception\Persistency
 */
class AccountNotFoundException extends AbstractException
{
    /**
     * AccountNotFoundException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Account not found", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
