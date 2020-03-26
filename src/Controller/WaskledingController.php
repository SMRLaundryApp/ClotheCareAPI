<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Products;
use App\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class WaskledingController extends AbstractController
{
    /**
     * @Route("/waskleding", name="waskleding")
     */
    public function index()
    {
        $value = 1;
        $entityManager = $this->getDoctrine()->getManager();
        $product = $entityManager->getRepository(Products::class)->findByExampleField($value);

        $customer = new Customer();
        return $this->render('waskleding/index.html.twig', [
            'controller_name' => 'WaskledingController',
        ]);
    }
}
