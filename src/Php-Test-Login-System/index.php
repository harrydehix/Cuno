<?php
require_once 'hidden/utils/UserData.php';
session_start();

if (!isset($_SESSION['userdata'])) {
    header("location: signup.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script>
        function logout() {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.location.href = "signup.php", true;
                }
            }
            xhttp.open("POST", "hidden/hiddenLogOut.php", true);
            xhttp.send();
        }

        function checkVerification() {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    console.log(this.responseText);
                    if (this.responseText === "true") {
                        document.getElementById("verification-needed").remove();
                    } else {
                        document.getElementById("verification-response").innerHTML = "No, you are not!";
                    }
                }
            }
            xhttp.open("POST", "hidden/checkVerification.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            var username = <?php echo '"' . $_SESSION['userdata']->username . '"' ?>;
            xhttp.send("username=" + username);
        }
    </script>
</head>

<body>
    <h1>Welcome, <?php echo $_SESSION['userdata']->username ?>!</h1>
    <?php if (!$_SESSION['userdata']->verified) : ?>
        <div id="verification-needed">
            <p>You are not verified yet. We sent an email to <?php echo $_SESSION['userdata']->email ?>. Click the verification link there to verify yourself.</p>
            <button onclick="checkVerification()">I am verified!</button>
            <small id="verification-response"></small>
        </div>
    <?php endif; ?>
    <button onclick="logout()">Logout</button>
</body>

</html>