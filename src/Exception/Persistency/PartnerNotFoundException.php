<?php


namespace Team1\Exception\Persistency;


use Team1\Exception\AbstractException;
use Throwable;

/**
 * Class PartnerNotFoundException
 * @package Team1\Exception\Persistency
 */
class PartnerNotFoundException extends AbstractException
{
    /**
     * PartnerNotFoundException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Partner not found", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
