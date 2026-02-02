<?php

namespace App\Dto\Inputs;

use Symfony\Component\Validator\Constraints as Assert;

class CityFilterDto
{
    #[Assert\NotNull]
    public string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
