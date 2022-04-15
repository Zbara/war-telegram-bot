<?php

namespace App\Controller;

use App\Service\TelegramService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TelegramController extends AbstractController
{
    #[Route('/webhook', name: 'webhook')]
    public function execute(TelegramService $telegramService): Response
    {
        return new Response($telegramService->handle());
    }
}
