<?php

namespace App\Entity;

use App\Repository\ContentRepository;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass=ContentRepository::class)
 */
class Content
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

     /**
    * @ORM\ManyToOne(targetEntity=SitePage::class, inversedBy="contents")
    */
    private $page;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $text;

     /**
    * @ORM\OneToMany(targetEntity=ContentPlagiat::class, cascade={"persist", "remove"}, mappedBy="content")
    */
    private $plagiats;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPage(): ?SitePage
    {
        return $this->page;
    }

    public function setPage(SitePage $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getPlagiats(): ?string
    {
        return $this->plagiats;
    }

    public function setPlagiats(string $plagiats): self
    {
        $this->plagiats = $plagiats;

        return $this;
    }
}
