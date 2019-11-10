<?php


namespace Team1\Service\Validator;

use Team1\Exception\Validator\PasswordTooShortException;
use Team1\Exception\Validator\WrongEmailFormatException;
use Team1\Api\Data\Request\CreateRequest;

/**
 * Class CreateUserValidator
 * @package Team1\Service\Validator
 */
class CreateUserValidator
{
    public static function validateUser(CreateRequest $request)
    {
        if(strlen($request->getPassword()) < 8)
        {
            throw new PasswordTooShortException();
        }
        if(strpos($request->getEmail(),"@") === null)
        {
            throw new WrongEmailFormatException();
        }
    }
}