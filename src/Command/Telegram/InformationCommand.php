<?php

namespace App\Command\Telegram;

use Telegram\Bot\Actions;

class InformationCommand extends MainCommand
{
    protected $name = "information";
    protected $description = "Інформація";

    public function handle(): void
    {
        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $this->replyWithMessage([
            'text' => "Бот що показує активні повітряні тривоги. Інформація береться із сайту - alerts.in.ua. \n\n Версія " . $this->gitService->getBuild(),
            'chat_id' => $this->getUpdate()->getChat()->id,
        ]);
    }
}
