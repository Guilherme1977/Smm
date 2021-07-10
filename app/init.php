<?php
session_start();
ob_start();
$config = require_once __DIR__ . '/config.php';

$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE']??'en',0,2);

$langdir = scandir(__DIR__.'/lang');
$langdir = str_replace(array('.php','.','..'), '', $langdir);

foreach ($langdir as $value) {
    $langinc = True;
    if ($lang == $value) {
        require_once __DIR__ . '/lang/'. $lang .'.php';
        $langinc = False;
        break;
    }
}

if ($langinc) {
    require_once __DIR__ . '/lang/en.php';
}

try {
    $conn = new PDO("mysql:host=" . $config["db"]["host"] . ";dbname=" . $config["db"]["name"] . ";charset=" . $config["db"]["charset"] . ";", $config["db"]["user"], $config["db"]["pass"]);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e) {
    die($e->getMessage());
}
if ($_COOKIE["u_id"] && $_COOKIE["u_login"] && $_COOKIE["u_password"]):
    $row = $conn->prepare("SELECT * FROM clients WHERE client_id=:id");
    $row->execute(array("id" => $_COOKIE["u_id"]));
    $row = $row->fetch(PDO::FETCH_ASSOC);
    $access = json_decode($row["access"], true);
    $password = $row["password"];
    if (@$_COOKIE["u_password"] == $password):
        $_SESSION["developerity_userlogin"] = 1;
        $_SESSION["developerity_userid"] = $row["client_id"];
        $_SESSION["developerity_userpass"] = $row["password"];
        if ($access["admin_access"]):
            $_SESSION["developerity_adminlogin"] = 1;
        endif;
    else:
        unset($_SESSION["developerity_userlogin"]);
        unset($_SESSION["developerity_userid"]);
        unset($_SESSION["developerity_userpass"]);
        unset($_SESSION["developerity_adminlogin"]);
        unset($_SESSION);
        setcookie("u_id", $row["client_id"], time() - (60 * 60 * 24 * 7), '/', null, null, true);
        setcookie("u_password", $row["password"], time() - (60 * 60 * 24 * 7), '/', null, null, true);
        setcookie("u_login", 'ok', time() - (60 * 60 * 24 * 7), '/', null, null, true);
        session_destroy();
    endif;
endif;
$loader = new Twig_Loader_Filesystem(__DIR__ . '/views/' . THEME);
$twig = new Twig_Environment($loader, ['autoescape' => false]);
$settings = $conn->prepare("SELECT * FROM settings WHERE id=:id");
$settings->execute(array("id" => 1));
$settings = $settings->fetch(PDO::FETCH_ASSOC);
$user = $conn->prepare("SELECT * FROM clients WHERE client_id=:id");
$user->execute(array("id" => $_SESSION["developerity_userid"]));
$user = $user->fetch(PDO::FETCH_ASSOC);
$user['auth'] = $_SESSION["developerity_userlogin"];
$user["access"] = json_decode($user["access"], true);
foreach (glob(__DIR__ . '/helper/*.php') as $helper) {
    if ($helper == __DIR__ . '/helper/system.php') {
        continue;
    }
    require $helper;
}
foreach (glob(__DIR__ . '/classes/*.php') as $class) {
    require $class;
}