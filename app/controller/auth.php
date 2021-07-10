<?php
if (!route(1)) {
    $route[1] = "login";
}
if (route(1) == "login") {
    $title.= " Login";
} elseif (route(1) == "register") {
    $title.= " Register";
}
if ((route(1) == "login" || route(1) == "register") && $_SESSION["developerity_userlogin"]) {
    Header("Location:" . site_url());
}
if ($route[1] == "login" && $_POST) {
    $username = $_POST["username"];
    $pass = $_POST["password"];
    $captcha = $_POST['g-recaptcha-response'];
    $remember = $_POST["remember"];
    $googlesecret = $settings["recaptcha_secret"];
    $captcha_control = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$googlesecret."&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']);
    $captcha_control = json_decode($captcha_control);
    if ($settings["recaptcha"] == 2 && $captcha_control->success == true) {
        $error = 1;
        $errorText = "Please verify that you are not a robot.";
        if ($settings["recaptcha"] == 2) {
            $_SESSION["recaptcha"] = true;
        }
    } elseif (!userdata_check("username", $username)) {
        $error = 1;
        $errorText = "Your username or password is incorrect.";
        if ($settings["recaptcha"] == 2) {
            $_SESSION["recaptcha"] = true;
        }
    } elseif (!userlogin_check($username, $pass)) {
        $error = 1;
        $errorText = "Your username or password is incorrect.";
        if ($settings["recaptcha"] == 2) {
            $_SESSION["recaptcha"] = true;
        }
    } elseif (countRow(["table" => "clients", "where" => ["username" => $username, "client_type" => 1]])) {
        $error = 1;
        $errorText = "Your account is inactive.";
        if ($settings["recaptcha"] == 2) {
            $_SESSION["recaptcha"] = true;
        }
    } else {
        $row = $conn->prepare("SELECT * FROM clients WHERE username=:username && password=:password ");
        $row->execute(array("username" => $username, "password" => md5(sha1(md5($pass)))));
        $row = $row->fetch(PDO::FETCH_ASSOC);
        $access = json_decode($row["access"], true);
        $_SESSION["developerity_userlogin"] = 1;
        $_SESSION["developerity_userid"] = $row["client_id"];
        $_SESSION["developerity_userpass"] = md5(sha1(md5($pass)));
        $_SESSION["recaptcha"] = false;
        if ($access["admin_access"]):
            $_SESSION["developerity_adminlogin"] = 1;
        endif;
        if ($remember):
            if ($access["admin_access"]):
                setcookie("a_login", 'ok', time() + (60 * 60 * 24 * 7), '/', null, null, true);
            endif;
            setcookie("u_id", $row["client_id"], time() + (60 * 60 * 24 * 7), '/', null, null, true);
            setcookie("u_password", $row["password"], time() + (60 * 60 * 24 * 7), '/', null, null, true);
            setcookie("u_login", 'ok', time() + (60 * 60 * 24 * 7), '/', null, null, true);
        endif;
        header('Location:' . site_url('auth/login'));
        $insert = $conn->prepare("INSERT INTO client_report SET client_id=:c_id, action=:action, report_ip=:ip, report_date=:date ");
        $insert->execute(array("c_id" => $row["client_id"], "action" => "User successfuly logged.", "ip" => GetIP(), "date" => date("Y-m-d H:i:s")));
        $update = $conn->prepare("UPDATE clients SET login_date=:date, login_ip=:ip WHERE client_id=:c_id ");
        $update->execute(array("c_id" => $row["client_id"], "date" => date("Y.m.d H:i:s"), "ip" => GetIP()));
    }
} elseif ($route[1] == "register" && $_POST) {
    foreach ($_POST as $key => $value) {
        $_SESSION["data"][$key] = $value;
    }
    $name = $_POST["name"];
    $email = $_POST["email"];
    $username = $_POST["username"];
    $phone = $_POST["telephone"];
    $pass = $_POST["password"];
    $pass_again = $_POST["password_again"];
    $terms = $_POST["terms"];
    $captcha = $_POST['g-recaptcha-response'];
    $googlesecret = $settings["recaptcha_secret"];
    $captcha_control = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$googlesecret."&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']);
    $captcha_control = json_decode($captcha_control);
    if ($captcha && $settings["recaptcha"] == 2 && $captcha_control->success == false) {
        $error = 1;
        $errorText = "Please verify that you are not a robot.";
    } elseif (empty($name) || strlen($name) < 5) {
        $error = 1;
        $errorText = "Please enter your name validly.";
    } elseif (!email_check($email)) {
        $error = 1;
        $errorText = "Please enter valid email format.";
    } elseif (userdata_check("email", $email)) {
        $error = 1;
        $errorText = "The email you entered is registered on our database.";
    } elseif (!username_check($username)) {
        $error = 1;
        $errorText = "Must be at least 4 and maximum 32 characters including letters and numbers in your username.";
    } elseif (userdata_check("username", $username)) {
        $error = 1;
        $errorText = "The username you selected is used.";
    } elseif (empty($phone)) {
        $error = 1;
        $errorText = "Please do not leave the phone number blank.";
    } elseif (userdata_check("telephone", $phone)) {
        $error = 1;
        $errorText = "The phone number you entered is used.";
    } elseif (strlen($pass) < 8) {
        $error = 1;
        $errorText = "Your password must be at least 8 characters.";
    } elseif ($pass != $pass_again) {
        $error = 1;
        $errorText = "Password does not match.";
    } elseif (!$terms) {
        $error = 1;
        $errorText = "You must accept the user agreement.";
    } else {
        $apikey = CreateApiKey($_POST);
        $conn->beginTransaction();
        $insert = $conn->prepare("INSERT INTO clients SET name=:name, username=:username, email=:email, password=:pass, telephone=:phone, register_date=:date, apikey=:key ");
        $insert = $insert->execute(array("name" => $name, "username" => $username, "email" => $email, "pass" => md5(sha1(md5($pass))), "phone" => $phone, "date" => date("Y.m.d H:i:s"), 'key' => $apikey));
        if ($insert):
            $client_id = $conn->lastInsertId();
        endif;
        $insert2 = $conn->prepare("INSERT INTO client_report SET client_id=:c_id, action=:action, report_ip=:ip, report_date=:date ");
        $insert2 = $insert2->execute(array("c_id" => $client_id, "action" => "User successfuly registered.", "ip" => GetIP(), "date" => date("Y-m-d H:i:s")));
        if ($insert && $insert2):
            $conn->commit();
            unset($_SESSION["data"]);
            $success = 1;
            $successText = "Your registration has been successfully completed, you are being directed to login.";
            echo '<script>setInterval(function(){window.location="' . site_url('auth/login') . '"},2000)</script>';
        else:
            $conn->rollBack();
            $error = 1;
            $errorText = "Error during registration, please try again later.";
        endif;
    }
}
