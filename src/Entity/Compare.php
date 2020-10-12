<?php

namespace App\Entity;

use App\Repository\CompareRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CompareRepository::class)
 */
class Compare
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $text1;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $text2;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $calcul;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText1(): ?string
    {
        return $this->text1;
    }

    public function setText1(string $text1): self
    {
        $this->text1 = $text1;

        return $this;
    }

    public function getText2(): ?string
    {
        return $this->text2;
    }

    public function setText2(string $text2): self
    {
        $this->text2 = $text2;

        return $this;
    }

    public function getCalcul(): ?string
    {
        return $this->calcul;
    }

    public function setCalcul(string $calcul): self
    {
        $this->calcul = $calcul;

        return $this;
    }
}
