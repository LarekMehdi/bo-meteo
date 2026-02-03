<?php

namespace App\Tests\Service;

use App\Dto\Inputs\ForecastFilterDto;
use App\Dto\Inputs\HistoryFilterDto;
use App\Entity\SearchForecastHistory;
use App\Entity\User;
use App\Repository\SearchForecastHistoryRepository;
use App\Service\SearchForecastHistoryService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[AllowMockObjectsWithoutExpectations]
class SearchForecastHistoryServiceTest extends TestCase
{
    private $repo;
    private $em;
    private $service;

    protected function setUp(): void
    {
        $this->repo = $this->createMock(SearchForecastHistoryRepository::class);
        $this->em = $this->createMock(EntityManagerInterface::class);

        $this->service = new SearchForecastHistoryService($this->em, $this->repo);
    }

    /** ---------------- UTIL ---------------- */
    private function setPrivateId(object $object, int $id): void
    {
        $reflection = new \ReflectionClass($object);
        $prop = $reflection->getProperty('id');
        $prop->setValue($object, $id);
    }

    /** ---------------- FIND ALL ---------------- */
    public function testFindAllByUser(): void
    {
        $user = new User();
        $this->setPrivateId($user, 1);

        $filter = new HistoryFilterDto();
        $filter->page = 1;
        $filter->limit = 2;

        $history1 = new SearchForecastHistory($user, 48.0, 2.0);
        $history2 = new SearchForecastHistory($user, 49.0, 3.0);

        $this->setPrivateId($history1, 1);
        $this->setPrivateId($history2, 2);

        $entities = [$history1, $history2];

        $this->repo->expects($this->once())
            ->method('findAllByUser')
            ->with($user, $filter)
            ->willReturn($entities);

        $this->repo->expects($this->once())
            ->method('countByUser')
            ->with($user, $filter)
            ->willReturn(10);

        $pageDto = $this->service->findAllByUser($user, $filter);

        $this->assertCount(2, $pageDto->getDatas());
        $this->assertEquals(10, $pageDto->getTotalElements());

        $first = $pageDto->getDatas()[0];
        $this->assertEquals(48.0, $first->getLatitude());
        $this->assertEquals(2.0, $first->getLongitude());
    }

    /** ---------------- CREATE ---------------- */
    public function testCreate(): void
    {
        $user = new User();
        $this->setPrivateId($user, 1);

        $dto = new ForecastFilterDto(47.0, 3.0);

        $this->em->expects($this->once())->method('persist');
        $this->em->expects($this->once())->method('flush');

        $history = $this->service->create($dto, $user);

        $this->assertInstanceOf(SearchForecastHistory::class, $history);
        $this->assertEquals(47.0, $history->getLatitude());
        $this->assertEquals(3.0, $history->getLongitude());
        $this->assertEquals($user, $history->getUser());
    }

    /** ---------------- DELETE ---------------- */
    public function testDeleteSuccess(): void
    {
        $user = new User();
        $this->setPrivateId($user, 1);

        $history = new SearchForecastHistory($user, 48.0, 2.0);

        $this->repo->expects($this->once())
            ->method('find')
            ->with(123)
            ->willReturn($history);

        $this->em->expects($this->once())->method('remove')->with($history);
        $this->em->expects($this->once())->method('flush');

        $this->service->delete(123, $user);
        $this->addToAssertionCount(1);
    }

    public function testDeleteNotFound(): void
    {
        $user = new User();
        $this->setPrivateId($user, 1);

        $this->repo->expects($this->once())
            ->method('find')
            ->with(123)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);

        $this->service->delete(123, $user);
    }

    public function testDeleteAccessDenied(): void
    {
        $user = new User();
        $this->setPrivateId($user, 1);

        $otherUser = new User();
        $this->setPrivateId($otherUser, 2);

        $history = new SearchForecastHistory($otherUser, 48.0, 2.0);

        $this->repo->expects($this->once())
            ->method('find')
            ->with(123)
            ->willReturn($history);

        $this->expectException(AccessDeniedHttpException::class);

        $this->service->delete(123, $user);
    }
}
