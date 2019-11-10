<?php

namespace pub;

use Team1\Exception\Validator\PasswordTooShortException;
use Team1\Api\Controller\RegisterController;
use Team1\Api\Data\Request\CreateRequest;
use Team1\Entity\User;
use Team1\Exception\Index\PasswordIsNotTheSameException;
use Team1\Exception\Index\TermsAndConditionsNotCheckedException;
use Team1\Exception\Persistency\ConnectionLostException;
use Team1\Exception\Persistency\EmailAlreadyUsedException;
use Team1\Exception\Persistency\InsertionFailedException;
use Team1\Exception\Persistency\NameAlreadyExistsException;
use Team1\Exception\Validator\WrongEmailFormatException;
use Team1\Service\Repository\UserRepository;
use Team1\Service\Validator\CreateUserValidator;

require("/home/sorin/Proiect-Colectiv/vendor/autoload.php");

$controller = new RegisterController();
if($_SERVER["REQUEST_URI"] === "/register")
{
    $controller->displayHTML("/home/sorin/Proiect-Colectiv/src/Api/Pages/Register.html");
    $controller->displayCSS("/home/sorin/Proiect-Colectiv/src/Api/Pages/Register.css");
}

if($_SERVER["REQUEST_URI"] === "/Dummy.html") {
    try {
        if (isset($_POST["terms_and_conditions"]) === false)
            throw new TermsAndConditionsNotCheckedException();
        if ($_POST["password"] !== $_POST["password_confirm"])
            throw new PasswordIsNotTheSameException();
        $createRequest = new CreateRequest($_POST["username"], $_POST["password"], $_POST["email"]);
        CreateUserValidator::validateUser($createRequest);
        $controller->add($createRequest);
    } catch (NameAlreadyExistsException $nameAlreadyExistsException) {
        echo $nameAlreadyExistsException->getMessage();
    } catch (EmailAlreadyUsedException $emailAlreadyUsedException) {
        echo $emailAlreadyUsedException->getMessage();
    } catch (InsertionFailedException $insertionFailedException) {
        echo $insertionFailedException->getMessage();
    } catch (PasswordTooShortException $passwordTooShortException) {
        echo $passwordTooShortException->getMessage();
    } catch (WrongEmailFormatException $wrongEmailFormatException) {
        echo $wrongEmailFormatException->getMessage();
    } catch (TermsAndConditionsNotCheckedException $termsAndConditionsNotCheckedException){
        echo $termsAndConditionsNotCheckedException->getMessage();
    } catch (PasswordIsNotTheSameException $passwordIsNotTheSameException){
        echo $passwordIsNotTheSameException->getMessage();
    } catch (WrongEmailFormatException $wrongEmailFormatException) {
        $wrongEmailFormatException->getMessage();
    }
}
