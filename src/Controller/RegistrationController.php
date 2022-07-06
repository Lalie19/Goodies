<?php

namespace App\Controller;

use App\Entity\User;
use App\Tool\UploadTool;
use App\Security\EmailVerifier;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\UserAuthenticator;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/register', name: 'register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UserAuthenticator $authenticator, EntityManagerInterface $entityManager, UploadTool $uploadTool): Response
    {
        // crée un nouvel utilisateur 
        $user = new User();
        // appeler doctrine pour crée le form 
        $form = $this->createForm(RegistrationFormType::class, $user);
        // demande de traitement de la saisi du form 
        $form->handleRequest($request);

        // si le form est soumi et qu'il est valide  
        if ($form->isSubmitted() && $form->isValid()) {
            // recupérer les images 
            $imageFile = $form->get('picture')->getData();
            // si il y a une image on la traite
            if ($imageFile) {
                $finalFileName = $uploadTool->upload($imageFile);

                $user->setPicture($finalFileName);
            }
            // traité le mot de passe 
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            // indiquer a entityManager que cette entity devra être enregistrer
            $entityManager->persist($user);
            // enregistrement de l'entity dans la BDD
            $entityManager->flush();

            // générer une URL signée et l’envoyer par e-mail à l’utilisateur
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('no-response@lalie.net', 'service client'))
                    ->to($user->getEmail())
                    ->subject('Veuiellez confirmer votre email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            // message qui s'affiche 
            $this->addFlash("warning", "vous devez validez votre adresse de courriel");

            
            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        // création de la view du form affiché sur la page indiqué au render
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, UserRepository $userRepository): Response
    {
        // récupérer id
        $id = $request->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }

        // valider le lien de confirmation de l'email
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        // les messages flash plus la redirection
        $this->addFlash('success', 'Votre adress de courriel  été correctement validé.');
        $this->addFlash('success', "Votre êtes connecté, vous avez été redirigé vers la page d'acceuil.");

        return $this->redirectToRoute('home');
    }
}
