<?php

require_once 'UserData.php';

class SessionManager
{
    public static function login($userdata)
    {
        session_start();
        $_SESSION['userdata'] = $userdata;
    }

    public static function logout()
    {
        session_start();
        session_unset();
        session_destroy();
    }
}
