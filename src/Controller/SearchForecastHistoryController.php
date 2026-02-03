<?php

namespace App\Controller;

use App\Dto\Inputs\ForecastFilterDto;
use App\Service\SearchForecastHistoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/history')]
final class SearchForecastHistoryController extends AbstractController
{
    public function __construct(private readonly SearchForecastHistoryService $historyService)
    {
    }

    /** CREATE **/
    #[Route('', name: 'history_create', methods: ['POST'])]
    public function create(ForecastFilterDto $dto): JsonResponse
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        if (!$user) {
            throw new UnauthorizedHttpException('Bearer', 'User not authenticated');
        }

        $history = $this->historyService->create($dto, $user);

        return $this->json($history);
    }

    /** FIND ALL **/
    #[Route('', name: 'history_find_all_by_user', methods: ['POST'])]
    public function findAllByUser(): JsonResponse
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        if (!$user) {
            throw new UnauthorizedHttpException('Bearer', 'User not authenticated');
        }

        $histories = $this->historyService->findAllByUser($user);

        return $this->json($histories);
    }

    /** DELETE **/
    #[Route('/{id}', name: 'history_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        if (!$user) {
            throw new UnauthorizedHttpException('Bearer', 'User not authenticated');
        }

        $this->historyService->delete($id, $user);

        return $this->json([
            'message' => 'History deleted successfully',
        ], 200);
    }
}
