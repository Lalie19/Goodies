<?php

namespace App\Controller;

use App\Entity\Goodies;
use App\Repository\GoodiesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route(['/cart',])]
class CartController extends AbstractController
{
    // fonction pour ajouter //
    #[Route(['/{goodies}/add',], name: 'cart_add')]
    public function add(Goodies $goodies, SessionInterface $session ): Response
    {
        // Récupérer un panier dans la session et stocker dans la session  //
        $cart = $session->get('cart', []);
        $id = $goodies->getId();

        // mettre un ou plusieurs produit dans le panier //
        if (array_key_exists($id, $cart)){
            
            $cart[$id]++;
        } else {
            
            $cart[$id] = 1;
        }

        // on le met dans la session//
        $session->set('cart', $cart);

        //ensuite on retourne sur la page du produit qui a été mis dans le panier //
        return $this->redirectToRoute('cart_show', [
            'id' => $id,
        ]);
        dd($cart);
        
    }


    // fonction pour enlever un produit //
    #[Route(['/{goodies}/less',], name: 'cart_less')]
    public function less(Goodies $goodies, SessionInterface $session ): Response
    {
        
        $cart = $session->get('cart', []);
        $id = $goodies->getId();

        // elever le produit si il y a plus que 2 produit //
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


    // fonction pour enlever supprimer//
    #[Route(['/{goodies}/remove',], name: 'cart_remove')]
    public function remove(Goodies $goodies, SessionInterface $session ): Response
    {
        
        $cart = $session->get('cart', []);
        $id = $goodies->getId();

        // surpprime le goodies //
            unset($cart[$id]);
        
        
        $session->set('cart', $cart);

        
        return $this->redirectToRoute('cart_show', [
            'id' => $id,
        ]);
        dd($cart);
        
    }

    #[Route(['/show',], name: 'cart_show')]
    public function show(GoodiesRepository $goodiesRepository, SessionInterface $session ): Response
    {
        
        $fullCart = [];
        $total = 0;
        // Récupérer un panier dans la session et stocker dans la session //
        $cart = $session->get('cart', []);
        
        foreach ($cart as $id => $quantity) {
            // recherche du protuit //
            $goodies = $goodiesRepository->find($id);
            // ajouté  les goodies et la quantité//
            $fullCart[] = [
                "goodies" => $goodies,
                "quantity" => $quantity,
            ];
            // les goodies * la quantity qui sera ajouté au total//
            $total += $goodies->getPrice() * $quantity;
        }

        //ensuite on retourne dans le panier avec le récapitulatif//
        return $this->render('cart/show.html.twig', [
            'cartGoodies' => $fullCart,
            'total' => $total,
        ]);
        
        
    }
}
