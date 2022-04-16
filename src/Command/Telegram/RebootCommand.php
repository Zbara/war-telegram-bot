<?php

namespace App\Command\Telegram;


use Telegram\Bot\Actions;
use Telegram\Bot\Keyboard\Keyboard;

class RebootCommand extends MainCommand
{
    protected $name = "reboot";
    protected $aliases = ['restart', 'reload'];
    protected ?string $backAction = null;
    protected $description = "Перезапуск";


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
            'text' => "Бот успішно перезапущений.",
            'chat_id' => $this->getUpdate()->getChat()->id,
            'reply_markup' => $reply_markup
        ]);
    }
}
