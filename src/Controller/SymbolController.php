<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SymbolController extends AbstractController
{
    /**
     * @Route("/symbol", name="homepage")
     */
    public function index()
    {
        return $this->render('symbol/index.html.twig', [
            'controller_name' => 'SymbolController',
        ]);
    }
}
