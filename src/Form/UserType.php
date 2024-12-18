<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    private $security;
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'row_attr' => [
                    'class' => 'half'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'row_attr' => [
                    'class' => 'half'
                ]
            ]);
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $builder
                ->add('email', EmailType::class, [
                    'label' => 'Email',
                    'row_attr' => [
                        'class' => 'half'
                    ],
                ])
                ->add('roles', ChoiceType::class, [
                    'choices' => [
                        'administrateur' => 'ROLE_ADMIN',
                        'éditeur' => 'ROLE_EDITOR'
                    ],
                    'row_attr' => [
                        'class' => 'half'
                    ],
                    'expanded' => true,
                    'multiple' => true,
                    'label' => 'Accès'
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
