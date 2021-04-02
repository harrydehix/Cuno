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
        function validateUsername(input) {

            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("username-response").innerHTML = this.responseText;
                }
            };
            xhttp.open("POST", "hidden/hiddenSignUp.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("username=" + input);
        }

        function validateEmail(input) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("email-response").innerHTML = this.responseText;
                }
            };
            xhttp.open("POST", "hidden/hiddenSignUp.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("email=" + input);
        }

        var passwordConfirmTyped = false;

        function validatePasswords(source) {
            var updateConfirm = source.id == "passwordConfirm" || passwordConfirmTyped;
            if (updateConfirm) passwordConfirmTyped = true;

            var password = document.getElementById("password").value;
            var passwordConfirm = document.getElementById("passwordConfirm").value;
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var response = JSON.parse(this.responseText);
                    document.getElementById("password-response").innerHTML = response.password;
                    if (updateConfirm) document.getElementById("passwordConfirm-response").innerHTML = response.passwordConfirm;
                }
            };
            xhttp.open("POST", "hidden/hiddenSignUp.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("password=" + password + "&passwordConfirm=" + passwordConfirm);
        }

        function validateForm(event) {
            event.preventDefault();

            grecaptcha
                .execute("6LcE0pYaAAAAAOclJBH_xqHK8c201QeRWbZcAmcJ", {
                    action: "submit",
                })
                .then(function(token) {
                    var recaptchaResponse = document.getElementById("recaptchaResponse");
                    recaptchaResponse.value = token;

                    var xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            var response = JSON.parse(this.responseText);
                            if (response.success) {
                                document.location.href = "index.php", true;
                            } else {
                                document.getElementById("submit-response").innerHTML = response.message;
                                document.getElementById("username-response").innerHTML = response.username;
                                document.getElementById("email-response").innerHTML = response.email;
                                document.getElementById("password-response").innerHTML = response.password;
                                document.getElementById("passwordConfirm-response").innerHTML = response.passwordConfirm;
                                document.getElementById("agreement-response").innerHTML = response.agreement;
                            }
                        }
                    };
                    xhttp.open("POST", "hidden/hiddenSignUp.php", true);
                    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

                    username = document.getElementById("username").value;
                    email = document.getElementById("email").value;
                    password = document.getElementById("password").value;
                    passwordConfirm = document.getElementById("passwordConfirm").value;
                    agreement = document.getElementById("agreement").checked;
                    recaptchaResponse = document.getElementById("recaptchaResponse").value;

                    xhttp.send("username=" + username +
                        "&email=" + email +
                        "&password=" + password +
                        "&passwordConfirm=" + passwordConfirm +
                        "&agreement=" + agreement +
                        "&recaptchaResponse=" + recaptchaResponse +
                        "&submit=true");
                });
        }

        function validateAgreement(checkbox) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("agreement-response").innerHTML = this.responseText;
                }
            }
            xhttp.open("POST", "hidden/hiddenSignUp.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("agreement=" + checkbox.checked);
        }
    </script>
    <script src="https://www.google.com/recaptcha/api.js?render=6LcE0pYaAAAAAOclJBH_xqHK8c201QeRWbZcAmcJ"></script>
</head>

<body>

    <form action="mail.php" method="post" onsubmit="validateForm(event)">
        <input id="username" type="text" name="username" placeholder="Username" onkeyup="validateUsername(this.value)"><br>
        <small id="username-response"></small><br>

        <input id="email" type="text" name="email" placeholder="E-Mail" onkeyup="validateEmail(this.value)"><br>
        <small id="email-response"></small><br>

        <input id="password" type="password" name="password" placeholder="Password" onkeyup="validatePasswords(this)"><br>
        <small id="password-response"></small><br>

        <input id="passwordConfirm" type="password" name="passwordConfirm" placeholder="Confirm Password" onkeyup="validatePasswords(this)"><br>
        <small id="passwordConfirm-response"></small><br>

        <input id="agreement" type="checkbox" name="agreement" onclick="validateAgreement(this)">
        <label>I agree to terms & conditions.</label><br>
        <small id="agreement-response"></small><br>

        <input type="hidden" name="recaptchaResponse" id="recaptchaResponse">

        <button id="submit" type="submit" name="submit">Sign Up</button><br>
        <small id="submit-response"></small>
        <p>Already a member? <a href="login.php">Login.</a></p>
    </form>

</body>

</html>