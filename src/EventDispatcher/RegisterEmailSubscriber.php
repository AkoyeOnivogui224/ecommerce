<?php

namespace App\EventDispatcher;

use App\Entity\User;
use Psr\Log\LoggerInterface;
use App\Event\RegisterSuccessEvent;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mime\Address;

class RegisterEmailSubscriber implements EventSubscriberInterface
{
    protected $logger;
    protected $mailer;
    protected $security;
    public function __construct(LoggerInterface $logger, MailerInterface $mailer, Security $security)
    {
        $this->logger = $logger;
        $this->mailer = $mailer;
        $this->security = $security;
    }

    public static function getSubscribedEvents()
    {
        return [
            'register.create' => 'sendSuccessEmail'
        ];
    }

    public function sendSuccessEmail(RegisterSuccessEvent $registerSuccessEvent)
    {

        // 3. Ecrire le mail (Nouveau TemplatedEmail)
        $email = new TemplatedEmail();
        $email->from(new Address("contact@mail.com", "Info pour nouvel enregistrement"))
            ->to($registerSuccessEvent->getUser()->getEmail())
            ->text("L'enregistrement pour l'utilisateur" . $registerSuccessEvent->getUser()->getFullnme())
            ->htmlTemplate('register/registerSuccess.html.twig')
            ->context([
                'user' => $registerSuccessEvent->getUser()
            ])
            ->subject("L'enregistrement de l'utilisateur" . $registerSuccessEvent->getUser()->getFullNme());

        //4. Envoyer le mail (MailerInterface)
        $this->mailer->send($email);
    }
}
