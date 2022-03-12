<?php

namespace App\Controller;

use App\Entity\Commandes;
use App\Form\CommandesType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/commandes')]
class InvoiceController extends AbstractController
{
    #[Route('/', name: 'app_invoice')]
    public function index(): Response
    {
        return $this->render('invoice/index.html.twig', [
            'controller_name' => 'InvoiceController',
        ]);
    }

    #[Route('/', name: 'invoice_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $invoice = new Commandes();
        $user = $this->getUser();
        if ($user) {
            $invoice->setFirstname($user->getFirstname())
                ->setName($user->getLastname());
        }
        $form = $this->createForm(CommandesType::class, $invoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($invoice);
            $entityManager->flush();

            return $this->redirectToRoute('goodies_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commandes/new.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }
}
