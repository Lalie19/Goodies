<?php

namespace App\Controller;


use App\Repository\GoodiesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/solo')]
class SoloController extends AbstractController
{
    #[Route('/', name: 'solo_index', methods: ['GET'])]
    public function index(GoodiesRepository $goodiesRepository ): Response
    {
        
        return $this->render('solo/index.html.twig', [
            'solos' => $goodiesRepository->findBy( [ 'name' => 'Solo leveling'], [ 'price' => 'ASC']),
        ]);
    }
    
}
