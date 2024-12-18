<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\ProductSalesInformation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductSalesInformationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sellingPrice', NumberType::class, [
                'label' => 'Prix de vente (TTC)',
                'attr' => [
                    'class' => 'priceInput'
                ],
                'row_attr' => [
                    'class' => 'third'
                ],
            ])
            ->add('purchasePrice', NumberType::class, [
                'label' => 'Prix d\'achat (HT)',
                'attr' => [
                    'class' => 'priceInput'
                ],
                'row_attr' => [
                    'class' => 'third'
                ],
            ])
            ->add('ecoContribution', NumberType::class, [
                'label' => 'dont éco-contribution (TTC)',
                'required' => false,
                'attr' => [
                    'class' => 'priceInput'
                ],
                'row_attr' => [
                    'class' => 'third'
                ],
            ])
            ->add('discountPrice', NumberType::class, [
                'label' => 'Prix Must Ménager (TTC)',
                'required' => false,
                'attr' => [
                    'class' => 'priceInput'
                ],
                'row_attr' => [
                    'class' => 'third'
                ],
            ])
            ->add('stock', NumberType::class, [
                'label' => 'Quantité en stock',
                'attr' => [
                    'class' => 'priceInput'
                ],
                'row_attr' => [
                    'class' => 'third'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductSalesInformation::class,
        ]);
    }
}
