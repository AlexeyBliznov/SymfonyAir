<?php

declare(strict_types=1);

namespace App\UseCase\Supervisor\AddEmployee;

use App\Entity\Role;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Form extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('name')
            ->add('role', ChoiceType::class, [
                'choices'  => [
                    'Gate manager' => Role::gateManager(),
                    'Check in manager' => Role::checkInManager(),
                    'Supervisor' => Role::supervisor(),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Command::class,
        ]);
    }
}
