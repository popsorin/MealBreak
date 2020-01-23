<?php

/*
 * Written by Popa Alexandru
 */

namespace Team1\Exception\Persistency;

use Team1\Exception\AbstractException;
use Throwable;

/**
 * Class QueuerNotFoundException
 * @package Team1\Exception\Persistency
 */
class QueuerNotFoundException extends AbstractException
{
    /**
     * QueuerNotFoundException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Queuer not found", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
