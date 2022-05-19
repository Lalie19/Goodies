<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Form\ArticlesType;
use App\Repository\ArticlesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/articles')]
class ArticlesController extends AbstractController
{
    //fonction qui permet d'afficher tout les articles//
    #[Route('/', name: 'articles_index', methods: ['GET'])]
    public function index(ArticlesRepository $articlesRepository): Response
    {
        // FindAll récupère tous les objets de la base de données //
        return $this->render('articles/index.html.twig', [
            'articles' => $articlesRepository->findAll(),
        ]);
    }

    // fonction qui permet d'ajouter un nouvel article //
    #[Route('/new', name: 'articles_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // crée un nouvel object //
        $article = new Articles();
        // appeler doctrine pour créer un form //
        $form = $this->createForm(ArticlesType::class, $article);
        // demande de traitement de la saisi du form  //
        $form->handleRequest($request);

        // si le form est soumi et qu'il est valide  //
        if ($form->isSubmitted() && $form->isValid()) {
            // indiquer a entityManager que cette entity devra être enregistrer  //
            $entityManager->persist($article);
            // enregistrement de l'entity dans la BDD //
            $entityManager->flush();

            // redirection de la page ver la page ci-dessous //
            return $this->redirectToRoute('articles_index', [], Response::HTTP_SEE_OTHER);
        }

        // création de la view du form affiché sur la page indiqué au render //
        return $this->renderForm('articles/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    // fonction qui permet d'afficher un article grâce a son ID  //
    #[Route('/{id}', name: 'articles_show', methods: ['GET'])]
    public function show(Articles $article): Response
    {
        return $this->render('articles/show.html.twig', [
            'article' => $article,
        ]);
    }

    // fonction qui permet de modifier un article grâce a son ID  //
    #[Route('/{id}/edit', name: 'articles_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Articles $article, EntityManagerInterface $entityManager): Response
    {
        // Doctrine crée un form selon l'article à modifier //
        $form = $this->createForm(ArticlesType::class, $article);
        // traitement de la saisie du form //
        $form->handleRequest($request);

        // si le form est soumi et qu'il est valide  //
        if ($form->isSubmitted() && $form->isValid()) {

            // enregistrement de l'entity dans la BDD //
            $entityManager->flush();

            // redirection de la page ver la page ci-dessous //
            return $this->redirectToRoute('articles_index', [], Response::HTTP_SEE_OTHER);
        }

        // modification de la view du form affiché sur la page indiqué au render //
        return $this->renderForm('articles/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    // fonction qui permet de supprimer un article grâce a son ID  //
    #[Route('/{id}', name: 'articles_delete', methods: ['POST'])]
    public function delete(Request $request, Articles $article, EntityManagerInterface $entityManager): Response
    {
        // vérifie si le token est valide //
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {

            // indiquer a entityManager que cette entity devra être supprimé  //
            $entityManager->remove($article);
            // supprimé l'entity de la BDD //
            $entityManager->flush();
        }

        // redirection de la page ver la page ci-dessous //
        return $this->redirectToRoute('articles_index', [], Response::HTTP_SEE_OTHER);
    }
}
