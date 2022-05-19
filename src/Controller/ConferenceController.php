<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Repository\CommentaireRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ConferenceController extends AbstractController
{
    #[Route('/articles/{slug}', name: 'conference_show')]
    public function show(Request $request, Commentaire $commentaire, CommentaireRepository $commentaireRepository ): Response
    {
        $comment = new Commentaire();
        $form = $this->createForm(CommentFormType::class, $comment);

        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $commentaireRepository->getCommentPaginator($commentaire, $offset);

        return new Response($this->twig->render('articles/show.html.twig', [
            'comments' => $paginator,
            'previous' => $offset - CommentaireRepository::PAGINATOR,
            'next' => min(count($paginator), $offset + CommentaireRepository::PAGINATOR_PER_PAGE),
            'comment_form' => $form->createView(),
        ]));
    }
}