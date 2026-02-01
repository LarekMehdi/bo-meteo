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
        $argumentType = $argument->getType();
        if (!$argumentType || !str_ends_with($argumentType, 'Dto')) {
            return [];
        }

        try {
            // GET: query params
            if ($request->isMethod('GET')) {
                $data = $request->query->all();

                $data = $this->castTypes($data);

                $jsonData = json_encode($data);

                $dto = $this->serializer->deserialize(
                    $jsonData,
                    $argumentType,
                    'json'
                );
            } else {
                // POST/PUT/PATCH: body
                $content = $request->getContent();

                if (empty($content)) {
                    throw new BadRequestHttpException('Request body is empty');
                }

                $dto = $this->serializer->deserialize(
                    $content,
                    $argumentType,
                    'json'
                );
            }

            // Valide le DTO
            $errors = $this->validator->validate($dto);
            if (count($errors) > 0) {
                throw new BadRequestHttpException((string) $errors);
            }

            yield $dto;
        } catch (\Symfony\Component\Serializer\Exception\NotNormalizableValueException $e) {
            throw new BadRequestHttpException('Invalid data: '.$e->getMessage());
        }
    }

    private function castTypes(array $data): array
    {
        $casted = [];

        foreach ($data as $key => $value) {
            if (is_numeric($value)) {
                // point = float
                if (str_contains((string) $value, '.')) {
                    $casted[$key] = (float) $value;
                } else {
                    if (in_array($key, ['latitude', 'longitude'])) {
                        $casted[$key] = (float) $value;
                    } else {
                        $casted[$key] = (int) $value;
                    }
                }
            }
            // booléen
            elseif (in_array(strtolower($value), ['true', 'false', '1', '0'], true)) {
                $casted[$key] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
            }
            // string
            else {
                $casted[$key] = $value;
            }
        }

        return $casted;
    }
}
