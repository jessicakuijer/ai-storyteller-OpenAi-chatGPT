<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class StoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('story', TextareaType::class, [
                'label' => 'Rajoute ici les éléments que tu souhaites pour que je puisse inventer une histoire :',
                'attr' => [
                    'placeholder' => 'Ex: un chat, un téléphone, une montre, un chateau, des paillettes...',
                    'rows' => 10,
                    ]
                ])
                ->add('currentCheckbox', HiddenType::class, [
                    'data' => '',
                ])
                ->add('alternativeStory', CheckboxType::class, [
                    'label'    => 'Histoire avec une leçon de morale ? ',
                    'required' => false,
                ])
                ->add('scaryStory', CheckboxType::class, [
                    'label'    => 'Histoire effrayante ? ',
                    'required' => false,
                ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'attr' => [
                    'hx-post' => '/',
                    'hx-target' => '#response'
                    ]
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
