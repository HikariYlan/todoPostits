<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserSettingsType;
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
}
