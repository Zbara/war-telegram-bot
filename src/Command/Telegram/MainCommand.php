<?php
declare(strict_types=1);

namespace App\Command\Telegram;

use App\Entity\Users;
use App\Repository\AlertsRepository;
use App\Repository\UsersRepository;
use App\Service\ParserService;
use Doctrine\ORM\EntityManagerInterface;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Api;
use Telegram\Bot\Keyboard\Keyboard;
use Twig\Environment;

class MainCommand  extends Command
{
    protected $name = '';
    protected ?string $backAction = null;
    protected $description;

    protected EntityManagerInterface $entityManager;
    protected Users $user;
    protected Api $api;
    protected Environment $environment;
    protected ParserService $parse;

    public function __construct(
        EntityManagerInterface $entityManager,
        Api $api,
        Environment  $environment,
        ParserService $parserService,
        AlertsRepository       $alertsRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->api = $api;
        $this->environment = $environment;
        $this->alertsRepository = $alertsRepository;
    }

    public function handle()
    {
        $userData = $this->api->getWebhookUpdate()->getChat();
        $this->user = $this->entityManager->getRepository(Users::class)->findOneBy([
            'username' => $userData->username
        ]);
    }
}
