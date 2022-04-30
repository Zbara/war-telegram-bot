<?php

namespace App\Listener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ApiExceptionListener
{
    public function __construct(
        private bool $isDebug
    )
    {
    }

    public function __invoke(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();

        dump(explode(' ', exec('cat ../.git/logs/HEAD')));

        die;



        $event->setResponse(
            new JsonResponse(
                [
                    'code' => $throwable->getCode(),
                    'messages' => $throwable->getMessage(),
                    'class' => get_class($throwable),
                    'trace' => $this->isDebug ? $throwable->getTrace() : null
                ]
            )
        );
    }
}

