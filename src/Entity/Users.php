<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
class Users
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'bigint')]
    private $platform_id;

    #[ORM\Column(type: 'string', length: 255)]
    private $lastCommand;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $first_name;

    #[ORM\Column(type: 'string', length: 255)]
    private $username;

    #[ORM\Column(type: 'integer')]
    private $created;

    #[ORM\Column(type: 'integer')]
    private $last_time;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastCommand(): ?string
    {
        return $this->lastCommand;
    }

    public function setLastCommand(string $lastCommand): self
    {
        $this->lastCommand = $lastCommand;

        return $this;
    }

    public function getPlatformId(): ?string
    {
        return $this->platform_id;
    }

    public function setPlatformId(string $platform_id): self
    {
        $this->platform_id = $platform_id;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getCreated(): ?int
    {
        return $this->created;
    }

    public function setCreated(int $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getLastTime(): ?int
    {
        return $this->last_time;
    }

    public function setLastTime(int $last_time): self
    {
        $this->last_time = $last_time;

        return $this;
    }
}
