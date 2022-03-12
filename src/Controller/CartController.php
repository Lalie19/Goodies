<?php

namespace App\Controller;

use App\Entity\Goodies;
use App\Repository\GoodiesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route([
    '/cart',
])]
class CartController extends AbstractController
{
    #[Route([
        '/{goodies}/add'
        ,
    ], name: 'cart_add')]
    public function add(Goodies $goodies, /*Request $request*/ SessionInterface $session ): Response
    {
        $cart = $session->get('cart', []);
        $id = $goodies->getId();

        if (array_key_exists($id, $cart)){
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        $session->set('cart', $cart);
        // [
        //     11 => 1, 
        //     14 => 2,
        // ]
        return $this->redirectToRoute('cart_show', [
            'id' => $id,
        ]);
        dd($cart);
        
    }


    #[Route([
        '/{goodies}/less'
        ,
    ], name: 'cart_less')]
    public function less(Goodies $goodies, SessionInterface $session ): Response
    {
        $cart = $session->get('cart', []);
        $id = $goodies->getId();

        if (2 > $cart[$id]) {
            unset($cart[$id]);
        } else {
            $cart[$id]--;
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('cart_show', [
            'id' => $id,
        ]);
        dd($cart);
        
    }


    #[Route([
        '/{goodies}/remove'
        ,
    ], name: 'cart_remove')]
    public function remove(Goodies $goodies, SessionInterface $session ): Response
    {
        $cart = $session->get('cart', []);
        $id = $goodies->getId();

            unset($cart[$id]);
        

        $session->set('cart', $cart);

        return $this->redirectToRoute('cart_show', [
            'id' => $id,
        ]);
        dd($cart);
        
    }

    #[Route([
        '/show'
        ,
    ], name: 'cart_show')]
    public function show(GoodiesRepository $goodiesRepository, SessionInterface $session ): Response
    {
        $fullCart = [];
        $total = 0;
        $cart = $session->get('cart', []);
        foreach ($cart as $id => $quantity) {
            $goodies = $goodiesRepository->find($id);
            $fullCart[] = [
                "goodies" => $goodies,
                "quantity" => $quantity,
            ];
            $total += $goodies->getPrice() * $quantity;
        }

        return $this->render('cart/show.html.twig', [
            'cartGoodies' => $fullCart,
            'total' => $total,
        ]);
        
        
    }
}
