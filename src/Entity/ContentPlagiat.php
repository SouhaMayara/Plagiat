<?php

namespace App\Entity;

use App\Repository\ContentPlagiatRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ContentPlagiatRepository::class)
 */
class ContentPlagiat
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

      /**
    * @ORM\ManyToOne(targetEntity=Content::class, inversedBy="plagiats")
    */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $URL;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $plagiat;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?Content
    {
        return $this->content;
    }

    public function setContent(Content $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getURL(): ?string
    {
        return $this->URL;
    }

    public function setURL(string $URL): self
    {
        $this->URL = $URL;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPlagiat(): ?string
    {
        return $this->plagiat;
    }

    public function setPlagiat(string $plagiat): self
    {
        $this->plagiat = $plagiat;

        return $this;
    }
}
