<?php

namespace App\Form\Feature;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TechnologyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('technology', ChoiceType::class, [
                'label' => 'Technologie',
                'choices' => [
                    'Mousse' => 'mousses',
                    'Mousse alvéolaire' => 'mousse alvéolaire',
                    'Ressorts' => 'ressorts',
                    'Ressorts ensachés' => 'ressorts ensaches',
                ],
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'inherit_data' => true,
        ]);
    }
}