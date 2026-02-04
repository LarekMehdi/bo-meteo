<?php

namespace App\Tests\Service;

use App\Dto\Inputs\SigninDto;
use App\Dto\Inputs\SignupDto;
use App\Dto\Outputs\AuthResponseDto;
use App\Dto\Outputs\UserDto;
use App\Entity\User;
use App\Entity\UserToken;
use App\Repository\UserRepository;
use App\Service\AuthService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AllowMockObjectsWithoutExpectations]
class AuthServiceTest extends TestCase
{
    private $em;
    private $userRepository;
    private $passwordHasher;
    private $jwtManager;
    private $service;

    protected function setUp(): void
    {
        $this->em = $this->createMock(EntityManagerInterface::class);
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $this->jwtManager = $this->createMock(JWTTokenManagerInterface::class);

        $this->service = new AuthService(
            $this->em,
            $this->userRepository,
            $this->passwordHasher,
            $this->jwtManager
        );
    }

    /** ---------------- UTIL ---------------- */
    private function setPrivateId(object $object, int $id): void
    {
        $reflection = new \ReflectionClass($object);
        $prop = $reflection->getProperty('id');
        $prop->setValue($object, $id);
    }

    /** ---------------- SIGNUP ---------------- */
    public function testSignupSuccess(): void
    {
        $dto = new SignupDto('John', 'Doe', 'john@example.com', 'password123');

        // Le repo ne doit rien trouver
        $this->userRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['email' => 'john@example.com'])
            ->willReturn(null);

        $this->passwordHasher->expects($this->once())
            ->method('hashPassword')
            ->willReturn('hashedPassword');

        // Simuler l'id généré par Doctrine
        $this->em->expects($this->once())
            ->method('persist')
            ->willReturnCallback(function ($user) {
                $this->setPrivateId($user, 1);
            });

        $this->em->expects($this->once())->method('flush');

        $userDto = $this->service->signup($dto);

        $this->assertInstanceOf(UserDto::class, $userDto);
        $this->assertEquals(1, $userDto->getId());
        $this->assertEquals('John', $userDto->getFirstname());
        $this->assertEquals('Doe', $userDto->getLastname());
        $this->assertEquals('john@example.com', $userDto->getEmail());
    }

    /** ---------------- SIGNIN ---------------- */
    public function testSigninSuccess(): void
    {
        $dto = new SigninDto('john@example.com', 'password123');

        $user = new User();
        $this->setPrivateId($user, 1);
        $user->setEmail('john@example.com');
        $user->setFirstname('John');
        $user->setLastname('Doe');
        $user->setPassword('hashedPassword');

        $this->userRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['email' => 'john@example.com'])
            ->willReturn($user);

        $this->passwordHasher->expects($this->once())
            ->method('isPasswordValid')
            ->with($user, 'password123')
            ->willReturn(true);

        $this->jwtManager->expects($this->once())
            ->method('create')
            ->with($user)
            ->willReturn('access-token');

        $this->em->expects($this->once())->method('persist');
        $this->em->expects($this->once())->method('flush');

        $authResponse = $this->service->signin($dto);

        $this->assertInstanceOf(AuthResponseDto::class, $authResponse);
        $this->assertEquals('access-token', $authResponse->getAccessToken());
        $this->assertEquals('John', $authResponse->user->getFirstname());
    }

    /** ---------------- REFRESH ---------------- */
    public function testRefreshSuccess(): void
    {
        $plainToken = 'plain-refresh-token';
        $hashedToken = hash('sha256', $plainToken);

        $user = new User();
        $this->setPrivateId($user, 1);
        $user->setEmail('john@example.com');
        $user->setFirstname('John');
        $user->setLastname('Doe');

        $oldToken = new UserToken();
        $oldToken->setUser($user)
                 ->setToken($hashedToken)
                 ->setExpiresAt(new \DateTimeImmutable('+1 day'));

        // Mock du repository
        $userTokenRepo = $this->getMockBuilder(EntityRepository::class)
                              ->disableOriginalConstructor()
                              ->getMock();
        $userTokenRepo->method('findOneBy')
                      ->with(['token' => $hashedToken])
                      ->willReturn($oldToken);

        $this->em->method('getRepository')->willReturn($userTokenRepo);

        $this->em->expects($this->once())->method('remove')->with($oldToken);
        $this->em->expects($this->once())->method('persist');
        $this->em->expects($this->once())->method('flush');

        $this->jwtManager->expects($this->once())->method('create')->with($user)->willReturn('new-access-token');

        $authResponse = $this->service->refresh($plainToken);

        $this->assertInstanceOf(AuthResponseDto::class, $authResponse);
        $this->assertEquals('new-access-token', $authResponse->getAccessToken());
        $this->assertEquals('John', $authResponse->user->getFirstname());
    }

    /** ---------------- LOGOUT ---------------- */
    public function testLogout(): void
    {
        $plainToken = 'plain-refresh-token';
        $hashedToken = hash('sha256', $plainToken);

        $refreshToken = new UserToken();

        $userTokenRepo = $this->getMockBuilder(EntityRepository::class)
                              ->disableOriginalConstructor()
                              ->getMock();
        $userTokenRepo->method('findOneBy')
                      ->with(['token' => $hashedToken])
                      ->willReturn($refreshToken);

        $this->em->method('getRepository')->willReturn($userTokenRepo);

        $this->em->expects($this->once())->method('remove')->with($refreshToken);
        $this->em->expects($this->once())->method('flush');

        $this->service->logout($plainToken);
    }
}
