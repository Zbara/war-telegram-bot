<?php

namespace App\Service;

use App\Entity\Users;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;

class TelegramService
{

    private UsersRepository $usersRepository;
    private Api $bot;
    private iterable $commands;
    private array $commandsByName;
    private EntityManagerInterface $entityManager;

    public function __construct(
        UsersRepository        $usersRepository,
        Api                    $bot,
        iterable               $commands,
        EntityManagerInterface $entityManager
    )
    {
        $this->usersRepository = $usersRepository;
        $this->bot = $bot;
        $this->commands = $commands;
        $this->entityManager = $entityManager;

        foreach ($commands as $command) {
            if ($command->getName() != '') {
                $this->commandsByName[$command->getName()] = $command;
            }
        }
    }

    /**
     * @throws TelegramSDKException
     */
    public function handle(): string
    {
        $userData = $this->bot->getWebhookUpdate()->getChat();

        if ($userData->get('id', 0) > 0) {

            $user = $this->usersRepository->findOneBy(['platform_id' => $userData->id]);

            if (null === $user) {
                $user = new Users();
                $user->setUsername($userData->username ?? 'user_' . $userData->id)
                    ->setFirstName($userData->firstName)
                    ->setPlatformId($userData->id)
                    ->setCreated(time())
                    ->setLastCommand('start')
                    ->setLastTime(time());

                $this->entityManager->persist($user);
                $this->entityManager->flush();
            }
            try {
                if ($this->bot->getWebhookUpdate()->hasCommand()) {
                    foreach ($this->commands as $command) {
                        $this->bot->addCommand($command);
                    }
                    $this->bot->commandsHandler(true);
                    return 'command accepted';
                }
                $commandMessage = $this->bot->getWebhookUpdate()->getMessage()->text;


                $commandByText = null;
                foreach ($this->commands as $command) {
                    if ($command->getName() != '') {
                        $this->bot->addCommand($command);
                        $this->bot->commandsHandler(true);
                        if ($command->getDescription() == $commandMessage) {
                            $commandByText = $command->getName();
                        }
                    }
                }
                if (!empty($user->getLastCommand()) && $commandByText == null) {
                    $commandBack = $user->getLastCommand();
                    if ($this->bot->getWebhookUpdate()->getMessage()->text == '⬅️ Назад до меню') {
                        $commandBack = $this->commandsByName[$user->getLastCommand()]->getBackAction();
                    }
                    $commandMessage = $this->commandsByName[$commandBack]->getDescription();

                    $commandByText = '';
                    foreach ($this->commands as $command) {
                        if ($command->getName() != '') {
                            if ($command->getDescription() == $commandMessage) {
                                $commandByText = $command->getName();
                            }
                        }
                    }
                }
                $user->setLastCommand($commandByText);

                if ($commandByText !== '') {
                    $this->bot->triggerCommand($commandByText, $this->bot->commandsHandler(true));
                }

                $user->setLastCommand($commandByText)
                    ->setLastTime(time());

                $this->entityManager->flush();

                return 'send';

            } catch (\Throwable $e) {
                $this->bot->sendMessage([
                    'chat_id' => $this->bot->getWebhookUpdate()->getChat()->id,
                    'text' => 'Команди не існує.'
                ]);
            }
        }
        return 'not a command';
    }
}
