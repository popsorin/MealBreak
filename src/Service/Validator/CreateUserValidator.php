<?php

/*
 * Written by Pop Sorin
 */

namespace Team1\Service\Validator;

use Team1\Exception\Validator\NameNullError;
use Team1\Exception\Validator\EmailNullError;
use Team1\Exception\Validator\PasswordNullError;
use Team1\Exception\Validator\PasswordTooShortException;
use Team1\Exception\Validator\WrongEmailFormatException;

use Team1\Api\Data\Request\CreateRequest;

/**
 * Class CreateUserValidator
 * @package Team1\Service\Validator
 */
class CreateUserValidator
{
    /**
     * @param CreateRequest $request
     * @throws PasswordTooShortException
     * @throws WrongEmailFormatException
     */
    public static function validateUser(CreateRequest $request)
    {
        if($request->getName() === "") {
            throw new NameNullError();
        }
        if($request->getEmail() === "") {
            throw new EmailNullError();
        }
        if($request->getPassword() === "") {
            throw new PasswordNullError();
        }
        if(strpos($request->getEmail(),"@") === null)
        {
            throw new WrongEmailFormatException();
        }
        if(strlen($request->getPassword()) < 8)
        {
            throw new PasswordTooShortException();
        }
    }
}