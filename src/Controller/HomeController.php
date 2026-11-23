<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController

// Création de  routes
// Une Route = une Page WEB
{
    #[Route('/', name: 'app_home', methods: ['GET', 'POST'])]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', []);
    }

    #[Route('/catalogue', name: 'app_catalog', methods: ['GET', 'POST'])]
    public function catalog(ProductRepository $productRepository): Response
    

    {
        return $this->render('home/catalog.html.twig', [
            'products' => $productRepository->findAll()
        ]);
    }

     #[Route('/catalogue/{id}', name: 'app_catalog_show')]
        public function show(Product $product): Response
    {
        return $this->render('home/show.html.twig', [
            'product' => $product,
    ]); 

    }






}

