<?php

namespace App\Form\Type;

use App\DTO\ExtAddrobjPointDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            ])
            ->add('longitude', NumberType::class, [
                'label' => false,
                'scale' => 11,
                'attr' => [
                    'required' => true,
                    'placeholder' => 'Долгота'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ExtAddrobjPointDTO::class
        ]);
    }
}
