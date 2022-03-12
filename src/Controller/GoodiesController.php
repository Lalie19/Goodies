<?php

namespace App\Controller;

use App\Entity\Goodies;
use App\Form\GoodiesType;
use App\Repository\GoodiesRepository;
use App\Tool\UploadTool;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/goodies')]
class GoodiesController extends AbstractController
{
    #[Route('/', name: 'goodies_index', methods: ['GET'])]
    public function index(GoodiesRepository $goodiesRepository): Response
    {
        return $this->render('goodies/index.html.twig', [
            'goodies' => $goodiesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'goodies_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UploadTool $uploadTool): Response
    {
        $goody = new Goodies();
        $form = $this->createForm(GoodiesType::class, $goody);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $finalFileName = $uploadTool->upload($imageFile);

                $goody->setImage($finalFileName);
            }
            // $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($goody);
            $entityManager->flush();

            return $this->redirectToRoute('goodies_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('goodies/new.html.twig', [
            'goody' => $goody,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'goodies_show', methods: ['GET'])]
    public function show(Goodies $goody): Response
    {
        return $this->render('goodies/show.html.twig', [
            'goody' => $goody,
        ]);
    }

    #[Route('/{id}/edit', name: 'goodies_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Goodies $goody, EntityManagerInterface $entityManager, UploadTool $uploadTool): Response
    {
        $form = $this->createForm(GoodiesType::class, $goody);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $oldPath = "";
                if ($goody->getImage()){
                    $oldPath = $goody->getImage();
                }
                $finalFileName = $uploadTool->upload($imageFile, $oldPath);

                $goody->setImage($finalFileName);
            }

            $entityManager->flush();

            return $this->redirectToRoute('goodies_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('goodies/edit.html.twig', [
            'goody' => $goody,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'goodies_delete', methods: ['POST'])]
    public function delete(Request $request, Goodies $goody, EntityManagerInterface $entityManager, UploadTool $uploadTool): Response
    {
        if ($this->isCsrfTokenValid('delete'.$goody->getId(), $request->request->get('_token'))) {
            $uploadTool->delete($goody->getImage());
            $entityManager->remove($goody);
            $entityManager->flush();
        }

        return $this->redirectToRoute('goodies_index', [], Response::HTTP_SEE_OTHER);
    }
}
