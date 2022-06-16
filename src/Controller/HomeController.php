<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Form\Builder\Contact;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        // $this->addFlash("success", "voici un messsage de réussité");
        return $this->render('home/index.html.twig', [
            'controller_name' => 'Home',
        ]);
    }

    #[Route('/contact', name: 'home_contact')]
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // dd ($contact);

            $email= new TemplatedEmail();
            $email->to(new Address("no-response@lalie.net", "service client"))
                ->from($contact->getEmail())
                ->subject($contact->getSubject())
                ->htmlTemplate('email/contact.html.twig')
                ->context([
                "message" => $contact->getMessage(),
                ]);
            $mailer->send($email);
            $this->addFlash("success", "votre message a bien été envoyé");
            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('home/contact.html.twig', [
            'contact' => $contact,
            'form' => $form,
        ]);
    
        return $this->render('home/contact.html.twig');
    }

    #[Route('/condition', name: 'home_condition')]
    public function condition(): Response
    {
            return $this->render('home/condition.html.twig', [
            'controller_name' => 'HomeController',          
        ]);
    }

    #[Route('/mention', name: 'home_mention')]
    public function mention(): Response
    {
            return $this->render('home/mention.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/policy', name: 'home_policy')]
    public function policy(): Response
    {
            return $this->render('home/policy.html.twig', [
            'controller_name' => 'HomeController',
          
        ]);
    }

}
