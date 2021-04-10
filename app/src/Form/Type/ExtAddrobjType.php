<?php


namespace App\Form\Type;


use App\Entity\ExtAddrobj;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExtAddrobjType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('objectid', NumberType::class, [
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('objectguid', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ]
            ])            
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
                'allow_add' => true,
                'allow_delete' => true,
                'attr' => [
                    'class' => 'form-control no-border',
                ]
            ])
            ->add('polygon', CollectionType::class, [
                'entry_type' => ExtAddrobjPointType::class,
                'entry_options' => [
                    'label' => false,
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'attr' => [
                    'class' => 'form-control no-border',
                ]
            ])            
            ->add('save', SubmitType::class, [
                'label' => 'Сохранить',
                'attr' => [
                    'class' => 'btn btn-primary',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ExtAddrobj::class
        ]);
    }
}