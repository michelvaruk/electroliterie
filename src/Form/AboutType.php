<?php

namespace App\Form;

use App\Entity\About;
use App\Form\CustomType\ImageFileType;
use App\Form\CustomType\QuillTextareaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AboutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', QuillTextareaType::class, [
                'label' => 'Description du produit',
                'required' => true,
            ])
            ->add('pictureFile', ImageFileType::class, [
                'field_name' => 'pictureFile',
                'label' => false,
                'row_attr' => [
                    "class" => "half"
                ]
            ]);

        if($options['id'] === 1) {
            $builder->add('hours', TextType::class, [
                'label' => 'Horaires d\'ouverture',
                'row_attr' => [
                    "class" => "half"
                ]
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => About::class,
            'id' => 0
        ]);
    }
}