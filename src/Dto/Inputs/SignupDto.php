<?php

namespace App\Dto\Inputs;

use Symfony\Component\Validator\Constraints as Assert;

class SignupDto
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 50)]
    public readonly string $firstname;

    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 50)]
    public readonly string $lastname;

    #[Assert\NotBlank]
    #[Assert\Email]
    #[Assert\Length(max: 200)]
    public readonly string $email;

    #[Assert\NotBlank]
    #[Assert\Length(min: 6)]
    public readonly string $password;

    public function __construct(
        string $firstname,
        string $lastname,
        string $email,
        string $password,
    ) {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->password = $password;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function getLastname(): string
    {
        return $this->lastname;
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
