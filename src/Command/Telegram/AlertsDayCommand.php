<?php

namespace App\Command\Telegram;

use Telegram\Bot\Actions;

class AlertsDayCommand extends MainCommand
{
    protected $name = "alerts_day";
    protected $description = "Тривоги за добу";

    public function handle()
    {
        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $alerts = $this->alertsRepository->getAlertsDay();

        if (count($alerts) > 0) {

            $array_chunk = array_chunk($alerts, 30);

            foreach ($array_chunk as $key => $alert) {
                $this->replyWithMessage([
                    'text' => $this->environment->render('alerts.day.html.twig', [
                        'alerts' => $alert,
                        'start' => ($key === 0) ?? true,
                        'end' => ($key + 1 === count($array_chunk)) ? count($alerts) : false,
                    ]),
                    'chat_id' => $this->getUpdate()->getChat()->id,
                ]);
                if ($key + 1 < count($array_chunk)) {
                    $this->replyWithChatAction(['action' => Actions::TYPING]);
                }
                sleep(1);
            }
            return true;
        }
        return $this->replyWithMessage([
            'text' => 'Повітряних тривог не було.',
            'chat_id' => $this->getUpdate()->getChat()->id,
        ]);
    }
}
