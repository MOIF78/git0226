<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class TestMailController extends AbstractController
{
    #[Route('/test-mail', name: 'test_mail')]
    public function index(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('noreply@parfum.fr')
            ->to('ton-adresse@email.fr')
            ->subject('Test Mailtrap')
            ->text('Ceci est un email de test.');

        $mailer->send($email);

        return new Response('Email envoyé');
    }
}