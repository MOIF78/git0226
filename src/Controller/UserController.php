<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

final class UserController extends AbstractController
{
    #[Route('/admin/users', name: 'app_user_index')]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/admin/users/{id}/edit', name: 'app_user_edit')]
public function edit(
    User $user,
    Request $request,
    EntityManagerInterface $entityManager
): Response
{
    $form = $this->createForm(UserType::class, $user);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

        $entityManager->flush();

        return $this->redirectToRoute('app_user_index');
    }

    return $this->render('user/edit.html.twig', [
        'form' => $form->createView(),
    ]);
}
}