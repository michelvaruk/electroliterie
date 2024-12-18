<?php

namespace App\Form;

use App\Entity\Promotion;
use App\Form\CustomType\ImageFileType;
use App\Form\CustomType\QuillTextareaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PromotionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Nom de l\'opération',
                'row_attr' => [
                    'class' => 'two-thirds'
                ],
            ])
            ->add('odr', CheckboxType::class, [
                'label' => 'ODR',
                'required' => false,
                'row_attr' => [
                    'class' => 'w-full'
                ],
            ])
            ->add('description', QuillTextareaType::class, [
                'row_attr' => [
                    'class' => 'w-full'
                ],
            ])
            ->add('value', NumberType::class, [
                'label' => 'Valeur de la remise',
                'required' => false,
                'row_attr' => [
                    'class' => 'third'
                ],
            ])
            ->add('relative', CheckboxType::class, [
                'label' => 'En %',
                'required' => false,
                'row_attr' => [
                    'class' => 'w-full'
                ],
            ])
            ->add('startDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de départ de l\'opération',
                'row_attr' => [
                    'class' => 'third'
                ],
            ])
            ->add('endDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de fin de l\'opération',
                'row_attr' => [
                    'class' => 'third'
                ],
            ])
            ->add('pictureFile', ImageFileType::class, [
                'label' => 'Image de mise en avant',
                'field_name' => 'pictureFile',
                'row_attr' => [
                    'class' => 'half'
                ],
            ])
            ->add('productPromotions', CollectionType::class, [
                'label' => 'Produits concernés',
                'entry_type' => ProductPromotionType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'entry_options' => ['label' => false],
                'attr' => [
                    'data-controller' => 'form-collection'
                ],
                'row_attr' => [
                    'class' => 'w-full'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Promotion::class,
        ]);
    }
}
