<?php

declare(strict_types=1);

namespace Tests\Post;

use App\Domain\Review\Value\Confirmation;
use App\Infrastructure\Mailer\Mailer;
use App\Infrastructure\Post\Postman;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

final class PostmanTest extends TestCase
{
    use ProphecyTrait;

    public function testShouldPickUpConfirmation(): void
    {
        $confirmation = new Confirmation('you@you.com', 'headline', 'message');

        $expectedEmail = (new Email())->from('me@me.com')
            ->to('you@you.com')
            ->subject('Thank you - headline')
            ->text('Rendered with @text and email you@you.com.')
            ->html('Rendered with @html and email you@you.com.');

        $mailer = $this->prophesize(MailerInterface::class);
        $mailer->send($expectedEmail)->shouldBeCalled();

        $postman = new Postman(new TemplateEngineStub(), $mailer->reveal());
        $postman->pickUp($confirmation);
    }
}

final class TemplateEngineStub extends Environment
{
    public function __construct()
    {
    }

    public function render($template, array $context = []): string
    {
        /** @var Confirmation $confirmation */
        $confirmation = $context['confirmation'];

        return \sprintf('Rendered with %s and email %s.', $template, $confirmation->email);
    }
}
