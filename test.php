<pre>
<?php

use Packages\Encryptor\Encryptor;

require_once __DIR__ . '/vendor/autoload.php';


//
//$template = [
//    'subject' => 'Восстановление пароля на сайте stankinlib.ru',
//    'content' =>
//        '<html>
//    <head>
//   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
//        <title>Тема страницы</title>
//    </head>
//    <body>
//        <p>Перейдите для восстановления <a href="http://stankinlibrary.ru/front/test_room.html{{getParameter}}">по этой ссылке</a></p>
//    </body>
//</html>',
//];
//$params = ['getParameter' => 'asdasd'];
//$subject = $template['subject'];
//$content = $template['content'];
//foreach ($params as $key => $param) {
//    var_dump('{{'.$key.'}}');
//    var_dump(strpos($content, '{{'.$key.'}}'));
//    $content = str_replace('{{'.$key.'}}', $param, $content);
//}
//
//var_dump($content);