<?php

namespace App\Form;

use App\Entity\Family;
use App\Entity\FamilyFeature;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FamilyFeatureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type')
            ->add('families', EntityType::class, [
                'class' => Family::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FamilyFeature::class,
        ]);
    }
}
