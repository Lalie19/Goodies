<?php

namespace App\Controller;
use App\Repository\GoodiesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/tower')]
class TowerController extends AbstractController
{
    #[Route('/', name: 'tower_index', methods: ['GET'])]
    public function index(GoodiesRepository $goodiesRepository ): Response
    {
        // findBy permet de retourner une liste d'object qu'on aura sellectionné// 
        return $this->render('tower/index.html.twig', [
            'towers' => $goodiesRepository->findBy( [ 'name' => 'Tower of God'], [ 'price' => 'ASC']),
        ]);
    }
    
}
