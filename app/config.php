<?php
define('PATH', realpath('.'));
define('THEME', 'theme');
define('UMH', false); // never change this
define('SUBFOLDER', false); // set this to true if you are using a subfolder. for ex: domain.com/subfolder
define('URL', 'https://yourdomain.com'); // do not add a slash (/) to the end
define('STYLESHEETS_URL', '//yourdomain.com'); // do not add a slash (/) to the end
define('KEY', '123123123132-123123123123-123123123123-123123131'); // codecanyon purchase code

ini_set('display_errors', 0);
error_reporting(E_ALL & ~E_NOTICE);

date_default_timezone_set('Europe/Istanbul'); // your timezone here, if you care about the unnecessary details

######## UNSUPPORTED CURRENCIES FROM AUTO CONVERTER API ########
define('NGN_', '387.50'); // Nigerian naira
######## UNSUPPORTED CURRENCIES FROM AUTO CONVERTER API ########

return [
    'db' => [
        'name'    =>  'smm', // Database name
        'host'    =>  'localhost',
        'user'    =>  'toor', // Database username
        'pass'    =>  'toor', // Database Password
        'charset' =>  'utf8mb4' // do not touch this!
    ]
];