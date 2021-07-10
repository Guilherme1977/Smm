<?php
if ($user["admin_type"] != 2):
    header("Location:" . site_url());
    exit();
endif;
if ($_SESSION["client"]["data"]):
    $data = $_SESSION["client"]["data"];
    foreach ($data as $key => $value) {
        $$key = $value;
    }
    unset($_SESSION["client"]);
endif;
if (route(2) == 'now'):
    $d = str_replace(array('http://','https://'), '', URL);
    $url  = 'https://developerity.com/update?key='.KEY.'&d='.$d;
    $local = __DIR__.'/../../Magduriyet.zip';
    $zipResource = fopen($local, "w");

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FAILONERROR, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER,true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 100);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
    curl_setopt($ch, CURLOPT_FILE, $zipResource);
    $data = curl_exec($ch);
    curl_close($ch);

    $zip = new ZipArchive;
    $res = $zip->open(__DIR__.'/../../Magduriyet.zip');

    if ($res === true) {
        $zip->extractTo(__DIR__.'/../../');
        $zip->close();
        sleep(15);
        unlink(__DIR__.'/../../Magduriyet.zip');
        header("Location:" . site_url());
    }
endif;
require admin_view('update');