<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\ProfileType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/user')]
class UserController extends AbstractController
{
    //fonction qui permet d'afficher tout les users//
    #[Route('/', name: 'user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        // FindAll récupère tous les objets de la base de données //
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    // fonction qui permet d'ajouter un nouvel user //
    #[Route('/new', name: 'user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // crée un nouvel object //
        $user = new User();
        // appeler doctrine pour créer un form //
        $form = $this->createForm(UserType::class, $user);
        // demande de traitement de la saisi du form  //
        $form->handleRequest($request);

        // si le form est soumi et qu'il est valide  //
        if ($form->isSubmitted() && $form->isValid()) {
            // indiquer a entityManager que cette entity devra être enregistrer  //
            $entityManager->persist($user);
            // enregistrement de l'entity dans la BDD //
            $entityManager->flush();

            // redirection de la page ver la page ci-dessous //
            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        }

        // création de la view du form affiché sur la page indiqué au render //
        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    // fonction qui permet d'afficher un user grâce a son ID  //
    #[Route('/{id}/show', name: 'user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    // fonction qui permet de modifier un user grâce a son ID  //
    #[Route('/{id}/edit', name: 'user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        // Doctrine crée un form selon l'user à modifier //
        $form = $this->createForm(UserType::class, $user);
        // traitement de la saisie du form //
        $form->handleRequest($request);

        // si le form est soumi et qu'il est valide  //
        if ($form->isSubmitted() && $form->isValid()) {
            // dd($user);
            $entityManager->flush();

            // redirection de la page ver la page ci-dessous //
            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        }

        // modification de la view du form affiché sur la page indiqué au render //
        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    // fonction qui permet de supprimer un user grâce a son ID  //
    #[Route('/{id}/delete', name: 'user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        // vérifie si le token est valide //
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            // indiquer a entityManager que cette entity devra être supprimé  //
            $entityManager->remove($user);
            // supprimé l'entity de la BDD //
            $entityManager->flush();
        }

        // redirection de la page ver la page ci-dessous //
        return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/profil', name: 'user_profil', methods: ['GET'])]
    #[IsGranted("ROLE_USER")]
    public function profil(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        
        return $this->render('user/profil.html.twig', [
            'user' => $user,
        ]); 

    }
    #[Route('/{id}/profil_edit', name: 'profil_edit', methods: ['GET', 'POST'])]
    public function profilEdit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(ProfileType::class, $user);
        // traitement de la saisie du form //
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('user_profil', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('user/profil_edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
    
}