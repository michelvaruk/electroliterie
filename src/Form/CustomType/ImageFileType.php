<?php

namespace App\Form\CustomType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints\File;

class ImageFileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add($options['field_name'], VichImageType::class, [
                'label' => $options['label_name'],
                'required' => false,
                'allow_delete' => $options['allow_delete'],
                'delete_label' => 'Supprimer',
                'download_label' => false,
                'constraints' => new File([
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/png',
                        'image/gif',
                        'image/webp',
                        'image/svg+xml',
                        'image/tiff',
                        'image/bmp',
                    ],
                    'maxSize' => '2048k',
                    'maxSizeMessage' => 'Le fichier importé est trop volumineux - ({{ size }} {{ suffix }}). La taille maximale admise est : {{ limit }} {{ suffix }}.',
                    'mimeTypesMessage' => 'Merci de télécharger un fichier image ou photo valide.',
                ])
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'inherit_data' => true,
            'field_name' => 'imageFile',
            'allow_delete' => true,
            'label_name' => 'Photo de présentaion'
        ]);
    }
}