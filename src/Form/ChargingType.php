<?php

namespace App\Form;

use App\Entity\Car;
use App\Entity\Charging;
use App\Entity\Station;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChargingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('car', EntityType::class, [
                'class' => Car::class,
                'choice_label' => 'displayName',
            ])
            ->add('station', EntityType::class, [
                'class' => Station::class,
                'choice_label' => 'name',
            ])
            ->add('date', DateType::class)
            ->add('totalKwh', NumberType::class, [
                'html5' => true,
                'scale' => 2,
                'attr' => [
                    'min' => 1,
                    'max' => 200,
                    'step' => '0.01',
                ]
            ])
            ->add('duration', TimeType::class, [
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Charging::class,
        ]);
    }
}
