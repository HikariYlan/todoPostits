<?php

namespace App\Controller;

use App\Entity\PostIt;
use App\Enum\Status;
use App\Form\PostItType;
use App\Repository\PostItRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PostItController extends AbstractController
{
    #[Route('/sticky_board', name: 'app_sticky_board')]
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

    #[Route('/post_it/new', name: 'app_postit_create', methods: ['GET', 'POST'])]
    public function createPostIt(Request $request, EntityManagerInterface $entityManager): Response
    {
        $postIt = new PostIt();
        $postIt->setOwner($this->getUser());
        $form = $this->createForm(PostItType::class, $postIt);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($postIt);
            if (Status::FINISHED === $postIt->getStatus()) {
                $postIt->setFinishDate(new \DateTime());
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_sticky_board');
        }

        return $this->render('post_it/_form.html.twig', [
            'form' => $form,
            'submit_label' => 'Stick it to your sticky notes board',
        ]);
    }

    #[Route('/post_it/{id}/edit', name: 'app_postit_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function editPostIt(PostIt $postIt, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PostItType::class, $postIt);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($postIt);
            if (Status::FINISHED === $postIt->getStatus()) {
                $postIt->setFinishDate(new \DateTime());
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_sticky_board');
        }

        return $this->render('post_it/_form.html.twig', [
            'form' => $form,
            'submit_label' => 'Edit your Post-It',
        ]);
    }

    #[Route('/post_it/{id}/update-status', name: 'app_postit_update_status', requirements: ['id' => '\d+'], methods: ['PATCH'])]
    public function updateStatus(PostIt $postIt, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        if ($postIt->getOwner() !== $this->getUser()) {
            return new JsonResponse(['error' => 'Unauthorized'], Response::HTTP_FORBIDDEN);
        }

        $data = json_decode($request->getContent(), true);

        if (!isset($data['status'])) {
            return new JsonResponse(['error' => 'Status is required'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $statusEnum = Status::from($data['status']);
        } catch (\ValueError $e) {
            return new JsonResponse(['error' => 'Invalid status value'], Response::HTTP_BAD_REQUEST);
        }

        $postIt->setStatus($statusEnum);

        if (Status::FINISHED === $statusEnum && !$postIt->getFinishDate()) {
            $postIt->setFinishDate(new \DateTime());
        }

        if (Status::FINISHED !== $statusEnum && $postIt->getFinishDate()) {
            $postIt->setFinishDate(null);
        }

        $entityManager->flush();

        return new JsonResponse([
            'success' => true,
            'postIt' => [
                'id' => $postIt->getId(),
                'status' => $postIt->getStatus()->value,
                'finishDate' => $postIt->getFinishDate()?->format('Y-m-d H:i:s'),
            ],
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
