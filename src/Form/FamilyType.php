<?php

namespace App\Form;

use App\Entity\Family;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FamilyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Nom de la famille',
                'row_attr' => [
                    'class' => 'w-full'
                ]
            ])
            ->add('features', ChoiceType::class, [
                'label' => 'Spécificités',
                'choices' => [
                    'Technologie' => 'technology',
                    'Accueil' => 'cover',
                    'Type de sommier' => 'type',
                    'Couleur' => 'colorName',
                    'Largeur' => 'width',
                    'Longueur' => 'length',
                    'Hauteur' => 'height',
                    'Forme des pieds' => 'feetShape',
                    'Maintien' => 'support'
                ],
                'choice_label' => function($choice, string $key) {
                    return ucfirst($key);
                },
                'row_attr' => [
                    'class' => 'two-thirds'
                ],
                'attr' => [
                    'class' => 'flex flex-wrap justify-between'
                ],
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('coef', NumberType::class, [
                'label' => 'Coefficient plancher',
                'row_attr' => [
                    'class' => 'half'
                ],
            ])
            ->add('parent', EntityType::class, [
                'class' => Family::class,
                'choice_label' => 'title',
                'required' => false,
                'row_attr' => [
                    'class' => 'half'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Family::class,
        ]);
    }
}
