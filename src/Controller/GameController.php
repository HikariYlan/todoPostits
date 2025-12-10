<?php

namespace App\Controller;

use App\Repository\PostItRepository;
use App\Repository\UserRepository;
use App\Service\SteamAPI;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GameController extends AbstractController
{
    #[Route('/games', name: 'app_games')]
    public function index(SteamAPI $steamAPI, UserRepository $userRepository, PostItRepository $postItRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $user = $this->getUser();
        $requiredTasks = $user->getRequiredTasks();

        $currentUserSteamID = $userRepository->getUserSteamIDFromUsername($user->getUserIdentifier()) ?? 'not_found';
        if ('not_found' != $currentUserSteamID) {
            $games = $steamAPI->getUserGames($currentUserSteamID);
            $summary = $steamAPI->getUserSummary($currentUserSteamID);
            $avatar = $summary['avatarmedium'];
            $username = $summary['personaname'];
        } else {
            $games = $avatar = $username = null;
        }

        $tasks_finished = $postItRepository->getFinishedPostitsFromUser($user->getId());

        return $this->render('game/index.html.twig', [
            'games' => $games,
            'avatar' => $avatar,
            'username' => $username,
            'task_finished' => $tasks_finished,
            'tasks_required' => $requiredTasks,
        ]);
    }

    #[Route('/games/random', name: 'app_games_random')]
    public function randomGame(SteamAPI $steamAPI, UserRepository $userRepository, PostItRepository $postItRepository): Response
    {
        $user = $this->getUser();

        $tasks_finished = $postItRepository->getFinishedPostitsFromUser($user->getId());
        $finished = false;
        foreach ($tasks_finished as $postIt) {
            if ($postIt->getFinishDate()->format('Y-m-d') == (new \DateTime('now'))->format('Y-m-d')) {
                $finished = true;
                break;
            }
        }

        if (!$finished) {
            return $this->redirectToRoute('app_post_it_random');
        }

        $currentUserSteamID = $userRepository->getUserSteamIDFromUsername($user->getUserIdentifier()) ?? 'not_found';
        $games = $steamAPI->getUserGames($currentUserSteamID);
        $randomGame = $games[array_rand($games)];

        return $this->render('game/show.html.twig', [
            'game' => $randomGame,
        ]);
    }
}
