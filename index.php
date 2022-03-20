<?php

use Routes\RootRoute;

include_once __DIR__ . '/core/init.php';

$apiUrl = $_GET['q'];
$apiUrls = explode('/', $apiUrl);

$rootRoute = new RootRoute($apiUrls);
$result = $rootRoute->process();

$result->display();

//function getFormData($method) {
//
//    // GET или POST: данные возвращаем как есть
//    if ($method === 'GET') return $_GET;
//    if ($method === 'POST') return $_POST;
//
//    // PUT, PATCH или DELETE
//    $data = array();
//    $exploded = explode('&', file_get_contents('php://input'));
//
//    foreach($exploded as $pair) {
//        $item = explode('=', $pair);
//        if (count($item) == 2) {
//            $data[urldecode($item[0])] = urldecode($item[1]);
//        }
//    }
//
//    return $data;
//}
//
//var_dump(getFormData('GET'));
//var_dump(getFormData('POST'));
//var_dump($_FILES);
//var_dump(getFormData('d'));
