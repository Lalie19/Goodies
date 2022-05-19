<?php

namespace App\Controller;


use App\Repository\GoodiesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/remaried')]
class RemariedController extends AbstractController
{
    #[Route('/', name: 'remaried_index', methods: ['GET'])]
    public function index(GoodiesRepository $goodiesRepository ): Response
    {
        // findBy permet de retourner une liste d'object qu'on aura sellectionné// 
        return $this->render('remaried/index.html.twig', [
            'remarieds' => $goodiesRepository->findBy( [ 'name' => "Remaried Empress"], [ 'price' => 'ASC']),
        ]);
    }

}
