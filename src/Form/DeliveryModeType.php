<?php

namespace App\Form;

use App\Entity\DeliveryMode;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeliveryModeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Nom',
                'row_attr' => [
                    'class' => 'w-full'
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'row_attr' => [
                    'class' => 'w-full'
                ],
            ])
            ->add('price', NumberType::class, [
                'label' => 'Prix (en €)',
                'required' => false,
                'row_attr' => [
                    'class' => 'third'
                ],
            ])
            ->add('area', NumberType::class, [
                'label' => 'Zone de livraison par défaut (en km)',
                'required' => false,
                'row_attr' => [
                    'class' => 'third'
                ],
            ])
            ->add('offsidePrice', NumberType::class, [
                'label' => 'Suplément au delà de la zone... (en €)',
                'required' => false,
                'row_attr' => [
                    'class' => 'third'
                ],
            ])
            ->add('offsideArea', NumberType::class, [
                'label' => '...par tranche de... (en km)',
                'required' => false,
                'row_attr' => [
                    'class' => 'third'
                ],
            ])
            ->add('maxDistance', NumberType::class, [
                'label' => 'Distance maximale (en km)',
                'required' => false,
                'row_attr' => [
                    'class' => 'third'
                ],
            ])

            ->add('active', CheckboxType::class, [
                'label' => 'Activer ce mode de livraison',
                'required' => false,
                'row_attr' => [
                    'class' => 'third'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DeliveryMode::class,
        ]);
    }
}
