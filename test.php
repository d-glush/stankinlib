<pre>
<?php

use Packages\Encryptor\Encryptor;
use Packages\HttpDataManager\HttpData;
use Packages\HttpDataManager\HttpDataManager;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/core/init.php';


$httpDataManager = new HttpDataManager();
$httpData = new HttpData();
$httpData->collectData();

var_dump($httpData->getGetData());
var_dump($httpData->getPostData());
var_dump($httpData->getHeadersData());
var_dump($httpData->getInputStreamData());

var_dump($_FILES);

