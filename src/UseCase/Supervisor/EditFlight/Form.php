<?php

declare(strict_types=1);

namespace App\UseCase\Supervisor\EditFlight;

use App\Entity\Role;
use App\UseCase\AdminRegistration\Command;
use Symfony\Component\Form\AbstractType;
use App\Repository\AirplaneRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Form extends AbstractType
{
    public function __construct(private AirplaneRepository $airplaneRepository)
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
            ->add('pointOfDeparture')
            ->add('arrivalPoint')
            ->add('airplane', ChoiceType::class, [
                'choices' => $options1
            ])
            ->add('departureTime')
            ->add('arrivalTime');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Command::class,
        ]);
    }
}
