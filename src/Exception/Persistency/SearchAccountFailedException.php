<?php
/*
 * Written by Andrei
 */

namespace Team1\Exception\Persistency;

use Team1\Exception\AbstractException;

/**
 * Class SearchAccountFailedException
 * @package Team1\Exception\Persistency
 */
class SearchAccountFailedException extends AbstractException
{
    /**
     * SearchAccountFailedException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Search for account has failed", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
