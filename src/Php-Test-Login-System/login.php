<?php
require_once 'hidden/utils/UserData.php';
session_start();

if (isset($_SESSION['userdata'])) {
    header("location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AJAX Form</title>
    <script>
        function validateForm(event) {
            event.preventDefault();

            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var response = JSON.parse(this.responseText);
                    if (response.success) {
                        document.location.href = "index.php", true;
                    } else {
                        document.getElementById("submit-response").innerHTML = response.message;
                    }
                }
            };
            xhttp.open("POST", "hidden/hiddenLogIn.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            username = document.getElementById("username").value;
            password = document.getElementById("password").value;

            xhttp.send("username=" + username + "&password=" + password + "&submit=true");
        }
    </script>
</head>

<body>

    <form action="mail.php" method="post" onsubmit="validateForm(event)">
        <input id="username" type="text" name="username" placeholder="Username"><br>
        <input id="password" type="password" name="password" placeholder="Password"><br>

        <button id="submit" type="submit" name="submit">Log In</button><br>
        <small id="submit-response"></small>
        <p>Not yet a member? <a href="signup.php">Sign Up.</a></p>
    </form>

</body>

</html>