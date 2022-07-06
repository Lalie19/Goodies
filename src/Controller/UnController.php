<?php

namespace App\Controller;


use App\Repository\GoodiesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/un')]
class UnController extends AbstractController
{
    #[Route('/', name: 'un_index', methods: ['GET'])]
    public function index(GoodiesRepository $unRepository ): Response
    {
        
        return $this->render('un/index.html.twig', [
            'uns' => $unRepository->findBy( [ 'name' => 'UnOrdinary'], [ 'price' => 'ASC']),
        ]);
    }

}
