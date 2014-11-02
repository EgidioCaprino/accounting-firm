<?php
namespace Application;

use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail;

class MailSender {
    const FROM = 'noreply@accounting-firm.egidiocaprino.it';

    private static $sendMail = null;

    public static function sendMail(array $to, $subject, $message) {
        $subject = trim($subject);
        $message = trim($message);
        if (empty($to) || empty($subject) || empty($message)) {
            throw new \Exception('Invalid mail parameters');
        }
        $mail = new Message();
        $mail->setEncoding('UTF-8');
        $headers = $mail->getHeaders();
        $headers->removeHeader('Content-Type');
        $headers->addHeaderLine('Content-Type', 'text/plain; charset=UTF-8');
        $mail->setFrom(self::FROM);
        if (count($to) === 1) {
            $mail->addTo($to[0]);
        } else {
            $mail->addTo(self::FROM);
            foreach ($to as $address) {
                $mail->addBcc($address);
            }
        }
        $mail->setSubject($subject);
        $mail->setBody($message);
        self::getSendMail()->send($mail);
    }

    private function getSendMail() {
        if (self::$sendMail === null) {
            self::$sendMail = new Sendmail();
        }
        return self::$sendMail;
    }
}