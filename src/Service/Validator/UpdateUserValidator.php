<?php

namespace Team1\Service\Validator;

use Team1\Exception\Validator\DescriptionTooLongException;
use Team1\Api\Data\Request\UpdateRquest;

/**
 * Class UpdateUserValidator
 * @package Team1\Service\Validator
 */
class UpdateUserValidator extends CreateUserValidator
{
    public static function validateAccount(UpdateRquest $request)
    {
        CreateUserValidator::validateUser($request);
        if (strlen($request->getDescription()) > 255) {
            throw new DescriptionTooLongException();
        }
    }
}
