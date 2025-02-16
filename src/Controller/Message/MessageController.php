<?php

declare(strict_types=1);

namespace App\Controller\Message;

use App\Message\Application\MessageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class MessageController extends AbstractController
{
    public function __construct(private MessageService $messageService) {}

    #[Route('/send-message', name: 'send_message', methods: ['POST'])]
    public function sendMessage(Request $request): JsonResponse
    {
        $content = json_decode($request->getContent(), true);
        if (!isset($content['message'])) {
            return new JsonResponse(['error' => 'Missing "message" field'], 400);
        }

        $this->messageService->sendMessage($content['message']);

        return new JsonResponse(['status' => 'Message sent']);
    }
}
