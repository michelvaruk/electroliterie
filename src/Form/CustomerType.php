<?php

namespace App\Form;

use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom*',
                'row_attr' => [
                    'class' => 'firstname',
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom*',
                'row_attr' => [
                    'class' => 'lastname',
                ],
            ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone',
                'attr' => [
                    'data-action' => 'input->international-phone#change',
                    'data-international-phone-target' => 'input',
                    'autocomplete' => 'tel'
                ],
                'row_attr' => [
                    'class' => 'phone',
                    // 'data-controller' => 'international-phone',
                ],
                'required' => false,
            ])
            ->add('iso2', HiddenType::class, [
                'attr' => [
                    'data-international-phone-target' => 'final'
                ],
            ])
            ->add('formattedPhone', HiddenType::class, [
                'attr' => [
                    'data-international-phone-target' => 'formatted'
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse email*',
                'row_attr' => [
                    'class' => 'email',
                ],
            ]);
        if($options['is_new']) {
            $builder
                ->add('password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'invalid_message' => 'Les mots de passe renseignés ne correspondent pas.',
                    'required' => true,
                    'first_options'  => [
                        'label' => 'Mot de passe',
                        'constraints' => [
                            new Length([
                                'min' => 8,
                                'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                                // max length allowed by Symfony for security reasons
                                'max' => 4096,
                            ]),
                            new Regex([
                                'pattern' => '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)[A-Za-z0-9@,.?!\/\-_]+$/i',
                                'message' => 'Votre mot de passe doit contenir au moins une majuscule, une minuscule, et un chiffre',
                            ])
    
                        ],
                    ],
                    'second_options' => [
                        'label' => 'Confirmation du mot de passe',
                    ],
                ])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
            'is_new' => false,
        ]);
    }
}
