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
    public function createForDto(string $className, string $dtoClassName, array $data): FormInterface
    {
        $dto = new $dtoClassName();
        if (count($data) !== 0 ) {
            $dto = $dtoClassName::fromArray($data);
        }

        return $this->formFactory->create($className, $dto);
    }
}