<?php

namespace App\ArgumentResolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DtoResolver implements ValueResolverInterface
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
    ) {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        // Vérifie si l'argument est un DTO
        $argumentType = $argument->getType();
        if (!$argumentType || !str_ends_with($argumentType, 'Dto')) {
            return [];
        }

        // Désérialise le contenu de la requête
        $dto = $this->serializer->deserialize(
            $request->getContent(),
            $argumentType,
            'json'
        );

        // Valide le DTO
        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            throw new BadRequestHttpException((string) $errors);
        }

        yield $dto;
    }
}
