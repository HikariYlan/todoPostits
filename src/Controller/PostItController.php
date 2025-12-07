<?php

namespace App\Controller;

use App\Entity\PostIt;
use App\Enum\Status;
use App\Repository\PostItRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PostItController extends AbstractController
{
    #[Route('/cork_board', name: 'app_cork_board')]
    public function index(PostItRepository $postItRepository, UserRepository $userRepository): Response
    {
        if ($this->getUser()) {
            $user = $this->getUser();
        } else {
            return $this->redirectToRoute('app_login');
        }
        $userId = $userRepository->getIdFromCurrentUser($user->getUserIdentifier());
        $postIts = $postItRepository->getPostitsFromUser($userId);
        $pending = $toDo = $onGoing = $finished = [];
        foreach ($postIts as $postIt) {
            if (Status::PENDING == $postIt->getStatus()) {
                $pending[] = $postIt;
            }
            if (Status::TO_DO == $postIt->getStatus()) {
                $toDo[] = $postIt;
            }
            if (Status::ON_GOING == $postIt->getStatus()) {
                $onGoing[] = $postIt;
            }
            if (Status::FINISHED == $postIt->getStatus()) {
                $finished[] = $postIt;
            }
        }

        return $this->render('post_it/index.html.twig', [
            'pending' => $pending,
            'toDo' => $toDo,
            'onGoing' => $onGoing,
            'finished' => $finished,
        ]);
    }

    #[Route('/post_it/random', name: 'app_post_it_random', methods: 'GET')]
    public function randomPostIt(PostItRepository $postItRepository, UserRepository $userRepository): Response
    {
        $unfinishedPostIts = $postItRepository->getUnfinishedPostitsFromUser(
            $userRepository->getIdFromCurrentUser(
                $this->getUser()->getUserIdentifier()
            )
        );
        $randomPostIt = $unfinishedPostIts ? $unfinishedPostIts[array_rand($unfinishedPostIts)]->getId() : 0;
        return $this->redirectToRoute('app_post_it_details', [
            'id' => $randomPostIt,
        ]);
    }

    #[Route('/post_it/{id}', name: 'app_post_it_details', requirements: ['id' => '\d+'], methods: 'GET')]
    public function showDetails(?PostIt $postIt): Response
    {
        return $this->render('post_it/show.html.twig', [
            'postIt' => $postIt,
        ]);
    }
}
