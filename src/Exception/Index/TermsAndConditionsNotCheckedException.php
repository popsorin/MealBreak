<?php

/*
 * Written by Pop Sorin
 */

namespace Team1\Exception\Index;

use Team1\Exception\AbstractException;
use Throwable;

/**
 * Class TermsAndConditionsNotCheckedException
 * @package Team1\Exception\Index
 */
class TermsAndConditionsNotCheckedException extends AbstractException
{
    public function __construct(
        $message = "Terms and conditions are not checked",
        $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
