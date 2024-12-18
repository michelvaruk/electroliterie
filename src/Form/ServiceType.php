<?php

namespace App\Form;

use App\Entity\Service;
use App\Form\CustomType\ImageFileType;
use App\Form\CustomType\QuillTextareaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Nom du service',
                'row_attr' => [
                    'class' => 'third'
                ]
            ])
            ->add('description', QuillTextareaType::class, [
                'label' => 'Description du service',
                'required' => true,
                'row_attr' => [
                    'class' => 'w-full'
                ]
            ])
            ->add('pictureFile', ImageFileType::class, [
                'field_name' => 'pictureFile',
                'label' => false,
                'row_attr' => [
                    'class' => 'half'
                ]
            ])
            ->add('active', CheckboxType::class, [
                'label' => 'Afficher ce service sur le site',
                'required' => false,
                'row_attr' => [
                    'class' => 'half'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}