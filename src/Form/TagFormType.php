<?php

namespace App\Form;

use App\Entity\Tag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TagFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $colors = [
            'red' => '#ef4444',
            'orange' => '#f97316',
            'amber' => '#f59e0b',
            'yellow' => '#eab308',
            'lime' => '#84cc16',
            'green' => '#22c55e',
            'emerald' => '#10b981',
            'teal' => '#14b8a6',
            'cyan' => '#06b6d4',
            'sky' => '#0ea5e9',
            'blue' => '#3b82f6',
            'indigo' => '#6366f1',
            'violet' => '#8b5cf6',
            'purple' => '#a855f7',
            'fuchsia' => '#d946ef',
            'pink' => '#ec4899',
            'rose' => '#f43f5e',
            'slate' => '#64748b',
            'gray' => '#6b7280',
            'zinc' => '#71717a',
            'neutral' => '#737373',
            'stone' => '#78716c',
        ];

        $builder
            ->add('name')
            ->add('color', ChoiceType::class, [
                'choices' => [
                    'red' => 'red',
                    'orange' => 'orange',
                    'amber' => 'amber',
                    'yellow' => 'yellow',
                    'lime' => 'lime',
                    'green' => 'green',
                    'emerald' => 'emerald',
                    'teal' => 'teal',
                    'cyan' => 'cyan',
                    'sky' => 'sky',
                    'blue' => 'blue',
                    'indigo' => 'indigo',
                    'violet' => 'violet',
                    'purple' => 'purple',
                    'fuchsia' => 'fuchsia',
                    'pink' => 'pink',
                    'rose' => 'rose',
                    'slate' => 'slate',
                    'gray' => 'gray',
                    'zinc' => 'zinc',
                    'neutral' => 'neutral',
                    'stone' => 'stone',
                ],
                'choice_attr' => function ($choice, $key, $value) use ($colors) {
                    return ['style' => 'color: '.$colors[$value].'; font-weight: bold;'];
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tag::class,
        ]);
    }
}
