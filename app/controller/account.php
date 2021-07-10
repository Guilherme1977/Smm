<?php
$title.= " Account";
if ($_SESSION["developerity_userlogin"] != 1 || $user["client_type"] == 1) {
    Header("Location:" . site_url('logout'));
}
if (route(1) == "newapikey") {
    $conn->beginTransaction();
    $insert = $conn->prepare("INSERT INTO client_report SET client_id=:c_id, action=:action, report_ip=:ip, report_date=:date ");
    $insert = $insert->execute(array("c_id" => $user["client_id"], "action" => "API Key has been changed.", "ip" => GetIP(), "date" => date("Y-m-d H:i:s")));
    $apikey = CreateApiKey(["email" => $user["email"], "username" => $user["username"]]);
    $update = $conn->prepare("UPDATE clients SET apikey=:key WHERE client_id=:id ");
    $update = $update->execute(array("id" => $user["client_id"], "key" => $apikey));
    if ($update && $insert):
        $conn->commit();
    else:
        $conn->rollBack();
    endif;
    header('Location:' . site_url('account'));
} elseif (route(0) == "account" && $_POST && isset($_POST["current_password"])) {
    if(UMH != true){
        $pass = htmlspecialchars($_POST["current_password"]);
        $new_pass = htmlspecialchars($_POST["password"]);
        $new_again = htmlspecialchars($_POST["confirm_password"]);
        if (!userdata_check('password', md5(sha1(md5($pass))))) {
            $error = 1;
            $errorText = "Current password is wrong.";
        } elseif (strlen($new_pass) < 8) {
            $error = 1;
            $errorText = "New password should be at least 8 characters.";
        } elseif ($new_pass != $new_again) {
            $error = 1;
            $errorText = "Password doesn't match.";
        } else {
            $conn->beginTransaction();
            $insert = $conn->prepare("INSERT INTO client_report SET client_id=:c_id, action=:action, report_ip=:ip, report_date=:date ");
            $insert = $insert->execute(array("c_id" => $user["client_id"], "action" => "User password changed..", "ip" => GetIP(), "date" => date("Y-m-d H:i:s")));
            $update = $conn->prepare("UPDATE clients SET password=:pass WHERE client_id=:id ");
            $update = $update->execute(array("id" => $user["client_id"], "pass" => md5(sha1(md5($new_pass)))));
            if ($update && $insert):
                $_SESSION["developerity_userpass"] = md5(sha1(md5($new_pass)));
                setcookie("u_password", md5(sha1(md5($new_pass))), time() + (60 * 60 * 24 * 7), '/', null, null, true);
                $conn->commit();
                $success = 1;
                $successText = "Password is updated.";
            else:
                $conn->rollBack();
                $error = 1;
                $errorText = "Somethings went wrong..";
            endif;
        }
    }else{
        $error = 1;
        $errorText = "Sorry, unfortunately Demo mode is active.";
    }
} elseif (route(0) == "account" && isset($_POST["timezone"])) {
    $timezone = htmlspecialchars($_POST["timezone"]);
    $conn->beginTransaction();
    $update = $conn->prepare("UPDATE clients SET timezone=:timezone WHERE client_id=:id ");
    $update = $update->execute(array("id" => $user["client_id"], "timezone" => $timezone));
    if ($update):
        $conn->commit();
        $success = 1;
        $successText = 'Timezone is updated, redirecting now.. <script>setInterval(function(){window.location="' . site_url('account') . '"},2000)</script>';
    else:
        $conn->rollBack();
        $error = 1;
        $errorText = "Somethings went wrong..";
    endif;
}