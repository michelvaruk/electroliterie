<?php

namespace App\Form\CustomType;

use App\Entity\Product;
use App\Service\PriceTypeService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\BaseEntityAutocompleteType;

#[AsEntityAutocompleteField]
class ProductAutocompleteField extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => Product::class,
            'searchable_fields' => ['title'],
            'label' => 'Produit',
            'no_more_results_text' => 'Aucun résultat supplémentaire',
            'no_results_found_text' => 'Aucun résultat trouvé',
            'choice_label' => function (Product $product): string {
                return PriceTypeService::getRealPrice($product);
            }
        ]);
    }

    public function getParent(): string
    {
        return BaseEntityAutocompleteType::class;
    }
}