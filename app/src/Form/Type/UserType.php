<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status', CheckboxType::class, [
                'label' => 'Включен / отключен',
                'required' => false,
            ])

            ->add('name', TextType::class, [
                'label' => 'Имя',
            ])

            ->add('email', EmailType::class)

            ->add('password', PasswordType::class, [
                'label' => 'Пароль',
                'required' => false,
            ])

            ->add('confirmPassword', PasswordType::class, [
                'label' => 'Подтвердить пароль',
                'required' => false,
            ])

            ->add('save', SubmitType::class, [
                'label' => 'Сохранить',
            ])
        ;
    }
}
