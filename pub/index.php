<?php

/*
 * Written by Pop Sorin & Nita Andrei & Popa Alexandru
 */

namespace pub;

use Team1\Api\Controller\ChatterController;
use Team1\Api\Data\Request\ChatterRequest;
use Team1\Api\Data\Request\MessageRequest;
use Team1\Api\Data\Request\UpdateProfileRequest;
use Team1\Exception\Persistency\AlreadyOnlineException;
use Team1\Exception\Persistency\EmailNullException;
use Team1\Exception\Persistency\GetMessagesException;
use Team1\Exception\Persistency\ReturnAllFailedException;
use Team1\Exception\Validator\PasswordTooShortException;
use PDO;
use PDOException;
use Team1\Api\Controller\AccountController;
use Team1\Api\Controller\RegisterController;
use Team1\Api\Controller\QueueController;
use Team1\Api\Data\Request\CreateRequest;
use Team1\Api\Data\Request\LoginRequest;
use Team1\Api\Data\Request\UpdateRquest;
use Team1\Api\Data\Request\QueuerRequest;
use Team1\Exception\Index\PasswordIsNotTheSameException;
use Team1\Exception\Index\TermsAndConditionsNotCheckedException;
use Team1\Exception\Persistency\AccountNotFoundException;
use Team1\Exception\Persistency\ConnectionLostException;
use Team1\Exception\Persistency\EmailAlreadyUsedException;
use Team1\Exception\Persistency\InsertionFailedException;
use Team1\Exception\Persistency\NameAlreadyExistsException;
use Team1\Exception\Persistency\SearchAccountFailedException;
use Team1\Exception\Validator\WrongEmailFormatException;
use Team1\Service\Validator\CreateUserValidator;
use Team1\Service\Validator\UpdateUserValidator;

//require "/home/sorin/Proiect-Colectiv/vendor/autoload.php";
require "/var/www/html/my_project/mealbreak/vendor/autoload.php";



try {
    $registerController = new RegisterController();
    $accountController = new AccountController();
    $chatterController = new ChatterController();
    $queueController = new QueueController();
}
catch (ConnectionLostException $connectionLostException) {
    echo $connectionLostException->getMessage();
    die();
}

//sends to the browser the current user id and email
if($_SERVER["REQUEST_URI"] === "/chatters")
{
    session_start();
    try {
        $account = $accountController->searchById($_SESSION['id']);
        echo json_encode(array("id" => $_SESSION['id'],
                               "name" => $_SESSION['name'],
                                "description" => $account->getDescription(),
                                "email" => $account->getEmail(),
                                "age" => $account->getAge()
        ));
    } catch (AccountNotFoundException $e) {
        echo $e->getMessage();
    } catch (SearchAccountFailedException $e) {
        echo $e->getMessage();
    }
}

//sends to the browser the partner of the current user
if($_SERVER["REQUEST_URI"] === "/partner")
{
    try {
        $chatter = $chatterController->searchPartner($_POST['to_user_id']);
        echo json_encode(array("id" => $chatter->getIdPartener(), "name" => $chatter->getName()));
    }
    catch (AccountNotFoundException $accountNotFoundException){
        echo $accountNotFoundException->getMessage();
    }
}

if($_SERVER["REQUEST_URI"] === "/") {
    session_start();
    $registerController->displayHTML(dirname(__DIR__) . "/src/Api/Pages/mainpage.html");
    $registerController->displayCSS(dirname(__DIR__) . "/src/Api/Pages/css/mainpage.css");
}
if($_SERVER["REQUEST_URI"] === "/register") {
 //
    $registerController->displayHTML(dirname(__DIR__) . "/src/Api/Pages/Register.html");
    $registerController->displayCSS(dirname(__DIR__) . "/src/Api/Pages/css/register.css");
    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST["register_sub"])) {
        $createRequest = new CreateRequest($_POST["username"], $_POST["psw"], $_POST["email"]);
        CreateUserValidator::validateUser($createRequest);
        $registerController->add($createRequest);
    }
    /*try {
        if ($_POST["password"] !== $_POST["password_confirm"])
            throw new PasswordIsNotTheSameException();
        $createRequest = new CreateRequest($_POST["username"], $_POST["psw"], $_POST["email"]);
        CreateUserValidator::validateUser($createRequest);
        $registerController->add($createRequest);
    } catch (EmailAlreadyUsedException $emailAlreadyUsedException) {
        echo $emailAlreadyUsedException->getMessage();
    } catch (InsertionFailedException $insertionFailedException) {
        echo $insertionFailedException->getMessage();
    } catch (PasswordTooShortException $passwordTooShortException) {
        echo $passwordTooShortException->getMessage();
    } catch (WrongEmailFormatException $wrongEmailFormatException) {
        echo $wrongEmailFormatException->getMessage();
    } catch (PasswordIsNotTheSameException $passwordIsNotTheSameException) {
        echo $passwordIsNotTheSameException->getMessage();
    }*/
}
/**

}

if($_SERVER["REQUEST_URI"] === "/chat") {
    try {
        if($_POST['email'] === null || $_POST['password'] === null)
            throw new EmailNullException();
        $registerController->displayHTML(dirname(__DIR__) . "/src/Api/Pages/Chat.html");
        }
        catch (EmailNullException $exception){
            echo $exception->getMessage();
        }
}
*/
//adds the latest message in the database
if($_SERVER["REQUEST_URI"] === "/updateMessage")
{
    try {
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
    catch (AccountNotFoundException $accountNotFoundException){
        echo $accountNotFoundException->getMessage();
    }
    catch (InsertionFailedException $insertionFailedException){
        echo $insertionFailedException->getMessage();
    }
    catch (GetMessagesException $getMessagesException){
        echo $getMessagesException->getMessage();
    }
}

if($_SERVER["REQUEST_URI"] === "/getLatestMessage") {
    try {
        $chatter = $chatterController->searchPartner($_POST['to_user_id']);
        $request = new ChatterRequest(
            $_POST["to_user_name"],
            $_POST["chat_message"],
            date('m/d/Y h:i:s a', time()),
            $chatter->getIdPartener(),
            $chatter->getIdAccount()
        );
        $chatterController->fetchUserChatHistory($chatter->getIdPartener(), $chatter->getIdAccount());
    } catch (AccountNotFoundException $accountNotFoundException) {
        echo $accountNotFoundException->getMessage();
    } catch (GetMessagesException $getMessagesException) {
        echo $getMessagesException->getMessage();

    }
}
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

if ($_SERVER["REQUEST_URI"] === "/php_match_script") {
    $start = microtime(true);
    set_time_limit(60);
    for($i = 0;$i < 60; ++$i) {
        if(count($queueController->getRepo()->getAll()) >=2) {
            if($chatters = $queueController->tryToMatch()) {
                $account1 = $accountController->searchById($chatters[0]);
                $chatterRequest = new ChatterRequest($account1->getName(),"",date('m/d/Y h:i:s a', time()),$chatters[0],$chatters[1]);
                $chatterController->add($chatterRequest);
                $account2 = $accountController->searchById($chatters[1]);
                $chatterRequest = new ChatterRequest($account2->getName(),"",date('m/d/Y h:i:s a', time()),$chatters[1],$chatters[0]);
                $chatterController->add($chatterRequest);
                $queueController->delete($chatters);
            }
            time_sleep_until($start + $i + 1);
        }
    }

}
if ($_SERVER["REQUEST_URI"] === "/match") {
    session_start();
    $queuerRequest = new QueuerRequest($_SESSION["id"]);
    $queueController->add($queuerRequest);

    for($second = 0;$second<120;++$second) {
        echo $second."<br>";
        if($chatterController->checkIfChatGenerated($_SESSION["id"])) {
            //give the response to the user
            //start the chat or redirect him to another page to start the chat
            $second = 120;
        }
        sleep(1);
    }
}

if ($_SERVER["REQUEST_URI"] === "/updateProfile"){
    session_start();
    var_dump($_POST);
    if(isset($_POST)){
        $updateRequest = new UpdateRquest(
                         $_SESSION['id'],
                         $_POST['name'],
                         $_SESSION['email'],
                         "",
                         $_POST['description'],
                         '',
                         '',
                         1,
                         intval($_POST['age'])
        );
        try {
            $accountController->update($updateRequest);
        } catch (EmailAlreadyUsedException $e) {
        } catch (InsertionFailedException $e) {
        } catch (NameAlreadyExistsException $e) {
        }
    }
}

if ($_SERVER["REQUEST_URI"] === "/Dummy.html") {
    // I guess we should delete this
    //Its not useful and the functions are not up to date
    var_dump($_SESSION["account_id"]);
    $queueController->displayHTML(dirname(__DIR__) . "/src/Api/Pages/loginRedirect.html");
    $registerController->displayCSS(dirname(__DIR__) . "/src/Api/Pages/Dummy.css");
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
    $registerController->displayHTML(dirname(__DIR__) . "/src/Api/Pages/login.html");
    $registerController->displayCSS(dirname(__DIR__) . "/src/Api/Pages/css/login.css");
    if (isset($_GET["token"])) {
        $user_array = $registerController->getRepo()->get_user_from_token($_GET["token"]);
        if ($registerController->getRepo()->getIsConfirmed($user_array['name']) == 0) {
            $current_time = strtotime(date("Y-m-d H:i:s"));
            $user_time = strtotime($registerController->getRepo()->getDate($user_array['name']));
            if ($current_time - $user_time < 7200) {
                $registerController->getRepo()->setIsConfirmed($user_array['name']);
                $UpdateRquest = new UpdateRquest($user_array["id"], $user_array["name"], $user_array["email"], $user_array["password"], "", "", "", 0, 0);
                UpdateUserValidator::validateAccount($UpdateRquest);
                $accountController->addAcc($UpdateRquest);
            }
            else{
                //we should redirect them to a page to resent an activation mail
                //echo "Your token is no longer valid.To get a new one please go to and complete your email<a href=\"token.php\">Click Here!</a>";
            }
        }
        }
    }

if($_SERVER["REQUEST_URI"] === "/logout")
{
    session_start();
    session_unset();

    $_SESSION['email'] = null;
    $_SESSION['id'] = null;
    $_SESSION['name'] = null;
    header("/login");
}
if($_SERVER["REQUEST_URI"] === "/loginRedirect")
{
    session_start();

    try{
        $_SESSION["email"] = $_POST['email'];
        $loginRequest = new LoginRequest($_POST['email'], $_POST['password']);
        $account = $accountController->logIn($loginRequest);
        $registerController->displayHTML(dirname(__DIR__) . "/src/Api/Pages/account.html");
        $registerController->displayCSS(dirname(__DIR__) . "/src/Api/Pages/css/account.css");
        $_SESSION["id"] = $account->getId();
        $_SESSION["name"] = $account->getName();
    }
    catch(AccountNotFoundException $exc)
    {
                //we should redirect them to a page to resent an activation mail
                //echo "Your token is no longer valid.To get a new one please go to and complete your email<a href=\"token.php\">Click Here!</a>";
    }
    /*
    $registerController->displayHTML(dirname(__DIR__) . "/src/Api/Pages/login.html");
    $registerController->displayCSS(dirname(__DIR__) . "/src/Api/Pages/login.css");
    if(isset($_POST['login1'])) {
        $_SESSION['account_id'] = $registerController->getRepo()->getIdFromMail($_POST['email']);
        //redirected here so I can test the functionality
        //we have to redirect to another page
        header("location:/Dummy.html");
    }*/
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