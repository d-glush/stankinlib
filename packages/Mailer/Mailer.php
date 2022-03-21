<?php

namespace Packages\Mailer;

class Mailer
{
    public function mail(string $templateName, string $to, array $params)
    {
        $template = $this->getTemplate($templateName);
        $subject = $template['subject'];
        $content = $template['content'];
        foreach ($params as $key => $param) {
            $content = str_replace('{{'.$key.'}}', $param, $content);
        }
        $headers = 'From: stankinlibrary@gmail.com' . "\r\n";
        $headers .= 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html;' . "\r\n";
        mail(
            $to,
            $subject,
            $content,
            $headers,
        );
    }

    private function getTemplate(string $templateName): array
    {
        return match ($templateName) {
            'resetPasswordConfirmTemplate' => $this->getResetPasswordConfirmTemplate(),
            default => $this->getErrorTemplate(),
        };
    }

    private function getResetPasswordConfirmTemplate(): array
    {
        return [
            'subject' => 'Восстановление пароля на сайте stankinlib.ru',
            'content' =>
'<html>
    <head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Тема страницы</title>
    </head>
    <body>
        <p>Перейдите для восстановления <a href="http://stankinlibrary.ru/front/test_room.html{{getParameter}}">по этой ссылке</a></p>
    </body>
</html>',
        ];
    }

    private function getErrorTemplate(): array
    {
        return [
            'subject' => 'Ошибка на сайте stankinlib.ru при отправке вам почты',
            'content' =>
'<html>
    <head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Тема страницы</title>
    </head>
    <body>
        <p>Обратитесь в техподдержу, для сообщения об ошибке, если в ближайшее время вы не получите корректное сообщение</p>
    </body>
</html>',
        ];
    }
}