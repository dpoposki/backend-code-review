<?php
declare(strict_types=1);

namespace App\Infrastructure\Symfony\Http\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationFailedResponse extends JsonResponse
{
    public function __construct(ConstraintViolationListInterface $violationList)
    {
        $violations = [];

        foreach ($violationList as $violation) {
            $violations[$violation->getPropertyPath()] = $violation->getMessage();
        }

        parent::__construct($violations, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
