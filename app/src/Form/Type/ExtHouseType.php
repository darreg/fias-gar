<?php

namespace App\Form\Type;

use App\Entity\ExtHouse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExtHouseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('objectid', NumberType::class)
            
            ->add('objectguid', TextType::class, [
                'required' => false,
            ])
            ->add('precision', TextType::class, [
                'required' => false,
            ])
            ->add('latitude', NumberType::class, [
                'required' => false,
            ])
            ->add('longitude', NumberType::class, [
                'required' => false,
            ])
            ->add('zoom', TextType::class, [
                'required' => false,
            ])

            ->add('save', SubmitType::class, [
                'label' => 'Сохранить',
            ])
        ;
    }
}
