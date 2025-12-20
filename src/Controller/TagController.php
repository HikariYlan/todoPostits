<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Entity\User;
use App\Form\TagFormType;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TagController extends AbstractController
{
    #[Route('/tags', name: 'app_tag')]
    public function index(TagRepository $repository): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->render('tag/index.html.twig', [
            'tags' => $repository->getOwnedTags($user->getId()),
        ]);
    }

    #[Route('/tag/new', name: 'app_tag_create')]
    public function createTag(Request $request, EntityManagerInterface $manager): Response
    {
        $tag = new Tag();
        $tag->setOwner($this->getUser());

        $form = $this->createForm(TagFormType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($tag);
            $manager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('tag/_form.html.twig', [
            'form' => $form,
        ]);
    }
}
