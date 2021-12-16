<?php

namespace App\BackOffice\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ExtAddrobjType extends AbstractType
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
            ->add('alias', TextType::class, [
                'required' => false,
            ])
            ->add('anglicism', TextType::class, [
                'required' => false,
            ])
            ->add('nominative', TextType::class, [
                'required' => false,
            ])
            ->add('genitive', TextType::class, [
                'required' => false,
            ])
            ->add('dative', TextType::class, [
                'required' => false,
            ])
            ->add('accusative', TextType::class, [
                'required' => false,
            ])
            ->add('ablative', TextType::class, [
                'required' => false,
            ])
            ->add('prepositive', TextType::class, [
                'required' => false,
            ])
            ->add('locative', TextType::class, [
                'required' => false,
            ])
            ->add('synonyms', CollectionType::class, [
                'entry_type' => ExtAddrobjSynonymType::class,
                'entry_options' => [
                    'label' => false,
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'attr' => [
                    'class' => 'no-border',
                ]
            ])
            ->add('points', CollectionType::class, [
                'entry_type' => ExtAddrobjPointType::class,
                'entry_options' => [
                    'label' => false,
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'attr' => [
                    'class' => 'no-border',
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Сохранить',
            ])
        ;
    }
}
