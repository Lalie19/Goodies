<?php

namespace App\Controller;



use App\Repository\ArticlesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/coupcoeur')]
class CoupCoeurController extends AbstractController
{
    #[Route('/', name: 'coupcoeur_index', methods: ['GET'])]
    public function index(ArticlesRepository $articlesRepository ): Response
    {
        // findBy( [ 'slug' => 'Coup de coeur']) retournera une liste d'objets comportant le nom "Coup de coeur"// 
        return $this->render('coupcoeur/index.html.twig', [
            'coupcoeurs' => $articlesRepository->findBy( [ 'slug' => 'Coup de coeur']),
        ]);
    }
    
}
