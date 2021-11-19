<?php

namespace App\Controller;

use App\Taxe\Calculator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController
{
    protected $calculator;
    public function __construct(Calculator $calculator)
    {
        $this->calculator = $calculator;
    }

    public function index()
    {
        dd("Ca fonctionne");
    }


    /**
     * @Route("/test/{age<\d+>?0}", name="test")
     */
    public function test(Request $request, $age)
    {
        // $age = $request->attributes->get('age');
        $tva = $this->calculator->calcul(100);

        return new Response("Le TVA est de $tva");
    }
}
