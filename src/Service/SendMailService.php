<?php
namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class SendMailService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send(
        string $to,
        string $subject,
        array $context
    ) : void
    {
        // On crée le mail
        $email = (new TemplatedEmail())
            ->from('houssemhassanii@gmail.com')
            ->to($to)
            ->subject($subject)
            ->context($context);
        // On envoie le mail
        $this->mailer->send($email);
    }
}