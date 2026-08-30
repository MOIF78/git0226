<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


/*  préfixe */
#[Route('/produit')]
final class ProductController extends AbstractController

{
    #[Route('/afficher', name: 'app_product_index')]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();

        /* 
            finAll() ==> retourne un array / équivalent SELECT * FROM product
            find($arg) ==> reoturne un objet ou null /équivalent SELECT * FROM  product WHERE id

            findBy(['price' => 100, 'stock' => 8], ['price' => 'ASC'], 10);
            ==> retourne un array
            ==> SELECT * FROM product WHERE price = 100 AND stock = 8 ORDER BY price LIMIT 10;

            -> finfOneBy() ==> retourne un objet ou null
        
        
        */

        // dd($products);

        return $this->render('product/index.html.twig', [
            'products' => $products

        ]);
    }

    #[Route('/ajouter', name:'app_product_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        //Création de l'objet de la class Product (Entity)
        $product = new Product();
        //dump($product);

        /* Création du formulaire avec la méthode creatFrom (), provenant de Abstraction. */
        $form = $this->createForm(ProductType::class, $product);

        // traitement du formulaire
        $form->handleRequest($request);

        if($form->isSubmitted()) {
           // dump($form->isValid());
        }
            

        /* condition
         si le formulaire à été soumis 
         si le formulaire à été validé  (respect des contraintes*/

        if ($form->isSubmitted() && $form->isValid()) {

            //dump($request);
            // dd($product);

            //Enregistrer en base de données

            // persist => on renseigne quel objet on veut enregister
            $entityManager->persist($product);

            // flush => permet d'executer
            $entityManager->flush();

            //dd($product);


            // noticication
            /* 
            AddFlashe() permet de créer une notification sur le front.
            1e argument: le type (success, warning, error : ref _flashes.html.twig)
            2 e argument : Message
             */
            $this->addFlash('success', 'Le produit a bien été ajouté');

            /* flashes =[
                'success' => [

                ],
                'error' => []
            ] */


            // Redirection == équivalent à la fonction twig path()
            return $this->redirectToRoute('app_product_index');

        }

        /* dd($form->createView()); */
        return $this->render('product/new.html.twig', [
            'formProduct' => $form->createView()
        ]);
            
    }

    #[Route('/{id}', name:'app_product_show', methods:['GET'])]
    public function show(Product $product): Response
    {
        // dump($product);
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }
    
    #[Route('/modifier/{id}', name:'app_product_edit')]
    public function edit(Product $product, Request $request, EntityManagerInterface $entityManager): Response

    {
        $form =$this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Le produit a bien été modifié');
            return $this->redirectToRoute('app_product_index');
        }
        
        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'formProduct' => $form
        ]);
    }

    #[Route('/{id}', name:'app_product_delete', methods:['POST'])]
    public function delete(Product $product, EntityManagerInterface $entityManager): Response

    //   dd('Je suis bien sur la route delete');
    
    {
      $entityManager->remove($product);
      $entityManager->flush();
      $this->addFlash('success', 'Le produit a bien été supprimé');
      return $this->redirectToRoute('app_product_index');
    }


}

