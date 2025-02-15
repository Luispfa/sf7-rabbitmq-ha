<?php

declare(strict_types=1);

namespace App\Controller;

use App\Application\Service\UserService;
use App\Infrastructure\MessageHandler\GenderCountHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    public function __construct(
        private UserService $userService,
        private GenderCountHandler $genderCountHandler
    ) {}

    #[Route('/create-user', methods: ['POST'])]
    public function createUser(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['name'], $data['lastname'], $data['gender'])) {
            return new JsonResponse(['error' => 'Invalid input'], 400);
        }

        $user = $this->userService->registerUser($data['name'], $data['lastname'], $data['gender']);

        return new JsonResponse($user);
    }

    #[Route('/gender-count', methods: ['GET'])]
    public function genderCount(): JsonResponse
    {
        return new JsonResponse($this->genderCountHandler->getGenderCount());
    }
}
