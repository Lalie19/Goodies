<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[
    Route('/admin'),
    IsGranted("ROLE_ADMIN")]

class AdminController extends AbstractController
{
    #[Route('/', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    

    // #[Route('/list', name: 'admin_list', methods: ['GET'])]
    // public function list(UserRepository $user): Response
    // {
    //     return $this->render('user/list.html.twig', [
    //         'user' =>$user,
    //     ]);
    // }

    
}
