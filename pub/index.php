<?php

/*
 * Written by Pop Sorin & Nita Andrei
 */

namespace pub;

use PDO;
use PDOException;
use Team1\Api\Controller\LoginController;
use Team1\Api\Controller\RegisterController;
use Team1\Api\Controller\QueueController;
use Team1\Api\Data\Request\CreateRequest;
use Team1\Api\Data\Request\LoginRequest;
use Team1\Api\Data\Request\UpdateRquest;
use Team1\Exception\Index\PasswordIsNotTheSameException;
use Team1\Exception\Index\TermsAndConditionsNotCheckedException;
use Team1\Exception\Persistency\AccountNotFoundException;
use Team1\Exception\Persistency\ConnectionLostException;
use Team1\Exception\Persistency\EmailAlreadyUsedException;
use Team1\Exception\Persistency\InsertionFailedException;
use Team1\Exception\Persistency\NameAlreadyExistsException;
use Team1\Exception\Persistency\SearchAccountFailedException;
use Team1\Exception\Validator\PasswordTooShortException;
use Team1\Exception\Validator\WrongEmailFormatException;
use Team1\Service\Validator\CreateUserValidator;
use Team1\Service\Validator\UpdateUserValidator;

require "/var/www/html/my_project/mealbreak/vendor/autoload.php";

try {
    $requestController = new RegisterController();
    $loginController = new LoginController();
    $queueController = new QueueController();
} catch (ConnectionLostException $connectionLostException) {
    echo $connectionLostException->getMessage();
}

if ($_SERVER["REQUEST_URI"] === "/register") {

    $requestController->displayHTML(dirname(__DIR__) . "/src/Api/Pages/Register.html");
    $requestController->displayCSS(dirname(__DIR__) . "/src/Api/Pages/Register.css");
    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST["register_sub"])) {
        try {
            if (isset($_POST["terms_and_conditions"]) === false) {
                throw new TermsAndConditionsNotCheckedException();
            }
            if ($_POST["password"] !== $_POST["password_confirm"])
                throw new PasswordIsNotTheSameException();
            $createRequest = new CreateRequest($_POST["username"], $_POST["password"], $_POST["email"]);
            CreateUserValidator::validateUser($createRequest);
            $requestController->add($createRequest);
            header("location: Dummy.html");
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
        } catch (TermsAndConditionsNotCheckedException $termsAndConditionsNotCheckedException) {
            echo $termsAndConditionsNotCheckedException->getMessage();
        } catch (PasswordIsNotTheSameException $passwordIsNotTheSameException) {
            echo $passwordIsNotTheSameException->getMessage();
        } catch (WrongEmailFormatException $wrongEmailFormatException) {
            $wrongEmailFormatException->getMessage();
        }
    }
}

if ($_SERVER["REQUEST_URI"] === "/Dummy.html") {
    session_start();
    var_dump($_SESSION["account_id"]);
    $queueController->displayHTML(dirname(__DIR__) . "/src/Api/Pages/loginRedirect.html");
    $requestController->displayCSS(dirname(__DIR__) . "/src/Api/Pages/Dummy.css");
    if(isset($_POST["generate"])) {
        if($match = $queueController->tryToMatch($_SESSION['account_id'])) {
            //create a chat with the $match->getAccountId() and $_SESSION("account_id") (the account id's of the ppl that were matched)
            $queueController->delete($match->getAccountId());
            //the generation button shall be removed from this user

        }
        else {
            $queueController->add($_SESSION["account_id"]);
            //the generation button shall be removed from this user

        }
    }
}
if ($_SERVER["REQUEST_URI"] === "/login" || substr($_SERVER["REQUEST_URI"], 0, 7) === "/login?") {
    session_start();
    if (isset($_GET["token"])) {
        $user_array = $requestController->getRepo()->get_user_from_token($_GET["token"]);
        if ($requestController->getRepo()->getIsConfirmed($user_array['username']) == 0) {
            $current_time = strtotime(date("Y-m-d H:i:s"));
            $user_time = strtotime($requestController->getRepo()->getDate($user_array['username']));
            if ($current_time - $user_time < 7200) {
                $requestController->getRepo()->setIsConfirmed($user_array['username']);
                $UpdateRquest = new UpdateRquest($user_array["id"],$user_array["username"],$user_array["email"],$user_array["password"],"","","","",0);
                UpdateUserValidator::validateAccount($UpdateRquest);
                $loginController->addAcc($UpdateRquest);
            } else {

                //we should redirect them to a page to resent an activation mail
                //echo "Your token is no longer valid.To get a new one please go to and complete your email<a href=\"token.php\">Click Here!</a>";
            }
        }
    }
    $requestController->displayHTML(dirname(__DIR__) . "/src/Api/Pages/login.html");
    $requestController->displayCSS(dirname(__DIR__) . "/src/Api/Pages/login.css");
    if(isset($_POST['login1'])) {
        $_SESSION['account_id'] = $requestController->getRepo()->getIdFromMail($_POST['email']);
        //redirected here so I can test the functionality
        //we have to redirect to another page
        header("location:/Dummy.html");
    }
}

if ($_SERVER["REQUEST_URI"] === "/loginRedirect.html") {
    session_start();
    try {
        $loginRequest = new LoginRequest($_POST['email'], $_POST['password']);

        $loginController->logIn($loginRequest);
    } catch (AccountNotFoundException $exc) {
        echo $exc->getMessage();
    } catch (SearchAccountFailedException $exc) {
        echo $exc->getMessage();
    } catch (ConnectionLostException $exception) {
        echo $exception->getMessage();
    }
}
if ($_SERVER["REQUEST_URI"] === "/Ajax_request") {
    function connect_to_database()
    {
        $host = 'localhost';
        $db = 'MealBreak';
        $username = 'root';
        $password = 'root';
        try {
            $connection = new PDO("mysql:host=$host;dbname=$db", $username, $password);
            // set the PDO error mode to exception
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //echo "Connected successfully";
            return $connection;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            return FALSE;
        }

    }

    if (isset($_POST["type"])) {
        switch ($_POST["type"]) {
            case "username":
                try {
                    $connection = connect_to_database();
                    $query = $connection->prepare("select * from Users where username = ?;");
                    $query->execute(array($_POST["data"]));
                    $result = $query->fetch(PDO::FETCH_ASSOC);
                    if (!empty($result)) {
                        throw new NameAlreadyExistsException();
                    }
                } catch (NameAlreadyExistsException $nameAlreadyExistsException) {
                    echo $nameAlreadyExistsException->getMessage();
                }
                break;
            case "email":
                try {
                    $conn = connect_to_database();
                    $query = $conn->prepare("select * from Users where email = ?;");
                    $query->execute(array($_POST["data"]));
                    $result = $query->fetch(PDO::FETCH_ASSOC);
                    if (!empty($result)) {
                        throw new EmailAlreadyUsedException();
                    }
                } catch (EmailAlreadyUsedException $emailAlreadyUsedException) {
                    echo $emailAlreadyUsedException->getMessage();
                }
                break;
            case "password":
                try {
                    if (strlen($_POST['data']) < 8) {
                        throw new PasswordTooShortException();
                    }
                } catch (PasswordTooShortException $passwordTooShortException) {
                    echo $passwordTooShortException->getMessage();
                }
                break;
            case "password_confirm":
                try {
                    if ($_POST["password"] !== $_POST["data"]) {
                        throw new PasswordIsNotTheSameException();
                    }
                } catch (PasswordIsNotTheSameException $passwordIsNotTheSameException) {
                    echo $passwordIsNotTheSameException->getMessage();
                }
                break;
        }
    }
}