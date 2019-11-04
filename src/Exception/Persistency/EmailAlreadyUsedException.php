<?php


namespace Team1\Exception\Persistency;

use Team1\Exception\AbstractException;
use Throwable;

/**
 * Class EmailAlreadyUsedException
 * @package Team1\Exception\Persistency
 */
class EmailAlreadyUsedException extends AbstractException
{
    /**
     * EmailAlreadyUsedException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Email is already used", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
