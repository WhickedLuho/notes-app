<?php
namespace App\Controllers;

use App\Models\Mailer;

class NotificationController extends BaseController
{

    public function loginUserNotify() {
        $postData = $this->request->post();

        $mailer = new Mailer();
        $subject = 'Notes App - Login Notification';

        $this->smarty->assign([
            'postData' => $postData,
        ]);
        $body = $this->smarty->fetch('mail/login_notify.tpl');

        $mailer->send($_ENV['ADMIN_MAIL'], $subject, $body);
        header('Location: /');
    }
}