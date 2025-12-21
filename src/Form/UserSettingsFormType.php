<?php

namespace App\Form;

use App\Entity\User;
use App\Enum\Gender;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class UserSettingsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextareaType::class, [
                'required' => false,
            ])
            ->add('location', TextType::class, [
                'required' => false,
            ])
            ->add('gender', EnumType::class, [
                'class' => Gender::class,
                'choice_label' => fn ($choice) => $choice->value,
                'choices' => [
                    'Man' => Gender::MAN,
                    'Woman' => Gender::WOMAN,
                    'Other' => Gender::OTHER,
                ],
                'placeholder' => 'Not specified',
                'required' => false,
            ])
            ->add('pronouns', TextType::class, [
                'required' => false,
            ])
            ->add('avatar', FileType::class, [
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024m',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image (JPEG, PNG, GIF, or WebP)',
                    ]),
                ],
            ])
            ->add('requiredTasks', IntegerType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
