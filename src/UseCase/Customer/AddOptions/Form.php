<?php

declare(strict_types=1);

namespace App\UseCase\Customer\AddOptions;

use App\Entity\Baggage;
use App\Repository\AirplaneRepository;
use App\Repository\OptionRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Form extends AbstractType
{
    private OptionRepository $optionRepository;

    public function __construct(OptionRepository $optionRepository)
    {
        $this->optionRepository = $optionRepository;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $options = $this->optionRepository->findAll();
        $options1 = [];
        foreach ($options as $option) {
            $options1[$option->getName() . $option->getDescription() . $option->getPrice()] = $option->getId();
        }
        array_unshift($options1, ['No options' => 0]);
        $builder
            ->add('options', ChoiceType::class, [
                'choices' => $options1
            ])
            ->add('baggage', ChoiceType::class, [
                'choices' => [
                    'Hand baggage' => new Baggage('HAND BAGGAGE'),
                    'Baggage' => new Baggage('BAGGAGE'),
                    'Pets and hand baggage' => new Baggage('PETS + HAND BAGGAGE'),
                    'Pets and baggage' => new Baggage('PETS + BAGGAGE')
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
