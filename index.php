<?php
require_once __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/app/init.php';
use PHPMailer\PHPMailer\PHPMailerAutoload;

$mail = new PHPMailer;
$first_route = explode('?', $_SERVER["REQUEST_URI"]);
$gets = explode('&', $first_route[1]);
foreach ($gets as $get) {
    $get = explode('=', $get);
    $_GET[$get[0]] = $get[1];
}
$routes = array_filter(explode('/', $first_route[0]));
if (SUBFOLDER === true) {
    array_shift($routes);
    $route = $routes;
} else {
    foreach ($routes as $index => $value):
        $route[$index - 1] = $value;
    endforeach;
}
if (!isset($route[0]) && $_SESSION["developerity_userlogin"] == true) {
    $route[0] = "neworder";
    $routeType = 0;
} elseif (!isset($route[0]) && $_SESSION["developerity_userlogin"] == false) {
    $route[0] = "auth";
    $routeType = 1;
} elseif ($route[0] == "auth" && $_SESSION["developerity_userlogin"] == false) {
    $routeType = 1;
} else {
    $routeType = 0;
}
if (!file_exists(controller($route[0]))) {
    $route[0] = "404";
}
$title = $settings["site_title"];
if (route(0) != "admin" && $settings["site_maintenance"] == 1):
    include 'app/views/maintenance.php';
    exit();
endif;
if ($settings["service_list"] == 2):
    $serviceList = 1;
elseif ($settings["service_list"] == 1):
    $serviceList = 2;
endif;
require controller($route[0]);
if ($settings["recaptcha"] == 1) {
    $captcha = false;
} elseif (($settings["recaptcha"] == 2 && (route(1) == "register" || route(1) == "login" || route(0) == "resetpassword")) || $_SESSION["recaptcha"]) {
    $captcha = true;
}
if ($settings["resetpass_page"] == 1) {
    $resetPage = false;
} elseif ($settings["resetpass_page"] == 2) {
    $resetPage = true;
}
if (route(0) == "auth"):
    $active_menu = route(1);
else:
    $active_menu = route(0);
endif;
if (route(0) != "admin" && route(0) != "ajax_data") {
    if (!$templateDir) {
        $templateDir = route($routeType);
    }
    if ($templateDir == "login" || $templateDir == "register"):
        $contentGet = "auth"; else:
        $contentGet = $templateDir;
    endif;
    $content = $conn->prepare("SELECT * FROM pages WHERE page_get=:get ");
    $content->execute(array("get" => $contentGet));
    $content = $content->fetch(PDO::FETCH_ASSOC);
    $content = $content["page_content"];
    echo $twig->render($templateDir . '.twig', array('site' => ['url' => URL, 'favicon' => $settings['favicon'], "logo" => $settings["site_logo"], "site_name" => $settings["site_name"], 'service_speed' => $settings["service_speed"]], 'styleList' => $stylesheet["stylesheets"], 'scriptList' => $stylesheet["scripts"], 'captchaKey' => $settings["recaptcha_key"], 'captcha' => $captcha, 'resetPage' => $resetPage, 'serviceCategory' => $categories, 'categories' => $categories, 'error' => $error, 'errorText' => $errorText, 'success' => $success, "servicesPage" => $serviceList, "resetType" => $resetType, 'successText' => $successText, 'title' => $title, 'user' => $user, 'data' => $_SESSION["data"], 'settings' => $settings, 'search' => $_GET["search"], "active_menu" => $active_menu, 'ticketList' => $ticketList, 'messageList' => $messageList, 'ticketCount' => new_ticket($user['client_id']), 'paymentsList' => $methodList, 'bankPayment' => $bankPayment["method_type"], 'payoneerPayment' => $payoneerPayment["method_type"], 'payoneerPaymentTitle' => $payoneerPaymentExtra['name'], 'payoneerPaymentEmail' => $payoneerPaymentExtra['email'], 'bankList' => $bankList, 'status' => $route[1], 'orders' => $ordersList, 'pagination' => $paginationArr, 'contentText' => $content, 'headerCode' => $settings["custom_header"], 'fastreg' => str_replace('%40','@',htmlspecialchars($_GET['fastreg']??'')), 'footerCode' => $settings["custom_footer"]));
}
if (route(0) != "neworder" && route(0) != "ajax_data" && (route(0) != "admin" && route(1) != "services")):
    unset($_SESSION["data"]);
endif;