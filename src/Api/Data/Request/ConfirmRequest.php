<?php

/*
 * Written by Pop Sorin
 */

namespace Team1\Api\Data\Request;

use Team1\Api\Data\Request\CreateRequest;

/**
 * Class ConfirmRequest
 * @package Team1\Api\Data\Request
 */
class ConfirmRequest extends CreateRequest
{
    /**
     * ConfirmRequest constructor.
     * @param string $name
     * @param string $apassword
     * @param string $email
     */
    public function __construct(string $name, string $password, string $email)
    {
        parent::__construct($name, $password, $email);
        $this->confirmed = true;
    }
}
