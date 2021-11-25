<?php

namespace App\Controller;

use App\Taxe\Calculator;
use App\Taxe\Detector;
use Cocur\Slugify\Slugify;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class HelloController extends AbstractController
{
    /**
     * @Route("/hello/{prenom?world}", name="hello")
     */
    public function hello($prenom = "Emmanuel")
    {
        return $this->render('hello.html.twig', [
            'prenom' => $prenom
        ]);
    }

    /**
     * @Route("/exemple", name="exemple")
     */
    public function exemple()
    {
        return $this->render('exemple.html.twig', ['age' => 26]);
    }
}
