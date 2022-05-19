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
        // Récupérer un panier dans la session et stocker dans la session sinon c'est un tableau vide //
        $cart = $session->get('cart', []);
        // selectionne le produit //
        $id = $goodies->getId();

        // mettre un ou plusieurs produit dans le panier //
        if (array_key_exists($id, $cart)){
            // si le produit existe déjà on le rajoute //
            $cart[$id]++;
        } else {
            // sinon on ajoute une fois le produit //
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
        // Récupérer un panier dans la session et stocker dans la session sinon c'est un tableau vide//
        $cart = $session->get('cart', []);
        // // selectionne le produit//
        $id = $goodies->getId();

        // elever le produit si il y a plus que 2 produit //
        if (2 > $cart[$id]) {
            // surpprime le goodies //
            unset($cart[$id]);
        } else {
            /// enleve un goodies // 
            $cart[$id]--;
        }

        // on le met dans la session//
        $session->set('cart', $cart);

        //ensuite on retourne sur la page du produit qui a été mis dans le panier
        return $this->redirectToRoute('cart_show', [
            'id' => $id,
        ]);
        dd($cart);
        
    }


    // fonction pour enlever supprimer//
    #[Route(['/{goodies}/remove',], name: 'cart_remove')]
    public function remove(Goodies $goodies, SessionInterface $session ): Response
    {
        // Récupérer un panier dans la session et stocker dans la session sinon c'est un tableau vide
        $cart = $session->get('cart', []);
        // selectionne le produit
        $id = $goodies->getId();

        // surpprime le goodies //
            unset($cart[$id]);
        
        // on le met dans la session//
        $session->set('cart', $cart);

        //ensuite on retourne sur la page du produit qui a été mis dans le panier
        return $this->redirectToRoute('cart_show', [
            'id' => $id,
        ]);
        dd($cart);
        
    }

    #[Route(['/show',], name: 'cart_show')]
    public function show(GoodiesRepository $goodiesRepository, SessionInterface $session ): Response
    {
        // déclare un tableau vide pour stocké ensuite des données  //
        $fullCart = [];
        // declarer total //
        $total = 0;
        // Récupérer un panier dans la session et stocker dans la session sinon c'est un tableau vide//
        $cart = $session->get('cart', []);
        // parcourir dans le tableau cart //
        foreach ($cart as $id => $quantity) {
            // recherche du protuit //
            $goodies = $goodiesRepository->find($id);
            // ajouté un tableau qui contient les goodies et la quantité//
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
