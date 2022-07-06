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
        
        $article = new Articles();
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager->persist($article);
            $entityManager->flush();

            
            return $this->redirectToRoute('articles_index', [], Response::HTTP_SEE_OTHER);
        }

        
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
        
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('articles_index', [], Response::HTTP_SEE_OTHER);
        }

        
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

            
            $entityManager->remove($article);
            
            $entityManager->flush();
        }

        
        return $this->redirectToRoute('articles_index', [], Response::HTTP_SEE_OTHER);
    }
}
