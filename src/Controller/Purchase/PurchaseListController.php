<?php

namespace App\Controller\Purchase;

use Twig\Environment;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PurchaseListController extends AbstractController
{

    /**
     * @Route("/purchases", name="purchase_index")
     * @IsGranted("ROLE_USER", message="Vous devez être connecté pour accéder à vos commandes")
     */
    public function index()
    {
        //1. Nous rassurer que la personne est bien connecter -> security
        /** @var User */
        $user = $this->getUser();

        //2. Savoir qui est connecter -> security

        //3. Passer l'utilisateur connecté à twig afin afin d'afficher ses commandes ->Environement de twig /Response

        return $this->render('purchase/index.html.twig', [
            'purchases' => $user->getPurchases()
        ]);
    }
}
