<?php

namespace App\Controller;


use App\Repository\GoodiesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/highschool')]
class HighschoolController extends AbstractController
{
    #[Route('/', name: 'highschool_index', methods: ['GET'])]
    public function index(GoodiesRepository $goodiesRepository ): Response
    {
        // findBy permet de retourner une liste d'object qu'on aura sellectionné// 
        return $this->render('highschool/index.html.twig', [
            'highschools' => $goodiesRepository->findBy( [ 'name' => 'Highschool leveling'], [ 'price' => 'ASC']),
        ]);
    }
    
}
