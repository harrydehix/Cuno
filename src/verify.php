<?php
require_once 'hidden/utils/UserData.php';
session_start();

require_once 'hidden/utils/InputValidator.php';
require_once 'hidden/utils/DBManager.php';
require_once 'hidden/config/constants.php';

$success = false;

if (isset($_GET['token']) && isset($_GET['username'])) {
    $token = InputValidator::prepare($_GET['token']);
    $username = InputValidator::prepare($_GET['username']);

    if (InputValidator::isValidUsername($username)) {
        $dbManager = new DBManager(DB_HOST, DB_USER, DB_PASS, DB_NAME, true);
        $success = $dbManager->verifyUser($username, $token);
        if ($success) $_SESSION['userdata']->verified = true;
    }
} ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuno User Verification</title>
</head>

<body>
    <?php if ($success) : ?>
        <h1> Verification succeeded! </h1>
        <p> You are ready to <a href="login.php">login</a> and play Cuno! </p>
    <?php else : ?>
        <h1> Verification failed! </h1>
        <p> Ouuups... Something went wrong. We're sorry! </p>
    <?php endif; ?>
</body>

</html>