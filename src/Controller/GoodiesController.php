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
    //fonction qui permet d'afficher tout les goodies//
    #[Route('/', name: 'goodies_index', methods: ['GET'])]
    public function index(GoodiesRepository $goodiesRepository): Response
    {
        // FindAll récupère tous les objets de la base de données //
        return $this->render('goodies/index.html.twig', [
            'goodies' => $goodiesRepository->findAll(),
        ]);
    }

    // fonction qui permet d'ajouter un nouvel goodies //
    #[Route('/new', name: 'goodies_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UploadTool $uploadTool): Response
    {
        // crée un nouvel object //
        $goody = new Goodies();
        // appeler doctrine pour créer un form //
        $form = $this->createForm(GoodiesType::class, $goody);
        // demande de traitement de la saisi du form  //
        $form->handleRequest($request);

        // si le form est soumi et qu'il est valide  //
        if ($form->isSubmitted() && $form->isValid()) {
            // recupérer les images //
            $imageFile = $form->get('image')->getData();
            // si il y a une image on la traite //
            if ($imageFile) {
                $finalFileName = $uploadTool->upload($imageFile);

                $goody->setImage($finalFileName);
            }
            // indiquer a entityManager que cette entity devra être enregistrer  //
            $entityManager->persist($goody);
            // enregistrement de l'entity dans la BDD //
            $entityManager->flush();

            // redirection de la page ver la page ci-dessous //
            return $this->redirectToRoute('goodies_index', [], Response::HTTP_SEE_OTHER);
        }

        // création de la view du form affiché sur la page indiqué au render //
        return $this->renderForm('goodies/new.html.twig', [
            'goody' => $goody,
            'form' => $form,
        ]);
    }

    // fonction qui permet d'afficher un goodies grâce a son ID  //
    #[Route('/{id}', name: 'goodies_show', methods: ['GET'])]
    public function show(Goodies $goody): Response
    {
        return $this->render('goodies/show.html.twig', [
            'goody' => $goody,
        ]);
    }

    // fonction qui permet de modifier un goodies grâce a son ID  //
    #[Route('/{id}/edit', name: 'goodies_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Goodies $goody, EntityManagerInterface $entityManager, UploadTool $uploadTool): Response
    {
        // Doctrine crée un form selon le goodie à modifier //
        $form = $this->createForm(GoodiesType::class, $goody);
        // traitement de la saisie du form //
        $form->handleRequest($request);

        // si le form est soumi et qu'il est valide  //
        if ($form->isSubmitted() && $form->isValid()) {
            // recupérer les images
            $imageFile = $form->get('image')->getData();
            //si il y a une image on la traite
            if ($imageFile) {
            
                $oldFile = $goody->getImage();
    
                $finalFileName = $uploadTool->upload($imageFile, $oldFile);

                $goody->setImage($finalFileName);
            }

            // enregistrement de l'entity dans la BDD //
            $entityManager->flush();

            // redirection de la page ver la page ci-dessous //
            return $this->redirectToRoute('goodies_index', [], Response::HTTP_SEE_OTHER);
        }

        // modification de la view du form affiché sur la page indiqué au render //
        return $this->renderForm('goodies/edit.html.twig', [
            'goody' => $goody,
            'form' => $form,
        ]);
    }

    // fonction qui permet de supprimer un goodies grâce a son ID  //
    #[Route('/{id}', name: 'goodies_delete', methods: ['POST'])]
    public function delete(Request $request, Goodies $goody, EntityManagerInterface $entityManager, UploadTool $uploadTool): Response
    {
        // vérifie si le token est valide //
        if ($this->isCsrfTokenValid('delete'.$goody->getId(), $request->request->get('_token'))) {

            // supprimé l'image de la BDD //
            $uploadTool->delete($goody->getImage());
            // indiquer a entityManager que cette entity devra être supprimé  //
            $entityManager->remove($goody);
            // supprimé l'entity de la BDD //
            $entityManager->flush();
        }

        // redirection de la page ver la page ci-dessous //
        return $this->redirectToRoute('goodies_index', [], Response::HTTP_SEE_OTHER);
    }
}
