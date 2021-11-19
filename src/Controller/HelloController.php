<?php

namespace App\Controller;

use App\Taxe\Calculator;
use Cocur\Slugify\Slugify;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class HelloController
{
    /**
     * @Route("/hello/{prenom?world}", name="hello")
     */
    public function hello($prenom = "Emmanuel", LoggerInterface $logger, Calculator $calculator, Slugify $slugify, Environment $twig)
    {
        dd($twig);
        // $slugify = new Slugify();
        dd($slugify->slugify("Emmanuel Onivogui"));

        $logger->error("Mon message de log");
        $tva = $calculator->calcul(100);
        dd($tva);
        return new Response("Hello $prenom");
    }
}
