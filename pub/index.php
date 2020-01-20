<?php

/*
 * Written by Pop Sorin & Nita Andrei & Popa Alexandru
 */

namespace pub;

use Team1\Api\Controller\ChatterController;
use Team1\Api\Controller\PubController;
use Team1\Api\Data\Request\ChatterRequest;
use Team1\Api\Data\Request\DeleteChatterRequest;
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

require "/home/sorin/Proiect-Colectiv/vendor/autoload.php";
//require "/var/www/html/my_project/mealbreak/vendor/autoload.php";



try {
    $registerController = new RegisterController();
    $accountController = new AccountController();
    $chatterController = new ChatterController();
    $queueController = new QueueController();
    $pubController = new PubController();
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
                               "name" => $account->getName(),
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
    if (isset($_SESSION["loggedIn"])) {
        $registerController->displayHTML(dirname(__DIR__) . "/src/Api/Pages/account.html");
        $registerController->displayCSS(dirname(__DIR__) . "/src/Api/Pages/css/account.css");
    }
    else {
        $registerController->displayHTML(dirname(__DIR__) . "/src/Api/Pages/mainpage.html");
        $registerController->displayCSS(dirname(__DIR__) . "/src/Api/Pages/css/mainpage.css");
        if (isset($_POST)) {
            $createRequest = new CreateRequest($_POST["name"], $_POST["psw"], $_POST["email"]);
            CreateUserValidator::validateUser($createRequest);
            $registerController->add($createRequest);
        }
    }
}

if($_SERVER["REQUEST_URI"] === "/register") {
 //
    $registerController->displayHTML(dirname(__DIR__) . "/src/Api/Pages/Register.html");
    $registerController->displayCSS(dirname(__DIR__) . "/src/Api/Pages/css/register.css");

}

//adds the latest message in the database
if($_SERVER["REQUEST_URI"] === "/updateMessage")
{
    session_start();
    try {
        $chatter = $chatterController->searchPartner($_COOKIE['partnerId']);
        $request = new ChatterRequest(
            $chatter->getName(),
            $_POST["message"],
            date('m/d/Y h:i:s a', time()),
            $chatter->getIdAccount(),
            $chatter->getIdPartener(),
            $_SESSION["idPub"]
        );
        $chatterController->add($request);
        $chatterController->fetchUserChatHistory( $chatter->getIdAccount(), $chatter->getIdPartener());
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
        $chatter = $chatterController->searchPartner($_COOKIE['partnerId']);
        $chatterController->fetchUserChatHistory( $chatter->getIdAccount(), $chatter->getIdPartener());
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
    session_start();

    $start = microtime(true);
    set_time_limit(60);
    for($i = 0;$i < 60; ++$i) {
        if(count($queueController->getRepo()->getAll()) >=2) {
            if($chatters = $queueController->tryToMatch()) {
                $account1 = $accountController->searchById($chatters[0]);
                $random = $pubController->randomPub();
                $chatterRequest = new ChatterRequest($account1->getName(),
                    "",
                    date('m/d/Y h:i:s a', time()),
                    $chatters[0],
                    $chatters[1],
                    $random
                );
                $chatterController->add($chatterRequest);
                $account2 = $accountController->searchById($chatters[1]);
                $chatterRequest = new ChatterRequest($account2->getName(),
                    "",
                    date('m/d/Y h:i:s a',
                    time()),
                    $chatters[1],
                    $chatters[0],
                    $random
                );
                $chatterController->add($chatterRequest);
                $queueController->delete($chatters);
            }
            time_sleep_until($start + $i + 1);
        }
    }

}

if ($_SERVER["REQUEST_URI"] === "/meeting") {
    $registerController->displayHTML(dirname(__DIR__) . "/src/Api/Pages/postmatch.html");
    $registerController->displayCSS(dirname(__DIR__) . "/src/Api/Pages/css/postmatch.css");
}

if ($_SERVER["REQUEST_URI"] === "/postMatch") {
    session_start();

    $chatter = $chatterController->searchPartner($_COOKIE['partnerId']);
    $pub = $pubController->getPub($chatter->getPub());
    $_SESSION["idPub"] = $chatter->getPub();
    echo json_encode(array("id" => $_SESSION['id'],
        "name" => $_COOKIE["partnerName"],
        "description" => $_COOKIE["partnerDescription"],
        "age" => $_COOKIE["partnerAge"],
        "location" => $pub->getLocation(),
        "pubName" => $pub->getName()
    ));
}

if ($_SERVER["REQUEST_URI"] === "/match") {

    session_start();

    $queuerRequest = new QueuerRequest($_SESSION["id"]);
    $queueController->add($queuerRequest);

    for($second = 0;$second<120;++$second) {
        if($chatterController->checkIfChatGenerated($_SESSION["id"])) {
            //give the response to the user
            //start the chat or redirect him to another page to start the chat
            $second = 120;
        }
        sleep(1);
    }

    try {
        $chatter = $chatterController->searchPartner($_SESSION['id']);
        $account = $accountController->searchById($chatter->getIdAccount());
        echo json_encode(array("id" => $account->getId(),
            "uri" => "/meeting",
        ));
        setcookie("partnerName", $account->getName());
        setcookie("partnerDescription", $account->getDescription());
        setcookie("partnerAge", $account->getAge());
        setcookie("partnerId", $account->getId());
    } catch (AccountNotFoundException $e) {
        echo $e->getMessage();
    } catch (SearchAccountFailedException $e) {
        echo $e->getMessage();
    }
}
if ($_SERVER["REQUEST_URI"] === "/restaurants"){
    $registerController->displayHTML(dirname(__DIR__) . "/src/Api/Pages/restaurants.html");
    $registerController->displayCSS(dirname(__DIR__) . "/src/Api/Pages/css/restaurants.css");

}
if ($_SERVER["REQUEST_URI"] === "/updateProfile"){
    session_start();

    if(isset($_POST)){
        $account = $accountController->searchById($_SESSION['id']);
            if($_POST['age'] !== '')
            $age = intval($_POST['age']);
        else
            $age = $account->getAge();

        if($_POST['name'] !== '')
            $name = $_POST['name'];
        else
            $name = $account->getName();

        if($_POST['description'] !== '')
            $description = $_POST['description'];
        else
            $description = $account->getDescription();

        $updateRequest = new UpdateRquest(
                         $_SESSION['id'],
                         $name,
                         $account->getEmail(),
                         $account->getPassword(),
                         $description,
                         '',
                         '',
                         1,
                         $age
        );
        try {
            $accountController->update($updateRequest);
        } catch (EmailAlreadyUsedException $e) {
        } catch (InsertionFailedException $e) {
        } catch (NameAlreadyExistsException $e) {
        }
    }
}

if ($_SERVER["REQUEST_URI"] === "/login" || substr($_SERVER["REQUEST_URI"], 0, 7) === "/login?") {
    session_start();
    if ($_SESSION["loggedIn"] === 1) {
        $registerController->displayHTML(dirname(__DIR__) . "/src/Api/Pages/account.html");
        $registerController->displayCSS(dirname(__DIR__) . "/src/Api/Pages/css/account.css");
        }
    else{
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
                } else {
                    //we should redirect them to a page to resent an activation mail
                    //echo "Your token is no longer valid.To get a new one please go to and complete your email<a href=\"token.php\">Click Here!</a>";
                }
            }
        }
    }
}

if(substr($_SERVER["REQUEST_URI"], 0, 8) === "/logout?")
{
    session_start();
    try {
        $account = $accountController->searchById($_SESSION['id']);
        $updateRequest = new UpdateRquest(
            $_SESSION['id'],
            $account->getName(),
            $account->getEmail(),
            $account->getPassword(),
            $account->getDescription(),
            $account->getNickname(),
            $account->getQueueStartTime(),
            0,
            $account->getAge()
        );
        $accountController->update($updateRequest);
    } catch (AccountNotFoundException $e) {
        echo $e->getMessage();
    } catch (SearchAccountFailedException $e) {
        echo $e->getMessage();
    }
    session_unset();

    setcookie("partnerName", time() - 3600);
    setcookie("partnerDescription", time() - 3600);
    setcookie("partnerAge", time() - 3600);
    setcookie("partnerId", time() - 3600);
    setcookie("id", time() - 3600);
    setcookie("loggedIn", time() - 3600);
    $_SESSION['email'] = null;
    $_SESSION['id'] = null;
    $_SESSION['name'] = null;
}

if($_SERVER["REQUEST_URI"] === "/arrived") {
    try {
        $chatter = $chatterController->searchPartner($_COOKIE['partnerId']);
    } catch (AccountNotFoundException $e) {
        $registerController->displayHTML(dirname(__DIR__) . "/src/Api/Pages/account.html");
        $registerController->displayCSS(dirname(__DIR__) . "/src/Api/Pages/css/account.css");
    }
    $request = new DeleteChatterRequest(
        $chatter->getIdAccount(),
        $chatter->getIdPartener()
    );
    $chatterController->deleteChatters($request);

    $registerController->displayHTML(dirname(__DIR__) . "/src/Api/Pages/account.html");
    $registerController->displayCSS(dirname(__DIR__) . "/src/Api/Pages/css/account.css");
}

if($_SERVER["REQUEST_URI"] === "/loginRedirect")
{
    session_start();

    try {
        if ($_COOKIE["loggedIn"] === 1) {
            $registerController->displayHTML(dirname(__DIR__) . "/src/Api/Pages/account.html");
            $registerController->displayCSS(dirname(__DIR__) . "/src/Api/Pages/css/account.css");
        }
        else
            if (isset($_POST['email'])) {
                $loginRequest = new LoginRequest($_POST['email'], $_POST['password']);
                $account = $accountController->logIn($loginRequest);
                $registerController->displayHTML(dirname(__DIR__) . "/src/Api/Pages/account.html");
                $registerController->displayCSS(dirname(__DIR__) . "/src/Api/Pages/css/account.css");
                $_SESSION["email"] = $account->getEmail();
                $_SESSION["id"] = $account->getId();
                $_SESSION["name"] = $account->getName();
                $_SESSION["loggedIn"] = 1;
                setcookie("id", $account->getId());

            }
            else{

                $registerController->displayHTML(dirname(__DIR__) . "/src/Api/Pages/account.html");
                $registerController->displayCSS(dirname(__DIR__) . "/src/Api/Pages/css/account.css");
            }


    } catch(AccountNotFoundException $exc)
    {
                //we should redirect them to a page to resent an activation mail
                //echo "Your token is no longer valid.To get a new one please go to and complete your email<a href=\"token.php\">Click Here!</a>";
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