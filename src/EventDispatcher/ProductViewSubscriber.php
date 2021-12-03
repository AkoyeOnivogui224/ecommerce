<?php

namespace App\EventDispatcher;

use App\Event\ProductView;
use App\Event\ProductViewEvent;
use App\Event\PurchaseSuccessEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductViewSubscriber implements EventSubscriberInterface
{
    protected $logger;
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return [
            'product.view' => 'sendEmailProductView'
        ];
    }

    public function sendEmailProductView(ProductViewEvent $productView)
    {
        $this->logger->info("Email envoyé à l'admin pour le produit n°" . $productView->getProduct()->getId());
    }
}
