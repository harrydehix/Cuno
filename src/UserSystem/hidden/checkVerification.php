<?php
require_once 'utils/UserData.php';

session_start();


require_once 'config/constants.php';
require_once 'utils/InputValidator.php';
require_once 'utils/DBManager.php';


if (isset($_POST['username'])) {
    $username = InputValidator::prepare($_POST['username']);

    if (InputValidator::isValidUsername($username)) {
        $dbManager = new DBManager(DB_HOST, DB_USER, DB_PASS, DB_NAME, true);

        $verified = $dbManager->isVerified($username);

        if ($verified) {
            $_SESSION['userdata']->verified = true;
            echo "true";
            exit();
        }
    }
}
echo "false";
exit();
