<?php

session_start();

require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

require __DIR__ . '/core/database.php';
require __DIR__ . '/core/function.php';
require __DIR__ . '/router.php';
require __DIR__ . '/routes.php';