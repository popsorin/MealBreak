<?php

/*
 * Written by Pop Sorin & Nita Andrei
 */

namespace pub;

use Team1\Exception\Validator\PasswordTooShortException;
use Team1\Api\Controller\LoginController;
use Team1\Api\Controller\RegisterController;
use Team1\Api\Data\Request\CreateRequest;
use Team1\Api\Data\Request\LoginRequest;
use Team1\Entity\User;
use Team1\Exception\Index\PasswordIsNotTheSameException;
use Team1\Exception\Index\TermsAndConditionsNotCheckedException;
use Team1\Exception\Persistency\AccountNotFoundException;
use Team1\Exception\Persistency\ConnectionLostException;
use Team1\Exception\Persistency\EmailAlreadyUsedException;
use Team1\Exception\Persistency\InsertionFailedException;
use Team1\Exception\Persistency\NameAlreadyExistsException;
use Team1\Exception\Validator\WrongEmailFormatException;
use Team1\Exception\Persistency\SearchAccountFailedException;
use Team1\Service\Repository\UserRepository;
use Team1\Service\Validator\CreateUserValidator;

require("/home/sorin/Proiect-Colectiv/vendor/autoload.php");

try {
    $requestController = new RegisterController();
    $loginController = new LoginController();
} catch (ConnectionLostException $connectionLostException) {
    echo $connectionLostException->getMessage();
}

if($_SERVER["REQUEST_URI"] === "/")
{
    $requestController->displayHTML(dirname(__DIR__) . "/src/Api/Pages/StartPage.html");
    $requestController->displayCSS(dirname(__DIR__) . "/src/Api/Pages/StartPage.css");

}

if($_SERVER["REQUEST_URI"] === "/register")
{
    $requestController->displayHTML(dirname(__DIR__) . "/src/Api/Pages/Register.html");
    $requestController->displayCSS(dirname(__DIR__) . "/src/Api/Pages/Register.css");
}

if($_SERVER["REQUEST_URI"] === "/Dummy.html") {
    try {
        if (isset($_POST["terms_and_conditions"]) === false)
            throw new TermsAndConditionsNotCheckedException();
        if ($_POST["password"] !== $_POST["password_confirm"])
            throw new PasswordIsNotTheSameException();
        $createRequest = new CreateRequest($_POST["username"], $_POST["password"], $_POST["email"]);
        CreateUserValidator::validateUser($createRequest);
        $requestController->add($createRequest);
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

if($_SERVER["REQUEST_URI"] === "/login")
{
    $requestController->displayHTML(dirname(__DIR__) . "/src/Api/Pages/login.html");
    $requestController->displayCSS(dirname(__DIR__) . "/src/Api/Pages/login.css");
}

if($_SERVER["REQUEST_URI"] === "/loginRedirect.html")
{
    try{
        $loginRequest = new LoginRequest($_POST['email'], $_POST['password']);

        $loginController->logIn($loginRequest);
    }
    catch(AccountNotFoundException $exc)
    {
        echo $exc->getMessage();
    }
    catch(SearchAccountFailedException $exc)
    {
        echo $exc->getMessage();
    }
    catch (ConnectionLostException $exception)
    {
        echo $exception->getMessage();
    }
}