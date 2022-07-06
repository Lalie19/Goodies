<?php

namespace App\Controller;



use App\Repository\GoodiesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/noblesse')]
class NoblesseController extends AbstractController
{
    #[Route('/', name: 'noblesse_index', methods: ['GET'])]
    public function index(GoodiesRepository $goodiesRepository ): Response
    {
         
        return $this->render('noblesse/index.html.twig', [
            'noblesses' => $goodiesRepository->findBy( [ 'name' => 'Noblesse'], [ 'price' => 'ASC']),
        ]);
    }
    
}
