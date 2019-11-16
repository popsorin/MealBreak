<?php

/*
 * Written by Pop Sorin
 */

namespace Team1\Exception\Persistency;

use Team1\Exception\AbstractException;
use Throwable;

/**
 * Class SearchForEmailFailed
 * @package Team1\Exception\Persistency
 */
class SearchForEmailFailed extends AbstractException
{
    /**
     * SearchForEmailFailed constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Search for email failed", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
