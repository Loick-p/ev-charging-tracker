<?php

namespace App\Form;

use App\Entity\Car;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('brand', TextType::class, [
                'attr' => [
                    'minlength' => 2,
                    'maxlength' => 255,
                ],
            ])
            ->add('model', TextType::class, [
                'attr' => [
                    'minlength' => 2,
                    'maxlength' => 255,
                ],
            ])
            ->add('year', IntegerType::class, [
                'attr' => [
                    'min' => 2000,
                    'max' => (int) date('Y') + 1,
                ]
            ])
            ->add('range', IntegerType::class, [
                'attr' => [
                    'min' => 1,
                    'max' => 1000,
                ]
            ])
            ->add('batteryCapacity', NumberType::class, [
                'html5' => true,
                'scale' => 2,
                'attr' => [
                    'min' => 1,
                    'max' => 200,
                    'step' => '0.01',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
        ]);
    }
}
