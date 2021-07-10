<?php
$title.= " Reset Password";
if ($_SESSION["developerity_userlogin"] == 1 || $user["client_type"] == 1 || $settings["resetpass_page"] == 1) {
    Header("Location:" . site_url());
}
$resetType = array();
if ($settings["resetpass_sms"] == 2):
    $resetType[] = ["type" => "sms", "name" => "Send code to my phone"];
endif;
if ($settings["resetpass_email"] == 2):
    $resetType[] = ["type" => "email", "name" => "Send code to my Email"];
endif;
if ($_POST):
    $captcha = $_POST['g-recaptcha-response'];
    $googlesecret = $settings["recaptcha_secret"];
    $captcha_control = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$googlesecret&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']);
    $captcha_control = json_decode($captcha_control);
    $user = $_POST["user"];
    $type = $_POST["type"];
    $row = $conn->prepare("SELECT * FROM clients WHERE username=:username || telephone=:tel ");
    $row->execute(array("username" => $user, "tel" => $user));
    if (empty($user)):
        $error = 1;
        $errorText = "User data can not be empty.";
    elseif (!$row->rowCount()):
        $error = 1;
        $errorText = "Data does not match.";
    elseif ($settings["recaptcha"] == 2 && $captcha_control->success == false):
        $error = 1;
        $errorText = "Please verify that you are not a robot.";
    else:
        $row = $row->fetch(PDO::FETCH_ASSOC);
        $pass = rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);
        $update = $conn->prepare("UPDATE clients SET password=:pass WHERE client_id=:id ");
        $update->execute(array("id" => $row["client_id"], "pass" => md5(sha1(md5($pass)))));
        if ($type == "sms"):
            $send = SMSUser($row["telephone"], "Your new password for your account: " . $pass);
        endif;
        if ($type == "email"):
            $send = sendMail(["subject" => "New password", "body" => "Your new password for your account: " . $pass, "mail" => $row["email"]]);
        endif;
        if ($send):
            $success = 1;
            $successText = "Email sent successfuly, please check your inbox or spam. You are being redirected.";
            echo '<script>setInterval(function(){window.location="' . site_url('/') . '"},4000)</script>';
        else:
            $error = 1;
            $errorText = "Error.";
        endif;
    endif;
endif;
