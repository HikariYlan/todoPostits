<?php

namespace App\Form;

use App\Entity\PostIt;
use App\Enum\Status;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostItType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', options: [
                'label' => 'Title',
            ])
            ->add('description', options: [
                'label' => 'Description',
            ])
            ->add('dueDate', DateTimeType::class, [
                'required' => false,
                'label' => 'Due Date',
            ])
            ->add('status', EnumType::class, [
                'class' => Status::class,
                'label' => 'Status',
                'choice_label' => fn (Status $status) => str_replace('_', ' ', $status->value),
                'choices' => [
                    'PENDING' => Status::PENDING,
                    'TO DO' => Status::TO_DO,
                    'ON GOING' => Status::ON_GOING,
                    'FINISHED' => Status::FINISHED,
                ],
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
