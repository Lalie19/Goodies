<?php

namespace App\Controller;



use App\Repository\GoodiesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/iloveyoo')]
class IloveyooController extends AbstractController
{
    #[Route('/', name: 'iloveyoo_index', methods: ['GET'])]
    public function index(GoodiesRepository $goodiesRepository ): Response
    {
        // findBy permet de retourner une liste d'object qu'on aura sellectionné// 
        return $this->render('iloveyoo/index.html.twig', [
            'iloveyoos' => $goodiesRepository->findBy( [ 'name' => "I love YOO"], [ 'price' => 'ASC']),
        ]);
    }

}
