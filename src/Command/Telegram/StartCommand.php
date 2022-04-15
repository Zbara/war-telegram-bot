<?php
declare(strict_types=1);

namespace App\Command\Telegram;

use Telegram\Bot\Actions;
use Telegram\Bot\Keyboard\Keyboard;

class StartCommand extends MainCommand
{
    protected $name = "start";
    protected $description = "Воздушные тревоги";

    public function handle()
    {
        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $reply_markup = Keyboard::make([
            'keyboard' => [
                [
                    '❗️Активні тревоги','Тривоги за добу'
                ],
                [
                    'Інформація', 'Налаштування'
                ]
            ],
            'resize_keyboard' => true,
            'one_time_keyboard' => false
        ]);
        $this->replyWithMessage([
            'text' => "Бот для перевірки повітряних тривог.",
            'chat_id' => $this->getUpdate()->getChat()->id,
            'reply_markup' => $reply_markup
        ]);
    }
}
