<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\ProductPromotion;
use App\Form\CustomType\ProductAutocompleteField;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductPromotionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('product', ProductAutocompleteField::class)
            ->add('value', NumberType::class, [
                'label' =>'Valeur de la remise produit',
                'required' => false
            ])
            ->add('relative', CheckboxType::class, [
                'label' => 'en %',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductPromotion::class,
        ]);
    }
}
