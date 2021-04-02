<?php

class EmailVerificator
{
    public static function gen_token()
    {
        return md5(rand(0, 1000));
    }

    public static function send_email($username, $email, $token)
    {
        $to = $email;
        $subject = 'Welcome to Cuno! | Verify your Email';
        $message = '
<html>
<head>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
<div>
    <div>
        <h1>Welcome to Cuno!</h1>
        <hr>
        <p>
            Hello, ' . $username . '! Verify this email address for your Cuno account by clicking the link below.</p>
        <hr>
        <di>
            <a href="localhost/Php-Test-Login-System/verify.php?username=' . $username . '&token=' . $token . '">Verify Email Adress Now</a>
        </div>
        <hr>
        <p>If you did not request to verify a Cuno account, you can safely ignore this email.</p>
    </div>
</div>
</body>
</html>
        ';

        $headers = 'From:noreply@cuno.org' . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        mail($to, $subject, $message, $headers);
    }
}
