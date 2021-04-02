<?php

require_once 'utils/InputValidator.php';
require_once 'utils/DBManager.php';
require_once 'config/constants.php';
require_once 'utils/SessionManager.php';
require_once 'utils/EmailVerificator.php';

$dbManager = new DBManager(DB_HOST, DB_USER, DB_PASS, DB_NAME, true);

if (isset($_POST['submit'])) {
    $username = InputValidator::prepare($_POST['username']);
    $email = InputValidator::prepare($_POST['email']);
    $password = InputValidator::prepare($_POST['password']);
    $passwordConfirm = InputValidator::prepare($_POST['passwordConfirm']);
    $agreement = InputValidator::prepare($_POST['agreement']);

    $response = array();
    $response["success"] = true;
    $response["message"] = "";

    // INPUT VALIDATION
    // Username
    if (!InputValidator::isValidUsername($username)) {
        $response["success"] = false;
        $response["username"] = "Invalid username.";
    } else {
        if (!$dbManager->isUniqueUsername($username)) {
            $response["success"] = false;
            $response["username"] = "Username aldready exists. Try another.";
        } else {
            $response["username"] = "Perfect!";
        }
    }
    // Email
    if (!InputValidator::isValidEmail($email)) {
        $response["success"] = false;
        $response["email"] = "Invalid email.";
    } else {
        if (!$dbManager->isUniquEemail($email)) {
            $response["success"] = false;
            $response["email"] = "There's already an account using that email.";
        } else {
            $response["email"] = "Perfect!";
        }
    }
    // Password
    if (!InputValidator::isValidPassword($password)) {
        $response["success"] = false;
        $response["password"] = "Weak...";
    } else {
        $response["password"] = "That's strong!";
    }
    // Password Confirm
    if ($password !== $passwordConfirm) {
        $response["success"] = false;
        $response["passwordConfirm"] = "These passwords don't match.";
    } else {
        if ($password !== "")
            $response["passwordConfirm"] = "Nice!";
        else
            $response["passwordConfirm"] = "";
    }
    // Agreement
    if ($agreement == "false") {
        $response["success"] = false;
        $response["agreement"] = "You need to agree to our terms & conditions.";
    } else {
        $response["agreement"] = "Thanks, sir!";
    }

    if (!$response["success"]) {
        $response["message"] = "Failed to sign-up. Please check your input.";
    }

    // RECAPTCHA VERIFICATION
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = array(
        'secret' => '6LcE0pYaAAAAAPCwJQ6ZwBSXqej6vYmjS4eBmRKr',
        'response' => $_POST["recaptchaResponse"]
    );
    $query = http_build_query($data);
    $options = array(
        'http' => array(
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            "Content-Length: " . strlen($query) . "\r\n" . "User-Agent:MyAgent/1.0\r\n",
            'method' => 'POST',
            'content' => $query
        )
    );
    $context  = stream_context_create($options);
    $verify = file_get_contents($url, false, $context);
    $captchaResponse = json_decode($verify);

    $response["score"] = $captchaResponse->score;
    $response["recaptcha"] = print_r($captchaResponse, true);
    if (!$captchaResponse->success or $captchaResponse->score < 0.5) {
        $response["success"] = false;
        $response["message"] = "You were identified as bot.";
    }

    if ($response["success"]) {
        $token = EmailVerificator::gen_token();
        if ($dbManager->addNewUser($username, $email, $password, $token)) {
            $user_id = $dbManager->conn->insert_id;
            EmailVerificator::send_email($username, $email, $token);
            SessionManager::login(new UserData($username, $email, false));
        } else {
            $response["success"] = false;
            $response['message'] = "Database error.";
        }
    }
    echo json_encode($response);
} else if (isset($_POST['username'])) {
    $username = InputValidator::prepare($_POST['username']);

    $valid = InputValidator::isValidUsername($username);
    $unique = $dbManager->isUniqueUsername($username);

    if ($valid) {
        if ($unique) {
            echo "Perfect!";
        } else {
            echo "Username already exists. Try another.";
        }
    } else {
        echo "Invalid username.";
    }
} else if (isset($_POST['email'])) {
    $email = InputValidator::prepare($_POST['email']);

    $valid = InputValidator::isValidEmail($email);
    $unique = $dbManager->isUniqueEmail($email);

    if ($valid) {
        if ($unique) {
            echo "Perfect!";
        } else {
            echo "There's already an account using that email.";
        }
    } else {
        echo "Invalid email.";
    }
} else if (isset($_POST['password']) and isset($_POST['passwordConfirm'])) {
    $password = InputValidator::prepare($_POST['password']);
    $passwordConfirm = InputValidator::prepare($_POST['passwordConfirm']);

    $validPassword = InputValidator::isValidPassword($password);
    $validConfirmPassword = $password === $passwordConfirm;

    $response = array();

    if ($validPassword) {
        $response["password"] = "That's strong!";
    } else {
        $response["password"] = "Weak...";
    }

    if ($validConfirmPassword) {
        $response["passwordConfirm"] = "Nice!";
    } else {
        $response["passwordConfirm"] = "These passwords don't match.";
    }
    echo json_encode($response);
} else if (isset($_POST['agreement'])) {
    $agreement = InputValidator::prepare($_POST['agreement']);

    if ($agreement == "false") {
        echo "You need to agree to our terms & conditions.";
    } else {
        echo "Thanks, sir!";
    }
}
