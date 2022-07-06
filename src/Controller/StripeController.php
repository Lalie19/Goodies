<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\Commande;
use App\Repository\AchatsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{
    #[Route('/{commande}/stripe', name: 'stripe_checkout')]
    public function check(Commande $commande, AchatsRepository $achatsRepository, Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        // récupérer la clé du compte stripe
        $privateKey = "sk_test_51KbPeEGLGNzn2cmOeQZIMSYG90TCdqnFg4cNSk7BIi9RL8ovAmYVZu6xZUSnZrMMkgojOvkuH1MQBMPykYSzc8xH00XZnPta92";
        // appelé stripe
        Stripe::setApikey($privateKey);

        // // récuper les comamndes pour la facture 
        $achatCriteriav = [
            "commande" => $commande,
        ];
        $achatses = $achatsRepository->findBy($achatCriteriav);
        
        $lineItems = [];
        foreach ($achatses as $achat) {
            // récupere les données 
            $item = [
                "price_data" => [
                    "currency" => "eur",
                    "product_data" =>[
                        "name" => $achat->getGoodies()->getName(),
                    ],
                    "unit_amount" => 100*$achat->getNombres(),
                ],
                "quantity" => $achat->getQuantity(),
            ];
            // ajout
            $lineItems[] = $item;
        }
        // dd($lineItems);
        // route pour validé le payment 
        $successRoute = $this->generateUrl('stripe_valid_payment', [
            "_locale" => $request->getLocale(),
            "commande" => $commande->getId(),
            "stripeSucessKey" => $commande->getStripeSucessKey(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);
        // dd($successRoute);
        // route en cas d'erreur
        $errorRoute = $this->generateUrl('stripe_error_payment', [
            "_locale" => $request->getLocale(),
            "commande" => $commande->getId(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        // rediction vers stripe et redirige vers l'une de route 
        $stripeSession = \Stripe\Checkout\Session::create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'payment_method_types' => ['card'],
            'success_url' => $successRoute,
            'cancel_url' => $errorRoute,
        ]);
        // dd($stripeSession);
        // mettre a jour commande
        $commande->setPiStripe($stripeSession->payement_intent);
        $entityManagerInterface->flush($commande);
        // dd($entityManagerInterface);
        return $this->redirect($stripeSession->url, 303);
    }

    #[Route('/stripe/{commande}/success/{stripeSucessKey}', name: 'stripe_valid_payment')]
    public function success(Commande $commande, string $stripeSucessKey, SessionInterface $session, AchatsRepository $achatsRepository): Response
    {
        // dd($commande);
        if ($stripeSucessKey != $commande->getStripeSucessKey()) {
            $this->redirectToRoute("stripe_error_payment", [
                'commande' => $commande->getId(),
            ]);
        }
        $commande->setPaid(true);
        // on le met dans la session
        $session->set('cart', []);
        $achatCriteriav = [
            "commande" => $commande,
        ];
        $achatses = $achatsRepository->findBy($achatCriteriav);
        return $this->render('stripe/success.html.twig', [
            'commande' => $commande,
            'achatses' => $achatses,
        ]);
    }
    
    #[Route('/stripe/{commande}/cancel', name: 'stripe_error_payment')]
    public function error(Commande $commande, TranslatorInterface $translator ): Response
    {
        // afficher un message
        $this->addFlash("danger", $translator->trans("src.controller.stripe:There was a problem during the payment process"));
        return $this->redirectToRoute("cart_show");
    }
}
