<?php

namespace App\Entity;

use App\Repository\SitePageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=SitePageRepository::class)
 *  
 * @UniqueEntity(fields={"url","site"})
 */
class SitePage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

   /**
    * @ORM\ManyToOne(targetEntity=Site::class, inversedBy="pages")
    */
    private $site;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\Column(type="boolean")
     */
    private $plagiat;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $states;

   /**
    * @ORM\OneToMany(targetEntity=Content::class, cascade={"persist", "remove"}, mappedBy="page")
    */
    private $contents;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(Site $site): self
    {
        $this->site = $site;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getPlagiat(): ?bool
    {
        return $this->plagiat;
    }

    public function setPlagiat(bool $plagiat): self
    {
        $this->plagiat = $plagiat;

        return $this;
    }

    public function getStates(): ?string
    {
        return $this->states;
    }

    public function setStates(string $states): self
    {
        $this->states = $states;

        return $this;
    }

    public function getContents(): ?string
    {
        return $this->contents;
    }

    public function setContents(string $contents): self
    {
        $this->contents = $contents;

        return $this;
    }
}
