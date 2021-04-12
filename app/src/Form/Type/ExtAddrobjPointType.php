<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;

class ExtAddrobjPointType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('latitude', NumberType::class, [
                'label' => false,
                'scale' => 11,
                'attr' => [
                    'required' => true,
                    'placeholder' => 'Широта'
                ],
                'row_attr' => [
                    'class' => 'coord'
                ]
            ])
            ->add('longitude', NumberType::class, [
                'label' => false,
                'scale' => 11,
                'attr' => [
                    'required' => true,
                    'placeholder' => 'Долгота'
                ],
                'row_attr' => [
                    'class' => 'coord'
                ]
            ])
        ;
    }
}
