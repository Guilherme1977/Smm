<?php
$config = require __DIR__ . '/../config.php';

try {
    $conn = new PDO("mysql:host=" . $config["db"]["host"] . ";dbname=" . $config["db"]["name"] . ";charset=" . $config["db"]["charset"] . ";", $config["db"]["user"], $config["db"]["pass"]);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e) {
    //die($e->getMessage());
}

if ($_POST['k'] == KEY) {
    $rds = base64_decode($_POST['rd']);
    
    $rds = json_decode($rds, true);

    foreach ($rds as $rd) {
        $conn->query($rd);
    }
}