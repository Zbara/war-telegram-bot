<?php

namespace App\Parser;

use App\Repository\AlertsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Api
{
    private HttpClientInterface $httpClient;

    public function __construct(
        HttpClientInterface    $httpClient,
    )
    {
        $this->httpClient = $httpClient;
    }


    public function request(): bool|array
    {
        try {
            $response = $this->httpClient->request('GET', 'https://api.alerts.in.ua/v1/alerts/active.json', [
                'body' => [],
                //'proxy' => 'http://:@127.0.0.1:8888'
            ]);
            if (Response::HTTP_OK === $response->getStatusCode()) {
                return $response->toArray();
            }

        } catch (DecodingExceptionInterface|TransportExceptionInterface|ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface $e) {
            return false;
        }
        return false;
    }

}
