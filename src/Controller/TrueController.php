<?php

namespace App\Controller;


use App\Repository\GoodiesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/true')]
class TrueController extends AbstractController
{
    #[Route('/', name: 'true_index', methods: ['GET'])]
    public function index(GoodiesRepository $goodiesRepository ): Response
    {
        return $this->render('true/index.html.twig', [
            'trues' => $goodiesRepository->findBy( [ 'name' => "True"]),
        ]);
    }
    
}