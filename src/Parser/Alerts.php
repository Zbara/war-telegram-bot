<?php

namespace App\Parser;

use App\Entity\Alerts as AlertsEntity;
use App\Parser\Model\Items;
use App\Repository\AlertsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\Pure;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Alerts
{
    private EntityManagerInterface $entityManager;
    private HttpClientInterface $httpClient;
    private AlertsRepository $alertsRepository;
    private ArrayCollection $items;
    private Api $api;

    #[Pure]
    public function __construct(
        EntityManagerInterface $entityManager,
        AlertsRepository       $alertsRepository,
        Api                    $api
    )
    {
        $this->entityManager = $entityManager;
        $this->alertsRepository = $alertsRepository;
        $this->items = new ArrayCollection();
        $this->api = $api;
    }

    public function handle():void
    {
        if ($items = $this->setter()) {
            $this->update();

            foreach ($items as $item) {
                $alert = $this->alertsRepository->findOneBy(['parse_id' => $item->getId()]);

                if ($alert === null) {
                    $alert = new AlertsEntity();
                    $alert->setStartedAt($item->getStartedAt())
                        ->setParseId($item->getId())
                        ->setLocationTitle($item->getLocationTitle())
                        ->setLocationType($item->getLocationType());
                }

                $alert->setUpdatedAt($item->getUpdatedAt())
                    ->setStatus(false);

                $this->entityManager->persist($alert);
            }
            $this->entityManager->flush();
        }
    }

    /**
     * @return Collection<int, Items>
     */
    private function setter(): Collection
    {
        $alerts = $this->api->request();

        if (isset($alerts['alerts'])) {

            foreach ($alerts['alerts'] as $alert) {
                $item = new Items();
                $item->setUpdatedAt(strtotime($alert['updated_at']))
                    ->setLocationTitle($alert['location_title'])
                    ->setStartedAt(strtotime($alert['started_at']))
                    ->setId($alert['id'])
                    ->setLocationType($alert['location_type']);

                $this->items[] = $item;
            }
        }
        return $this->items;
    }

    private function update()
    {
        foreach ($this->alertsRepository->findBy(['status' => false]) as $item){
            $item->setStatus(true);
        }

        $this->entityManager->flush();
    }
}
