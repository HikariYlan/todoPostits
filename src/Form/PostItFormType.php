<?php

namespace App\Form;

use App\Entity\PostIt;
use App\Entity\Tag;
use App\Entity\User;
use App\Enum\Status;
use App\Repository\TagRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostItFormType extends AbstractType
{
    public function __construct(
        private readonly Security $security,
        private readonly TagRepository $tagRepository,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var User $user */
        $user = $this->security->getUser();

        $builder
            ->add('title')
            ->add('description', TextareaType::class, [
                'required' => false,
            ])
            ->add('dueDate', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'input' => 'datetime',
                'html5' => true,
                'attr' => [
                    'placeholder' => 'dd/mm/yyyy',
                ],
            ])
            ->add('status', EnumType::class, [
                'class' => Status::class,
                'choice_label' => fn (Status $status) => str_replace('_', ' ', $status->value),
                'choices' => [
                    'PENDING' => Status::PENDING,
                    'TO DO' => Status::TO_DO,
                    'ON GOING' => Status::ON_GOING,
                    'FINISHED' => Status::FINISHED,
                ],
            ])
            ->add('tags', EntityType::class, [
                'class' => Tag::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'choices' => $this->tagRepository->getOwnedTags($user->getId()),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PostIt::class,
        ]);
    }
}
