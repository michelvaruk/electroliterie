<?php

namespace App\Form;

use App\Entity\Adress;
use App\Form\CustomType\ContactType;
use Doctrine\DBAL\Types\FloatType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class AdressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fetch', TextType::class, [
                'mapped' => false,
                'label' => 'Adresse*',
                'attr' => [
                    'data-action' => 'input->address#fetch',
                    'data-address-target' => 'fetch'
                ]
            ])
            ->add('adress', HiddenType::class, [
                'label' => 'Adresse*',
                'constraints' => [new Length(['max' => 255, 'maxMessage' => 'L\'adresse saisie est beaucoup trop longue.'])],
                'attr' => [
                    'data-address-target' => 'addressInput'
                ]
            ])
            ->add('adressComplement', TextType::class, [
                'label' => 'Complément d\'adresse',
                'required' => false,
                'constraints' => [new Length(['max' => 255, 'maxMessage' => 'Le complément d\'adresse saisi est beaucoup trop long.'])],
            ])
            ->add('zipCode', HiddenType::class, [
                'attr' => [
                    'data-address-target' => 'zipCode'
                ]
            ])
            ->add('city', HiddenType::class, [
                'attr' => [
                    'data-address-target' => 'city'
                ]
            ])
            ->add('contact', ContactType::class, [
                'label' => 'Contact pour la livraison',
            ])
            ->add('information', TextareaType::class, [
                'label' => 'Informations complémentaires pour la livraison',
                'required' => false,
            ])
            ->add('lat', HiddenType::class, [
                'attr' => [
                    'data-address-target' => 'lat',
                ]
            ])
            ->add('longitude', HiddenType::class, [
                'attr' => [
                    'data-address-target' => 'longitude',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adress::class,
        ]);
    }
}
