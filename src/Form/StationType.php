<?php

namespace App\Form;

use App\Entity\Station;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'minlength' => 2,
                    'maxlength' => 255,
                ],
            ])
            ->add('outputPower', NumberType::class, [
                'html5' => true,
                'scale' => 2,
                'attr' => [
                    'min' => 1,
                    'max' => 500,
                    'step' => '0.1',
                ]
            ])
            ->add('electricityPrice', NumberType::class, [
                'html5' => true,
                'scale' => 2,
                'attr' => [
                    'min' => 0.01,
                    'max' => 2,
                    'step' => '0.01',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Station::class,
        ]);
    }
}
