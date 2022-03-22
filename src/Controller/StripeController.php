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
        $privateKey = "sk_test_51KbPeEGLGNzn2cmOeQZIMSYG90TCdqnFg4cNSk7BIi9RL8ovAmYVZu6xZUSnZrMMkgojOvkuH1MQBMPykYSzc8xH00XZnPta92";
        Stripe::setApikey($privateKey);

        $achatCriteriav = [
            "commande" => $commande,
        ];
        $achatses = $achatsRepository->findBy($achatCriteriav);
        $lineItems = [];
        foreach ($achatses as $achat) {
            $item = [
                "price_data" => [
                    "currency" => "eur",
                    "goodies_data" =>[
                        "name" => $achat->getGoodies()->getName(),
                    ],
                    "unit_amount" => $achat->getNombres(),
                ],
                "quantity" => $achat->getQuantity(),
            ];
            $lineItems[] = $item;
        }
        // dd($lineItems);
        $successRoute = $this->generateUrl('stripe_valid_payment', [
            // "_locale" => $request->getLocale(),
            "commandes" => $commande->getId(),
            "stripeSucessKey" => $commande->getStripeSucessKey(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);
        // dd($successRoute);
        $errorRoute = $this->generateUrl('stripe_error_payment', [
            // "_locale" => $request->getLocale(),
            "commandes" => $commande->getId(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $stripeSession = \Stripe\Checkout\Session::create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'payment_method_types' => ['card'],
            'success_url' => $successRoute,
            'cancel_url' => $errorRoute,
        ]);
        // dd($stripeSession);
        $commande->setPiStripe($stripeSession->payement_intent);
        $entityManagerInterface->flush($commande);
        // dd($entityManagerInterface);
        return $this->redirect($stripeSession->url, 303);
    }

    #[Route('/stripe/{commandes}/success/{stripeSucessKey}', name: 'stripe_valid_payment')]
    public function success(Commande $commande, string $stripeSuccesKey, SessionInterface $session, AchatsRepository $achatsRepository): Response
    {
        if ($stripeSuccesKey != $commande->getStripeSucessKey()) {
            $this->redirectToRoute("stripe_error_payment", [
                'commande' => $commande->getId(),
            ]);
        }
        $commande->setPaid(true);
        $session->set('cart', []);
        $achatCriteriav = [
            "commande" => $commande,
        ];
        $achatses = $achatsRepository->findBy($achatCriteriav);
        return $this->render('stripe/success.html.twig', [
            'commande' => $commande,
            'achat' => $achatses,
        ]);
    }
    
    #[Route('/stripe/{commandes}/cancel', name: 'stripe_error_payment')]
    public function error(Commande $commande, TranslatorInterface $translator ): Response
    {
        $this->addFlash("danger", $translator->trans("src.controller.stripe:There was a problem during the payment process"));
        return $this->redirectToRoute("cart_show");
    }
}
