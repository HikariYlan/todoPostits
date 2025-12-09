<?php

namespace App\Controller;

use App\Service\SteamOpenIDService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SteamController extends AbstractController
{
    #[Route('/steam/auth', name: 'app_steam_auth')]
    public function auth(UrlGeneratorInterface $urlGenerator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $realm = $urlGenerator->generate('app_home', [], UrlGeneratorInterface::ABSOLUTE_URL);
        $returnTo = $urlGenerator->generate('app_steam_callback', [], UrlGeneratorInterface::ABSOLUTE_URL);

        $openId = new SteamOpenIDService($realm, $returnTo);

        return $this->redirect($openId->getAuthUrl());
    }

    #[Route('/steam/callback', name: 'app_steam_callback')]
    public function callback(
        Request $request,
        UrlGeneratorInterface $urlGenerator,
        EntityManagerInterface $entityManager,
    ): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $realm = $urlGenerator->generate('app_home', [], UrlGeneratorInterface::ABSOLUTE_URL);
        $returnTo = $urlGenerator->generate('app_steam_callback', [], UrlGeneratorInterface::ABSOLUTE_URL);

        $openId = new SteamOpenIDService($realm, $returnTo);
        $steamId = $openId->validate($request);

        if (null === $steamId) {
            return $this->redirectToRoute('app_sticky_board');
        }

        $user = $this->getUser();
        $user->setSteamID($steamId);
        $entityManager->flush();

        return $this->redirectToRoute('app_games');
    }

    #[Route('/steam/unlink', name: 'app_steam_unlink')]
    public function unlink(EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();
        $user->setSteamID(null);
        $entityManager->flush();

        return $this->redirectToRoute('app_games');
    }
}
