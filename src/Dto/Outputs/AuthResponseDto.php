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
}
