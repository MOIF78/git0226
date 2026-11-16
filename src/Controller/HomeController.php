<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProductRepository;

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

    






}

