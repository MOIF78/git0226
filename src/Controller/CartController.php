<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

final class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(): Response
{
    /** @var \App\Entity\User $user */
    $user = $this->getUser();

    $cart = $user?->getCart();

    $total = 0;

    if ($cart) {

        foreach ($cart->getCartItems() as $item) {

            $total += $item->getProduct()->getPrice()
                * $item->getQuantity();
        }
    }

    return $this->render('cart/index.html.twig', [
        'cart' => $cart,
        'total' => $total,
    ]);
}

    #[Route('/cart/add/{id}', name: 'app_cart_add')]
    public function add(
    Product $product,
    EntityManagerInterface $entityManager
    ): Response
{
    /** @var \App\Entity\User $user */
    $user = $this->getUser();

    if (!$user) {
        return $this->redirectToRoute('app_login');
    }

    $cart = $user->getCart();

    if (!$cart) {

        $cart = new Cart();

        $cart->setUser($user);

        $cart->setCreatedAt(new \DateTimeImmutable());

        $cart->setStatus('active');

        $entityManager->persist($cart);
    }

    foreach ($cart->getCartItems() as $item) {

        if(
            $item->getProduct()->getId()
            ===
            $product->getId()
        ) {
            $item->setQuantity(
                $item->getQuantity() + 1
        );

            $entityManager->flush();

            return $this->redirectToRoute('app_cart');
        }

    }

    $cartItem = new CartItem();

    $cartItem->setCart($cart);

    $cartItem->setProduct($product);

    $cartItem->setQuantity(1);

    $entityManager->persist($cartItem);

    $entityManager->flush();

    return $this->redirectToRoute('app_cart');
}

#[Route('/cart/delete/{id}', name: 'app_cart_delete')]
public function delete(
    CartItem $cartItem,
    EntityManagerInterface $entityManager
): Response
{
    $entityManager->remove($cartItem);

    $entityManager->flush();

    return $this->redirectToRoute('app_cart');
}
}
