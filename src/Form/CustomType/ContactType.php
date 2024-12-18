<?php

namespace App\Form\CustomType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new Length(['max' => 255, 'maxMessage' => 'Le nom renseigné est trop long.']),
                ],
                'attr' => [
                    'class' => 'capitalize'
                ],
                'required' => false,
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'constraints' => [new Length(['max' => 255, 'maxMessage' => 'Le prénom renseigné est trop long.'])],
                'attr' => [
                    'class' => 'capitalize'
                ],
                'required' => false,
            ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone',
                'constraints' => [
                    new Length(['max' => 18, 'maxMessage' => 'Le nom renseigné est trop long.']),
                    new Regex('/^(?:(?:\+|00)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}/', 'Le numéro de téléphone renseigné n\'est pas valide.'),
                ],
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'inherit_data' => true,
        ]);
    }
}
