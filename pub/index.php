<?php

namespace pub;

use Team1\Api\Controller\LoginController;
use Team1\Api\Controller\RegisterController;
use Team1\Api\Data\Request\CreateRequest;
use Team1\Api\Data\Request\LoginRequest;
use Team1\Entity\User;
use Team1\Exception\Persistency\AccountNotFoundException;
use Team1\Exception\Persistency\ConnectionLostException;
use Team1\Exception\Persistency\EmailAlreadyUsedException;
use Team1\Exception\Persistency\InsertionFailedException;
use Team1\Exception\Persistency\NameAlreadyExistsException;
use Team1\Exception\Persistency\SearchAccountFailedException;
use Team1\Service\Repository\UserRepository;
use Team1\Service\Validator\CreateUserValidator;

require("/home/sorin/Proiect-Colectiv/vendor/autoload.php");

$controller = new RegisterController();
$loginCtrl = new LoginController();

if($_SERVER["REQUEST_URI"] === "/register")
{
    $controller->displayHTML("/home/sorin/Proiect-Colectiv/src/Api/Pages/Register.html");
}

if($_SERVER["REQUEST_URI"] === "/Dummy.html")
{
    try {
        $createRequest = new CreateRequest($_POST["username"], $_POST["password"], $_POST["email"]);
        CreateUserValidator::validateUser($createRequest);
        $controller->add($createRequest);
    }
    catch (NameAlreadyExistsException $nameAlreadyExistsException){
        echo $nameAlreadyExistsException->getMessage();
    }
    catch (EmailAlreadyUsedException $emailAlreadyUsedException){
        echo $emailAlreadyUsedException->getMessage();
    }
    catch (InsertionFailedException $insertionFailedException){
        echo $insertionFailedException->getMessage();
    }
    catch (PasswordTooShortException $passwordTooShortException){
        echo $passwordTooShortException->getMessage();
    }
    catch (WrongEmailFormatException $wrongEmailFormatException){
        echo $wrongEmailFormatException->getMessage();
    }
}

if($_SERVER["REQUEST_URI"] === "/login")
{
    $controller->displayHTML("login.html");

}

if($_SERVER["REQUEST_URI"] === "/loginRedirect.html")
{
    try{
        $loginRequest = new LoginRequest($_POST['email'], $_POST['password']);

        $loginCtrl->logIn($loginRequest);
    }
    catch(AccountNotFoundException $exc)
    {
        echo $exc->getMessage();
    }
    catch(SearchAccountFailedException $exc)
    {
        echo $exc->getMessage();
    }

}