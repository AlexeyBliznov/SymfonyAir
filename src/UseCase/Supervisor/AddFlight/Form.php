<?php

declare(strict_types=1);

namespace App\UseCase\Supervisor\AddFlight;

use App\Repository\AirplaneRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Form extends AbstractType
{
    private AirplaneRepository $airplaneRepository;

    public function __construct(AirplaneRepository $airplaneRepository)
    {
        $this->airplaneRepository = $airplaneRepository;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $airplanes = $this->airplaneRepository->findAll();
        $options1 = [];
        foreach ($airplanes as $airplane) {
            $options1[$airplane->getModel()] = $airplane;
        }
        $builder
            ->add('departureTime', DateTimeType::class, [
                'input' => 'datetime_immutable',
            ])
            ->add('arrivalTime', DateTimeType::class, [
                'input' => 'datetime_immutable',
            ])
            ->add('from')
            ->add('to')
            ->add('airplane', ChoiceType::class, [
                'choices' => $options1
            ])
            ->add('price');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Command::class,
        ]);
    }
}
