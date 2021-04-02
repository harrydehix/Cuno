<?php

class DBManager
{
    public $conn = null;
    public $host = "";
    public $user = "";
    public $password = "";
    public $name = "";

    function __construct($host, $user, $password, $name, $connect)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->name = $name;
        if ($connect) $this->connect();
    }

    public function connect()
    {
        $this->conn = new mysqli($this->host, $this->user, $this->password, $this->name);
        if ($this->conn->connect_error) {
            die("Database error:" . $this->conn->connect_error);
        }
    }

    public function isUniqueUsername($username)
    {
        $query = "SELECT * FROM users WHERE username=? LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $username);
        $stmt->execute();

        $res = $stmt->get_result();
        $userCount = $res->num_rows;

        $stmt->close();
        return $userCount <= 0;
    }

    public function isUniqueEmail($email)
    {
        $query = "SELECT * FROM users WHERE email=? LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $email);
        $stmt->execute();

        $res = $stmt->get_result();
        $userCount = $res->num_rows;

        $stmt->close();
        return $userCount <= 0;
    }

    public function addNewUser($username, $email, $password, $token)
    {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $verified = 0;

        $sql = "INSERT INTO users (username, email, verified, token, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ssiss', $username, $email, $verified, $token, $password);

        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    public function isValidUser($username, $password)
    {
        $sql = "SELECT password FROM users WHERE username=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $username);

        $success = $stmt->execute();
        if ($success) {
            $res = $stmt->get_result();

            $hashedPasswordInDB = "";
            while ($row = $res->fetch_object()) {
                $hashedPasswordInDB = $row->password;
            }
            if (password_verify($password, $hashedPasswordInDB)) {
                $stmt->close();
                return true;
            } else {
                $stmt->close();
                return false;
            }
        } else {
            $stmt->close();
            return false;
        }
    }

    public function getUsersEmail($username)
    {
        $sql = "SELECT email FROM users WHERE username=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $username);

        $success = $stmt->execute();
        if ($success) {
            $res = $stmt->get_result();

            $email = "";
            while ($row = $res->fetch_object()) {
                $email = $row->email;
            }
            $stmt->close();
            return $email;
        }
        $stmt->close();
        return "";
    }

    public function isVerified($username)
    {
        $sql = "SELECT verified FROM users WHERE username=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $username);

        $success = $stmt->execute();
        if ($success) {
            $res = $stmt->get_result();

            $verified = 0;
            while ($row = $res->fetch_object()) {
                $verified = $row->verified;
            }
            $stmt->close();
            return $verified === 1;
        }
        $stmt->close();
        return false;
    }

    public function verifyUser($username, $token)
    {
        $sql = "SELECT token FROM users WHERE username=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $username);

        $success = $stmt->execute();
        if ($success) {
            $res = $stmt->get_result();

            $tokenInDatabase = "";
            while ($row = $res->fetch_object()) {
                $tokenInDatabase = $row->token;
            }
            if ($token === $tokenInDatabase) {
                $stmt->close();
                $sql = "UPDATE users SET verified=? WHERE username=?";
                $stmt = $this->conn->prepare($sql);
                $verified = 1;
                $stmt->bind_param('is', $verified, $username);

                $success = $stmt->execute();
                $stmt->close();
                return $success;
            }
        }
        $stmt->close();
        return false;
    }
}
