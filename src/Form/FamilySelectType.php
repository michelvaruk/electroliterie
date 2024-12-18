<?php

namespace App\Form;

use App\Entity\Product;
use App\Repository\FamilyRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class FamilySelectType extends AbstractType
{
    public function __construct(private FamilyRepository $family)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        $builder
            ->add('familyName', ChoiceType::class, [
                'label' => 'Dans quelle famille souhaitez-vous enregistrer le nouveau produit ?',
                'choices' => $this->family->findAll(),
                'choice_label' => function($choice) {
                    return ucfirst($choice->getTitle());
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
