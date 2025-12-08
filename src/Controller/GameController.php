<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\SteamAPI;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

final class GameController extends AbstractController
{
    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    #[Route('/games', name: 'app_games')]
    public function index(SteamAPI $steamAPI, UserRepository $userRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $currentUserSteamID = $userRepository->getUserSteamIDFromUsername($this->getUser()->getUserIdentifier()) ?? 'not_found';
        if ('not_found' != $currentUserSteamID) {
            $games = $steamAPI->getUserGames($currentUserSteamID);
            $summary = $steamAPI->getUserSummary($currentUserSteamID);
            $avatar = $summary['avatarmedium'];
            $username = $summary['personaname'];
        } else {
            $games = $avatar = $username = null;
        }

        return $this->render('game/index.html.twig', [
            'games' => $games,
            'avatar' => $avatar,
            'username' => $username,
        ]);
    }

    #[Route('/games/random', name: 'app_games_random')]
    public function randomGame(SteamAPI $steamAPI, UserRepository $userRepository): Response
    {
        $currentUserSteamID = $userRepository->getUserSteamIDFromUsername($this->getUser()->getUserIdentifier()) ?? 'not_found';
        $games = $steamAPI->getUserGames($currentUserSteamID);
        $randomGame = $games[array_rand($games)];

        return $this->render('game/show.html.twig', [
            'game' => $randomGame,
        ]);
    }
}
