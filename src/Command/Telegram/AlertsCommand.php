<?php

namespace App\Command\Telegram;


use Telegram\Bot\Actions;

class AlertsCommand extends MainCommand
{
    protected $name = "alerts";
    protected ?string $backAction = null;
    protected $description = "❗️Активні тревоги";


    public function handle()
    {
        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $this->replyWithMessage([
            'text' => $this->environment->render('alerts.html.twig', [
                'alerts' => $this->alertsRepository->findBy(
                    ['status' => false]
                )
            ]),
            'chat_id' => $this->getUpdate()->getChat()->id,
        ]);
    }
}
