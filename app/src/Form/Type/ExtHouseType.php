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
            ->add('precision', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('latitude', NumberType::class, [
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('longitude', NumberType::class, [
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('zoom', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('synonym', CollectionType::class, [
                'entry_type' => ExtAddrobjSynonymType::class,
                'entry_options' => [
                    'label' => false,
                ],
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Create ExtHouse',
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
        ;
    }
}