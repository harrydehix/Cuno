<?php

class UserData
{
    public $username;
    public $email;
    public $verified;

    function __construct($username, $email, $verified)
    {
        $this->username = $username;
        $this->email = $email;
        $this->verified = $verified;
    }
}
