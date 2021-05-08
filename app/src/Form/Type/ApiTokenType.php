<?php

namespace App\Form\Type;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ApiTokenType extends AbstractType
{
    private UserRepository $userRepository;

    public function __construct(
        UserRepository $userRepository
    )
    {
        $this->userRepository = $userRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $choices = [];
        $users = $this->userRepository->findAll();
        foreach ($users as $user) {
            $choices[$user->getName() . ' (' . $user->getUsername() . ')'] = $user->getId();
        }

        $builder
            ->add('status', CheckboxType::class, [
                'label' => 'Включен / отключен',
                'required' => false,
            ])            
            
            ->add('user', ChoiceType::class, [
                'choices' => $choices
            ])

            ->add('name', TextType::class, [
                'label' => 'Имя',
            ])

            ->add('token', TextType::class, [
                'label' => 'Токен',                
                'disabled' => true,
            ])

            ->add('expiresAt', DateTimeType::class, [
                'label' => 'Дата-время окончания действия',                
                'widget' => 'single_text',
                'input' => 'string',
                'input_format' => 'Y-m-d H:i'
            ])

            ->add('save', SubmitType::class, [
                'label' => 'Сохранить',
            ])
        ;
    }
}
