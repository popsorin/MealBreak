<?php

/*
 * Written by Pop Sorin & Nita Andrei
 */

namespace pub;

use Team1\Api\Controller\ChatterController;
use Team1\Api\Data\Request\ChatterRequest;
use Team1\Api\Data\Request\MessageRequest;
use Team1\Exception\Persistency\EmailNullException;
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

session_start();
try {
    $requestController = new RegisterController();
    $loginController = new LoginController();
    $chatterController = new ChatterController();
} catch (ConnectionLostException $connectionLostException) {
    echo $connectionLostException->getMessage();
}

if($_SERVER["REQUEST_URI"] === "/chatters")
{
    echo json_encode(array("id" => $_SESSION['id'], "name" => $_SESSION["email"]));
}

if($_SERVER["REQUEST_URI"] === "/partner")
{
    $chatter = $chatterController->searchPartner($_POST['to_user_id']);
    echo json_encode(array("id" => $chatter->getIdPartener(), "name" => $chatter->getName()));
}

if($_SERVER["REQUEST_URI"] === "/") {
    $requestController->displayHTML(dirname(__DIR__) . "/src/Api/Pages/StartPage.html");
    $requestController->displayCSS(dirname(__DIR__) . "/src/Api/Pages/StartPage.css");
}
if($_SERVER["REQUEST_URI"] === "/register")
{
        try
         {
            $requestController->displayHTML(dirname(__DIR__) . "/src/Api/Pages/Register.html");
            $requestController->displayCSS(dirname(__DIR__) . "/src/Api/Pages/Register.css");
            if (isset($_POST["terms_and_conditions"]) === false)
                throw new TermsAndConditionsNotCheckedException();
            if ($_POST["password"] !== $_POST["password_confirm"])
                throw new PasswordIsNotTheSameException();
            $createRequest = new CreateRequest($_POST["username"],$_POST["password"], $_POST["email"]);
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

if($_SERVER["REQUEST_URI"] === "/chat") {
    try {
        if($_POST['email'] === null || $_POST['password'] === null)
            throw new EmailNullException();
        $requestController->displayHTML(dirname(__DIR__) . "/src/Api/Pages/Chat.html");
        }
        catch (EmailNullException $exception){
            echo $exception->getMessage();
        }
}
if($_SERVER["REQUEST_URI"] === "/updateMessage")
{
        $chatter = $chatterController->searchPartner($_POST['to_user_id']);
        $request = new ChatterRequest(
            $_POST["to_user_name"],
            $_POST["chat_message"],
            date('m/d/Y h:i:s a', time()),
            $chatter->getIdPartener(),
            $chatter->getIdAccount()
        );
        $chatterController->add($request);
        $chatterController->fetchUserChatHistory($chatter->getIdPartener(), $chatter->getIdAccount());
}

if($_SERVER["REQUEST_URI"] === "/getLatestMessage")
{
    $chatter = $chatterController->searchPartner($_POST['to_user_id']);
    $request = new ChatterRequest(
        $_POST["to_user_name"],
        $_POST["chat_message"],
        date('m/d/Y h:i:s a', time()),
        $chatter->getIdPartener(),
        $chatter->getIdAccount()
    );
    $chatterController->fetchUserChatHistory($chatter->getIdPartener(), $chatter->getIdAccount());
}

if($_SERVER["REQUEST_URI"] === "/login")
{

    $requestController->displayHTML(dirname(__DIR__) . "/src/Api/Pages/login.html");
    $requestController->displayCSS(dirname(__DIR__) . "/src/Api/Pages/login.css");

}

if($_SERVER["REQUEST_URI"] === "/loginRedirect")
{
    try{
        if($_POST['email'] === null || $_POST['password'] === null)
            throw new EmailNullException();
        $_SESSION["email"] = $_POST['email'];
        $requestController->displayHTML(dirname(__DIR__) . "/src/Api/Pages/Chat.html");
        $loginRequest = new LoginRequest($_POST['email'], $_POST['password'],$_SESSION["token"],$_SESSION["token"]);
        $account = $loginController->logIn($loginRequest);
        $_SESSION['id'] = $account->getId();
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
    catch (EmailNullException $exception){
        echo $exception->getMessage();
    }
}


