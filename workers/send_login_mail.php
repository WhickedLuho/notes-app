<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Models\Mailer;
use Exception;

date_default_timezone_set('UTC');

try {
    $json = $argv[1] ?? '{}';
    $data = json_decode($json, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON input: ' . json_last_error_msg());
    }

    if (!isset($data['to'], $data['subject'], $data['body'])) {
        throw new Exception('Missing email data');
    }

    $mailer = new Mailer($data['config'] ?? []);
    $sent = $mailer->send($data['to'], $data['subject'], $data['body']);
    if (!$sent) {
        throw new Exception('Mailer failed to send email');
    }

    file_put_contents(__DIR__ . '/mail_worker_success.log', (new DateTime())->format(DateTime::ATOM) . " Mail sent to {$data['to']}\n", FILE_APPEND);
} catch (Throwable $e) {
    file_put_contents(__DIR__ . '/mail_worker_error.log', (new DateTime())->format(DateTime::ATOM) . " ERROR: " . $e->getMessage() . "\n", FILE_APPEND);
    exit(1);
}
