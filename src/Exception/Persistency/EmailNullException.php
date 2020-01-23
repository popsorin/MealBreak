<?php


namespace Team1\Exception\Persistency;


use Team1\Exception\AbstractException;
use Throwable;

/**
 * Class EmailNullException
 * @package Team1\Exception\Persistency
 */
class EmailNullException extends AbstractException
{
    /**
     * EmailNullException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "email or password are null", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
