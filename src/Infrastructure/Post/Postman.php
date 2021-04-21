<?php

declare(strict_types=1);

namespace App\Infrastructure\Post;

use App\Domain\Review\Value\Confirmation;
use Symfony\Component\Mailer\MailerInterface as SymfonyMailer;
use Symfony\Component\Mime\Email;
use Twig\Environment;

final class Postman
{
    private Environment $templateEngine;
    private SymfonyMailer $mailer;

    public function __construct(Environment $templateEngine, SymfonyMailer $mailer)
    {
        $this->templateEngine = $templateEngine;
        $this->mailer = $mailer;
    }

    public function pickUp(Confirmation $confirmation): void
    {
        $text = $this->templateEngine->render('@text', ['confirmation' => $confirmation]);
        $html = $this->templateEngine->render('@html', ['confirmation' => $confirmation]);

        $email = (new Email())->from('me@me.com')
            ->to($confirmation->email)
            ->subject('Thank you - ' . $confirmation->headline)
            ->text($text)
            ->html($html);

        $this->mailer->send($email);
    }
}
