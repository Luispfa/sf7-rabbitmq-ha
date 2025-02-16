<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\User\Application\GenderCountQueryService;
use App\User\Application\RegisterUserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    public function __construct(
        private RegisterUserService $registerUser,
        private GenderCountQueryService $genderCountQuery
    ) {}

    #[Route('/register-user', methods: ['POST'])]
    public function createUser(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['name'], $data['lastname'], $data['gender'], $data['email'])) {
            return new JsonResponse(['error' => 'Invalid input'], 400);
        }

        $user = ($this->registerUser)($data['name'], $data['lastname'], $data['gender'], $data['email']);

        return new JsonResponse($user);
    }

    #[Route('/gender-count', methods: ['GET'])]
    public function genderCount(): JsonResponse
    {
        return new JsonResponse(($this->genderCountQuery)());
    }
}
