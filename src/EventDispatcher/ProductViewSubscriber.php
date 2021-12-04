<?php

namespace App\EventDispatcher;

use App\Event\ProductView;
use App\Event\ProductViewEvent;
use App\Event\PurchaseSuccessEvent;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class ProductViewSubscriber implements EventSubscriberInterface
{
    protected $logger;
    protected $mailer;
    public function __construct(LoggerInterface $logger, MailerInterface $mailer)
    {
        $this->logger = $logger;
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return [
            'product.view' => 'sendEmailProductView'
        ];
    }

    public function sendEmailProductView(ProductViewEvent $productView)
    {
        // $email = new TemplatedEmail();
        // $email->from(new Address("contact@mail.com", "Info de la boutique"))
        //     ->to("admin@mail.com")
        //     ->text("Un visiteur est en train de voir la page du produit n°" . $productView->getProduct()->getId())
        //     ->htmlTemplate('emails/product_view.html.twig')
        //     ->context([
        //         'product' => $productView->getProduct()
        //     ])
        //     ->subject("Visite du produit n°" . $productView->getProduct()->getId());

        // $this->mailer->send($email);

        $this->logger->info("Email envoyé à l'admin pour le produit n°" . $productView->getProduct()->getId());
    }
}
