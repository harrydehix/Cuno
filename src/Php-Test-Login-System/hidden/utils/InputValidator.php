<?php

class InputValidator
{
    public static function prepare($var)
    {
        return htmlspecialchars(stripslashes(trim($var)));
    }

    public static function isValidUsername($var)
    {
        return !empty($var) and preg_match("/^([a-zA-Z0-9][_.\- ]?){5,200}$/", $var);
    }

    public static function isValidEmail($var)
    {
        return !empty($var) and filter_var($var, FILTER_VALIDATE_EMAIL) and strlen($var) <= 200;
    }

    public static function isValidURL($var)
    {
        return !empty($var) and filter_var($var, FILTER_VALIDATE_URL);
    }

    public static function isValidPassword($var)
    {
        return !empty($var) and preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,200}$/", $var);
    }
}
