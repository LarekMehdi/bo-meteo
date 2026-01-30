<?php

namespace App\Dto\Inputs;

use Symfony\Component\Validator\Constraints as Assert;

class SigninDto
{
    #[Assert\NotBlank]
    #[Assert\Email]
    #[Assert\Length(max: 200)]
    public readonly string $email;

    #[Assert\NotBlank]
    #[Assert\Length(min: 6)]
    public readonly string $password;

    public function __construct(
        string $email,
        string $password,
    ) {
        $this->email = $email;
        $this->password = $password;
    }
}
