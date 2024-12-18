<?php

namespace App\Form;

use App\Entity\Brand;
use App\Entity\Product;
use App\Form\CustomType\ImageFileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Form\CustomType\QuillTextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Validator\Constraints\File;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Nom du produit',
                'required' => false,
                'row_attr' => [
                    'class' => 'third'
                ]
            ])
            ->add('EAN', TextType::class, [
                'label' => 'Code EAN',
                "required" => false,
                'row_attr' => [
                    'class' => 'third'
                ]
            ])
            ->add('reference', TextType::class, [
                'label' => 'Référence',
                "required" => false,
                'row_attr' => [
                    'class' => 'third'
                ]
            ])
            ->add('brand', EntityType::class, [
                'label' => 'Marque',
                'class' => Brand::class,
                'choice_label' => function (Brand $brand): string {
                    return $brand->getTitle();
                },
                'row_attr' => [
                    'class' => 'third'
                ]
            ])
            ->add('description', QuillTextareaType::class, [
                'label' => 'Description du produit',
                'required' => true,
                'row_attr' => [
                    'class' => 'w-full'
                ]
            ])
            ->add('pictureFile', ImageFileType::class, [
                'label' => 'Image principale',
                'field_name' => 'pictureFile',
                'row_attr' => [
                    'class' => 'half'
                ]
            ])

            ->add('pdfFile', VichFileType::class, [
                'label' => 'Fiche du fabricant',
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'Supprimer',
                'download_label' => false,
                'constraints' => new File([
                    'mimeTypes' => [
                        'application/pdf',
                    ],
                    // 'mimeTypesMessage' => 'Merci de télécharger un fichier pdf valide.',
                    'maxSize' => '2048k',
                    'maxSizeMessage' => 'Le fichier importé est trop volumineux - ({{ size }} {{ suffix }}). La taille maximale admise est : {{ limit }} {{ suffix }}.',
                ]),
                'row_attr' => [
                    'class' => 'half'
                ]
            ])

            ->add('productPictures', CollectionType::class, [
                'label' => 'Autres images',
                'entry_type' => ProductPicturesType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'entry_options' => ['label' => false],
                'attr' => [
                    'data-controller' => 'form-collection'
                ],
                'row_attr' => [
                    'class' => 'w-full'
                ]
            ])
            ->add('feature', FeatureType::class, [
                'family' => $options['family'],
                'label' => 'Spécificités',
                'row_attr' => [
                    'class' => 'w-full'
                ],
                'attr' => [
                    'class' => 'flex flex-wrap justify-between'
                ]
            ])
            ->add('salesInfo', ProductSalesInformationType::class, [
                'label' => 'Conditions tarifaires',
                'row_attr' => [
                    'class' => 'w-full'
                ],
                'attr' => [
                    'class' => 'flex flex-wrap justify-between'
                ]
            ])
            ->add('selection', CheckboxType::class, [
                'label' => 'Inclure dans la sélection de produits de la page d\'accueil',
                'required' => false,
                'row_attr' => [
                    'class' => 'w-full'
                ]
            ])
            ->add('active', CheckboxType::class, [
                'label' => 'Activer ce produit',
                'required' => false,
                'row_attr' => [
                    'class' => 'w-full'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'family' => 'sans'
        ]);
    }
}
