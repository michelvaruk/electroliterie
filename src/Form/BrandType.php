<?php

namespace App\Form;

use App\Entity\Brand;
use App\Form\CustomType\ImageFileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class BrandType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Nom de la marque',
                'row_attr' => [
                    'class' => 'half'
                ],
            ])
            ->add('imageFile', ImageFileType::class, [
                'label' => false,
                'row_attr' => [
                    'class' => 'half'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Brand::class,
        ]);
    }
}
