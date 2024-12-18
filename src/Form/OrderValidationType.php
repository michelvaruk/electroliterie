<?php

namespace App\Form;

use App\Entity\DeliveryMode;
use App\Entity\Order;
use App\Repository\DeliveryModeRepository;
use App\Service\DistanceService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderValidationType extends AbstractType
{
    public function __construct(
        private DeliveryModeRepository $deliveryModeRepository
    )
    {
        
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $distance = $options['distance'];
        $builder
            ->add('deliveryMode', EntityType::class, [
                'label' => 'Mode de livraison',
                'class' => DeliveryMode::class,
                'choices' => $this->deliveryModeRepository->findBy(['active' => true]),
                'row_attr' => [
                    'class' => 'space-y-4',
                ],
                'attr' => [
                    'class' => 'grid grid-cols-1 gap-4 md:grid-cols-2'
                ],
                'choice_label' => function (?DeliveryMode $deliveryMode) use ($distance) {
                    $price = DistanceService::computePrice($deliveryMode, $distance);
                    if(null === $price) {
                        $price = 'Indisponible pour cette adresse';
                    } elseif ($price > 0) {
                        $price .= ' €';
                    } else {
                        $price = 'Gratuit';
                    }
                    return $price . ' - ' . $deliveryMode->getTitle();
                },
                'label_attr' => [
                    'class' => 'text-xl font-semibold text-gray-900'
                ],
                'choice_value' => 'id',
                'choice_attr' => function (?DeliveryMode $deliveryMode) use ($distance) {
                    return [
                        'data-description' => $deliveryMode->getDescription(),
                        'title' => $deliveryMode->getTitle(),
                        'data-order-target' => 'choice',
                        'data-action' => 'input->order#update',
                        'data-value' => DistanceService::computePrice($deliveryMode, $distance)
                    ];
                },
                'expanded' => true,
                'multiple' => false,
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
            'distance' => 1000,
        ]);
    }
}