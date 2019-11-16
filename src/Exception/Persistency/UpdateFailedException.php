<?php

/*
 * Written by Pop Sorin
 */

namespace Team1\Exception\Persistency;

use Team1\Exception\AbstractException;
use Throwable;

/**
 * Class UpdateFailedException
 * @package Team1\Exception\Persistency
 */
class UpdateFailedException extends AbstractException
{
    /**
     * UpdateFailedException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Update has failed", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
