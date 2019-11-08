<?php


namespace Team1\Exception\Persistency;

use Team1\Exception\AbstractException;
use Throwable;

/**
 * Class NameAlreadyExistsException
 * @package Team1\Exception\Persistency
 */
class NameAlreadyExistsException extends AbstractException
{
    /**
     * NameAlreadyExistsException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Username is already taken", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
