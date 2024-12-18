<?php

namespace App\Form;

use App\Entity\ProductPictures;
use App\Form\CustomType\ImageFileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProductPicturesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pictureFile', ImageFileType::class, [
                'label' => false,
                'label_name' => 'Image',
                'field_name' => 'pictureFile',
            ])
            ->add('alt', TextType::class, [
                'label' => 'Titre de l\'image',
                "required" => true
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductPictures::class,
        ]);
    }
}
