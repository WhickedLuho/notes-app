<?php
// app/models/Mailer.php
namespace App\Models;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer {
    private $mailer;

    public function __construct(array $config = []) {
        $this->mailer = new PHPMailer(true);

        $this->mailer->isSMTP();
        $this->mailer->Host       = $config['SMTP_HOST'] ?? $_ENV['SMTP_HOST'];
        $this->mailer->SMTPAuth   = true;
        $this->mailer->Username   = $config['SMTP_USER'] ?? $_ENV['SMTP_USER'];
        $this->mailer->Password   = $config['SMTP_PASS'] ?? $_ENV['SMTP_PASS'];
        $this->mailer->SMTPSecure = $config['SMTP_SECURE'] ?? $_ENV['SMTP_SECURE'];
        $this->mailer->Port       = $config['SMTP_PORT'] ?? $_ENV['SMTP_PORT'];
        $this->mailer->CharSet    = 'UTF-8';

        $fromEmail = $config['SMTP_FROM_EMAIL'] ?? $_ENV['SMTP_FROM_EMAIL'];
        $fromName  = $config['SMTP_FROM_NAME']  ?? $_ENV['SMTP_FROM_NAME'];

        $this->mailer->setFrom($fromEmail, $fromName);
    }

    public function send($to, $subject, $body, $altBody = '') {
        try {
            $this->mailer->clearAllRecipients();
            $this->mailer->addAddress($to);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body    = $body;
            $this->mailer->AltBody = $altBody ?: strip_tags($body);

            return $this->mailer->send();
        } catch (Exception $e) {
            error_log("Email error: " . $this->mailer->ErrorInfo);
            // file_put_contents(__DIR__ . '/mail_worker_test.log', date('c') . " $text\n", FILE_APPEND);
            

            return false;
        }
    }
}
