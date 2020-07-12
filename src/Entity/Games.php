<?php

namespace App\Entity;

use App\Repository\GamesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GamesRepository::class)
 */
class Games
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $HomeTeam;

    /**
     * @ORM\Column(type="integer")
     */
    private $AwayTeam;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Final;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Date;

    /**
     * @ORM\Column(type="integer")
     */
    private $NumberMatch;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHomeTeam(): ?int
    {
        return $this->HomeTeam;
    }

    public function setHomeTeam(int $HomeTeam): self
    {
        $this->HomeTeam = $HomeTeam;

        return $this;
    }

    public function getAwayTeam(): ?int
    {
        return $this->AwayTeam;
    }

    public function setAwayTeam(int $AwayTeam): self
    {
        $this->AwayTeam = $AwayTeam;

        return $this;
    }

    public function getFinal(): ?string
    {
        return $this->Final;
    }

    public function setFinal(string $Final): self
    {
        $this->Final = $Final;

        return $this;
    }

    public function getDate(): ?string
    {
        return $this->Date;
    }

    public function setDate(string $Date): self
    {
        $this->Date = $Date;

        return $this;
    }

    public function getNumberMatch(): ?int
    {
        return $this->NumberMatch;
    }

    public function setNumberMatch(int $NumberMatch): self
    {
        $this->NumberMatch = $NumberMatch;

        return $this;
    }
}
