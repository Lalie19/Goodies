<?php

namespace App\Controller;

use App\Entity\Achats;
use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\GoodiesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CommandesController extends AbstractController
{
    #[Route('/commandes', name: 'app_commandes')]
    public function index(): Response
    {
        return $this->render('commandes/index.html.twig', [
            'controller_name' => 'CommandesController',
        ]);
    }

    #[Route('/new', name: 'commandes_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SessionInterface $session, GoodiesRepository $goodiesRepository): Response
    {
        // crée un nouvel object //
        $commande = new Commande();
        // récupere l'utilisateur //
        $user = $this->getUser();
        // pré-injecter des donnée qui sont donc le nom et le prénom si la personne est connecté//
        if ($user) {
            $commande->setFirstname($user->getFirstname())
            ->setName($user->getLastname());
        }

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


        // appeler doctrine pour créer un form //
        $form = $this->createForm(CommandeType::class, $commande);
        // demande de traitement de la saisi du form  //
        $form->handleRequest($request);

        // si le form est soumi et qu'il est valide  //
        if ($form->isSubmitted() && $form->isValid()) {
            //  ajouter total //
            $commande->setPrice($total)
            //ajouter paid//
                ->setPaid(false)
            // ajouter une clé qui est unique //
                ->setStripeSucessKey(uniqid());
            // indiquer a entityManager que cette entity devra être enregistrer  //
            $entityManager->persist($commande);
            // parcourir le panier  //
            foreach ($cart as $id => $quantity) {
                // recherche du protuit //
                $goodies = $goodiesRepository->find($id);
                // crée un nouvel objet purchase //
                $achats = new Achats;
                // ajouter //
                $achats->setCommande($commande)
                    ->setGoodies($goodies)
                    ->setNombres($goodies->getPrice())
                    ->setQuantity($quantity);
                // indiquer a entityManager que cette entity devra être enregistrer  //
                $entityManager->persist($achats);

            }
            // enregistrement de l'entity dans la BDD //
            $entityManager->flush();

            // redirection de la page ver la page ci-dessous //
            return $this->redirectToRoute('stripe_checkout', ["commande" => $commande->getId()], Response::HTTP_SEE_OTHER);
        }

        // création de la view du form affiché sur la page indiqué au render //
        return $this->renderForm('commandes/new.html.twig', [
            'commande' => $commande,
            'form' => $form,
            'cartGoodies' => $fullCart,
            'total' => $total,
        ]);
    }
}
