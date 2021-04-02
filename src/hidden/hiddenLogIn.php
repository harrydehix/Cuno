<?php

require_once 'utils/InputValidator.php';
require_once 'utils/DBManager.php';
require_once 'config/constants.php';
require_once 'utils/SessionManager.php';


$response = array();
$response["success"] = false;
$response["message"] = "Invalid username/email-password combination.";

if (isset($_POST['submit'])) {
    $dbManager = new DBManager(DB_HOST, DB_USER, DB_PASS, DB_NAME, true);

    $username = InputValidator::prepare($_POST["username"]);
    $password = InputValidator::prepare($_POST["password"]);

    if ($dbManager->isValidUser($username, $password)) {
        $response["success"] = true;

        $email = $dbManager->getUsersEmail($username);
        SessionManager::login(new UserData($username, $email, $dbManager->isVerified($username)));
    }
}
echo json_encode($response);
