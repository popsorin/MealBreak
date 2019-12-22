<?php

/*
 * Written by Pop Sorin
 */

namespace Team1\Service\Validator;

use Team1\Exception\Validator\DescriptionTooLongException;
use Team1\Api\Data\Request\UpdateRquest;
use Team1\Exception\Validator\PasswordTooShortException;
use Team1\Exception\Validator\WrongEmailFormatException;

/**
 * Class UpdateUserValidator
 * @package Team1\Service\Validator
 */
class UpdateUserValidator extends CreateUserValidator
{
    /**
     * @param UpdateRquest $request
     * @throws DescriptionTooLongException
     * @throws PasswordTooShortException
     * @throws WrongEmailFormatException
     */
    public static function validateAccount(UpdateRquest $request)
    {
        CreateUserValidator::validateUser($request);
        if (strlen($request->getDescription()) > 255) {
            throw new DescriptionTooLongException();
        }
    }
}
