<?php

namespace App\Controller;


use App\Entity\Goodies;
use App\Form\GoodiesType;
use App\Repository\GoodiesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/lets')]
class LetsController extends AbstractController
{
    #[Route('/', name: 'lets_index', methods: ['GET'])]
    public function index(GoodiesRepository $goodiesRepository ): Response
    {
        
        return $this->render("lets/index.html.twig", [
            'letss' => $goodiesRepository->findBy( [ 'name' => "Let's play"], [ 'price' => 'ASC']),
        ]);
    }

    
}
