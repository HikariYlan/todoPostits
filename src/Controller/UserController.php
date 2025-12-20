<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserSettingsType;
use App\Service\SteamAPI;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/settings', name: 'app_user_settings')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $form = $this->createForm(UserSettingsType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile|null $avatarFile */
            $avatarFile = $form->get('avatar')->getData();

            if ($avatarFile) {
                $avatarContent = file_get_contents($avatarFile->getPathname());
                $user->setAvatar($avatarContent);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_sticky_board');
        }

        return $this->render('user/index.html.twig', [
            'form' => $form,
            'submit_label' => 'Update your profile',
        ]);
    }

    #[Route('/user/{id}/avatar/delete', name: 'app_user_avatar_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(EntityManagerInterface $manager, Request $request): Response
    {/** @var User $user */
        $user = $this->getUser();
        $submittedToken = $request->getPayload()->get('token');
        if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {
            $user->setAvatar(null);
            $manager->flush();
        }

        return $this->redirectToRoute('app_user_settings');
    }

    #[Route('/user/{id}/avatar', name: 'app_user_avatar')]
    public function avatar(User $user): Response
    {
        if (!$user->getAvatar()) {
            return new Response();
        }

        $avatarData = $user->getAvatar();

        if (is_resource($avatarData)) {
            $avatarData = stream_get_contents($avatarData);
        }

        $file_info = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_buffer($file_info, $avatarData);
        finfo_close($file_info);

        $response = new Response($avatarData);
        $response->headers->set('Content-Type', $mimeType ?: 'image/jpeg');
        $response->headers->set('Cache-Control', 'public, max-age=86400');

        return $response;
    }

    #[Route('/user/{id}/steam_avatar', name: 'app_user_steam_avatar')]
    public function steamAvatar(User $user, SteamAPI $steamAPI): Response
    {
        if (!$user->getSteamID()) {
            throw $this->createNotFoundException('User has no Steam ID');
        }

        $steamData = $steamAPI->getUserSummary($user->getSteamID());
        $avatarUrl = $steamData['avatarmedium'] ?? null;
        if (!$avatarUrl) {
            return new Response();
        }

        return $this->redirect($avatarUrl);
    }
}
