<?php

namespace App\Controller;


use App\Repository\GoodiesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/white')]
class WhiteController extends AbstractController
{
    #[Route('/', name: 'white_index', methods: ['GET'])]
    public function index(GoodiesRepository $whiteRepository ): Response
    {
        // findBy permet de retourner une liste d'object qu'on aura sellectionné// 
        return $this->render('white/index.html.twig', [
            'whites' => $whiteRepository->findBy( [ 'name' => 'White Blood'], [ 'price' => 'ASC']),
        ]);
    }
}
