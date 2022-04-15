<?php

namespace App\Service;

use App\Entity\Alerts;
use App\Repository\AlertsRepository;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ParserService
{
    private EntityManagerInterface $entityManager;
    private HttpClientInterface $httpClient;
    private AlertsRepository $alertsRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        HttpClientInterface    $httpClient,
        AlertsRepository       $alertsRepository
    )
    {
        $this->httpClient = $httpClient;
        $this->entityManager = $entityManager;
        $this->alertsRepository = $alertsRepository;
    }

    public function run(): int
    {
        while (true) {
            $alerts = $this->api();

            foreach ($this->alertsRepository->findBy(['status' => false]) as $item) {
                $item->setStatus(true);
                $this->entityManager->persist($item);
                $this->entityManager->flush();
            }
            foreach ($alerts['alerts'] as $alert) {
                $info = $this->alertsRepository->findOneBy(['parse_id' => $alert['id']]);

                if ($info === null) {
                    $info = new Alerts();
                    $info->setStatus(false)
                        ->setStartedAt(strtotime($alert['started_at']))
                        ->setParseId($alert['id'])
                        ->setLocationTitle($alert['location_title'])
                        ->setLocationType($alert['location_type']);
                }
                $info->setUpdatedAt(strtotime($alert['updated_at']));
                $info->setStatus(false);

                $this->entityManager->persist($info);
                $this->entityManager->flush();
            }
            sleep(60);
        }
    }

    public function api(): bool|array
    {
        try {
            $response = $this->httpClient->request('GET', 'https://api.alerts.in.ua/v1/alerts/active.json', [
                'body' => [],
                'proxy' => 'http://:@127.0.0.1:8888',
                'verify_peer' => false,
                'verify_host' => false,
            ]);
            if (Response::HTTP_OK === $response->getStatusCode()) {
                return $response->toArray();
            }

        } catch (TransportExceptionInterface|ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface $e) {
            return false;
        } catch (DecodingExceptionInterface $e) {
            return $e->getMessage();
        }
        return false;
    }

}
