<?php

namespace App\Controller;


use App\Repository\GoodiesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/purple')]
class PurpleController extends AbstractController
{
    #[Route('/', name: 'purple_index', methods: ['GET'])]
    public function index(GoodiesRepository $goodiesRepository ): Response
    {
         
        return $this->render('purple/index.html.twig', [
            'purples' => $goodiesRepository->findBy( [ 'name' => 'Purple Jacinth'], [ 'price' => 'ASC']),
        ]);
    }
    
}
