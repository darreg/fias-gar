<?php


namespace App\Controller\Api\v1;


use Symfony\Component\Validator\ConstraintViolationListInterface;

trait GetValidatorErrors
{
    private function getValidatorErrors(ConstraintViolationListInterface $violations): array
    {
        $errors = [];
        foreach ($violations as $violation) {
            $errors[$violation->getPropertyPath()][] = $violation->getMessage();
        }
        return $errors;
    }
}