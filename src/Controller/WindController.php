<?php

namespace App\Controller;


use App\Repository\GoodiesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/wind')]
class WindController extends AbstractController
{
    #[Route('/', name: 'wind_index', methods: ['GET'])]
    public function index(GoodiesRepository $goodiesRepository ): Response
    {
        // findBy permet de retourner une liste d'object qu'on aura sellectionné// 
        return $this->render('wind/index.html.twig', [
            'winds' => $goodiesRepository->findBy( [ 'name' => 'Wind Breack'], [ 'price' => 'ASC']),
        ]);
    }
    
}
