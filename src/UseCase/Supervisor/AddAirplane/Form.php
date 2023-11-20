<?php

declare(strict_types=1);

namespace App\UseCase\Supervisor\AddAirplane;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Form extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('productionYear', DateTimeType::class, [
                'input' => 'datetime_immutable',
            ])
            ->add('maintenanceSchedule', ChoiceType::class, [
                'choices' => [
                    '3 months' => new \DateInterval('P3M'),
                    '4 months' => new \DateInterval('P4M'),
                    '5 months' => new \DateInterval('P5M')
                ]
            ])
            ->add('nextMaintenance', DateTimeType::class, [
                'input' => 'datetime_immutable',
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Econom' => 'econom',
                    'Business' => 'business'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Command::class,
        ]);
    }
}