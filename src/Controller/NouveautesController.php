<?php

namespace App\Controller;



use App\Repository\ArticlesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/nouveaute')]
class NouveautesController extends AbstractController
{
    #[Route('/', name: 'nouveaute_index', methods: ['GET'])]
    public function index(ArticlesRepository $articlesRepository ): Response
    {
        return $this->render('nouveaute/index.html.twig', [
            'nouveautes' => $articlesRepository->findBy( [ 'slug' => 'Nouveaute']),
        ]);
    }
    
}
