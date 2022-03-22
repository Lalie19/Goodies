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
        $commande = new Commande();
        $user = $this->getUser();
        if ($user) {
            $commande->setFirstname($user->getFirstname())
            ->setName($user->getLastname());
        }

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


        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commande->setPrice($total)
                ->setPaid(false)
                ->setStripeSucessKey(uniqid());
            $entityManager->persist($commande);
            foreach ($cart as $id => $quantity) {
                $goodies = $goodiesRepository->find($id);
                $achats = new Achats;
                $achats->setCommande($commande)
                    ->setGoodies($goodies)
                    ->setNombres($goodies->getPrice())
                    ->setQuantity($quantity);
                $entityManager->persist($achats);

            }
            $entityManager->flush();

            return $this->redirectToRoute('stripe_checkout', ["commande" => $commande->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commandes/new.html.twig', [
            'commande' => $commande,
            'form' => $form,
            'cartGoodies' => $fullCart,
            'total' => $total,
        ]);
    }
}
