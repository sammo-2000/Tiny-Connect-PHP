<?php

require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

ini_set('cookie.use_only_cookies', 1);
ini_set('cookie.use_strict_mode', 1);

session_set_cookie_params([
    'lifetime' => 7 * 24 * 60 * 60,
    'domain' => $_ENV['DOMAIN'],
    'path' => '/',
    'secure' => true,
    'httponly' => true
]);

session_start();

if (!isset($_SESSION['last_regeneration'])) {
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
} else {
    $interval = 60 * 30;
    if (time() - $_SESSION['last_regeneration'] >= $interval) {
        session_regenerate_id(true);
        $_SESSION['last_regeneration'] = time();
    }
}

// error_reporting(E_ALL);
// ini_set('display_errors', 0);

// function customerErrorHandler($errNo, $errStr, $errFile, $errLine)
// {
//     $message = "Error: [$errNo] $errStr - $errFile - $errLine";
//     error_log($message . PHP_EOL, 3, "error_log.txt");
// }

// set_error_handler('customerErrorHandler');

require __DIR__ . '/core/Dbh.php';
require __DIR__ . '/core/function.php';
require __DIR__ . '/router.php';
require __DIR__ . '/routes.php';