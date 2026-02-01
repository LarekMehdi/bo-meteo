<?php

namespace App\Dto\Inputs;

use Symfony\Component\Validator\Constraints as Assert;

class SigninDto
{
    #[Assert\NotBlank]
    #[Assert\Email]
    #[Assert\Length(max: 200)]
    private readonly string $email;

    #[Assert\NotBlank]
    #[Assert\Length(min: 6)]
    private readonly string $password;

    public function __construct(
        string $email,
        string $password,
    ) {
        $this->email = $email;
        $this->password = $password;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
