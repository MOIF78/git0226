<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Order;
use App\Entity\OrderItem;
use Doctrine\ORM\EntityManagerInterface;

final class OrderController extends AbstractController
{
    #[Route('/order', name: 'app_order')]
    public function index(): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        if (!$user) {
        return $this->redirectToRoute('app_login');
    }

        return $this->render('order/index.html.twig', [
            'orders' => $user->getOrders(),
        ]);
    }

    #[Route('/order/validate', name: 'app_order_validate')]
    public function validateOrder(
        EntityManagerInterface $entityManager
    ): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $cart = $user->getCart();

        if (!$cart || $cart->getCartItems()->isEmpty()) {
            return $this->redirectToRoute('app_cart');
        }

        $order = new Order();

        $order->setUser($user);
        $order->setCreatedAt(new \DateTimeImmutable());
        $order->setStatus('pending');

        $total = 0;

        foreach ($cart->getCartItems() as $cartItem) {

            $orderItem = new OrderItem();

            $orderItem->setCustomerOrder($order);
            $orderItem->setProduct($cartItem->getProduct());
            $orderItem->setQuantity($cartItem->getQuantity());
            $orderItem->setUnitPrice(
                $cartItem->getProduct()->getPrice()
            );

            $total +=
                $cartItem->getProduct()->getPrice()
                * $cartItem->getQuantity();

            $entityManager->persist($orderItem);

            $entityManager->remove($cartItem);
        }

        $order->setTotalAmount($total);

        $entityManager->persist($order);

        $entityManager->flush();

        return $this->redirectToRoute('app_order');
    }
}













































