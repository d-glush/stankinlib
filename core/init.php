<?php

session_start();

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Credentials: true');
header('Content-type: application/json; charset=UTF-8');

require_once __DIR__ . '/../vendor/autoload.php';
