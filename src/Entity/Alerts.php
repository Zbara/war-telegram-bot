<?php

namespace App\Entity;

use App\Repository\AlertsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AlertsRepository::class)]
class Alerts
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $location_title;

    #[ORM\Column(type: 'string', length: 255)]
    private $location_type;

    #[ORM\Column(type: 'integer')]
    private $started_at;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $finished_at;

    #[ORM\Column(type: 'integer')]
    private $updated_at;

    #[ORM\Column(type: 'integer')]
    private $parse_id;

    #[ORM\Column(type: 'boolean')]
    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocationTitle(): ?string
    {
        return $this->location_title;
    }

    public function setLocationTitle(string $location_title): self
    {
        $this->location_title = $location_title;

        return $this;
    }

    public function getLocationType(): ?string
    {
        return $this->location_type;
    }

    public function setLocationType(string $location_type): self
    {
        $this->location_type = $location_type;

        return $this;
    }

    public function getStartedAt()
    {
        return $this->started_at;
    }

    public function setStartedAt(int $started_at): self
    {
        $this->started_at = $started_at;

        return $this;
    }

    public function getFinishedAt(): ?int
    {
        return $this->finished_at;
    }

    public function setFinishedAt(?int $finished_at): self
    {
        $this->finished_at = $finished_at;

        return $this;
    }

    public function getUpdatedAt(): ?int
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(int $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getParseId(): ?int
    {
        return $this->parse_id;
    }

    public function setParseId(int $parse_id): self
    {
        $this->parse_id = $parse_id;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }
}
