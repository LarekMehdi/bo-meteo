<?php

namespace App\Dto\Outputs;

class AuthResponseDto
{
    public function __construct(
        public readonly string $accessToken,
        public readonly ?string $refreshToken,
        public readonly UserDto $user,
    ) {
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function setAccessToken(string $accessToken): static
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(?string $refreshToken): static
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }

    public function getUser(): UserDto
    {
        return $this->user;
    }

    public function setUser(UserDto $user): static
    {
        $this->user = $user;

        return $this;
    }
}
