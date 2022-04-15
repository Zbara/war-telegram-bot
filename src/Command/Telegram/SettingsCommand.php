<?php

namespace App\Command\Telegram;


use Telegram\Bot\Actions;
use Telegram\Bot\Keyboard\Keyboard;

class SettingsCommand extends MainCommand
{
    protected $name = "settings";
    protected ?string $backAction = 'start';
    protected $description = "Налаштування";


    public function handle()
    {
        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $reply_markup = Keyboard::make([
            'keyboard' => [
                [
                    'Перезапуск'
                ],
                [
                    '⬅️ Назад до меню'
                ]
            ],
            'resize_keyboard' => true,
            'one_time_keyboard' => false
        ]);
        $this->replyWithMessage([
            'text' => "Налаштування.",
            'chat_id' => $this->getUpdate()->getChat()->id,
            'reply_markup' => $reply_markup
        ]);
    }

    public function getBackAction(): string
    {
        return $this->backAction;
    }
}
