<?php

namespace App\Manager;

use App\DTO\ConstructFromArrayInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;

class FormManager
{
    private FormFactoryInterface $formFactory;

    public function __construct(
        FormFactoryInterface $formFactory
    ) {
        $this->formFactory = $formFactory;
    }

    /**
     * @param class-string<FormTypeInterface> $className
     * @param class-string<ConstructFromArrayInterface> $dtoClassName
     */
    public function createForDto(
        string $className,
        string $dtoClassName,
        array $data,
        array $options = []
    ): FormInterface {
        /** @var mixed $dto */
        $dto = (count($data) !== 0) ? $dtoClassName::fromArray($data) : null;
        $options['data_class'] = $dtoClassName;
        return $this->formFactory->create($className, $dto, $options);
    }
}
