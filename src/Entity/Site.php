<?php

namespace App\Entity;

use App\Repository\SiteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=SiteRepository::class)
 * 
 * @UniqueEntity(fields={"url", "sitemap"})
 */
class Site
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
    private $nom;

     /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sitemap;

    /**
    * @ORM\OneToMany(targetEntity=SitePage::class, cascade={"persist", "remove"}, mappedBy="site")
    */
    private $pages;

   
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

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

    public function getSitemap(): ?string
    {
        return $this->sitemap;
    }

    public function setSitemap(string $sitemap): self
    {
        $this->sitemap = $sitemap;

        return $this;
    }

    public function getPages(): ?string
    {
        return $this->pages;
    }

    public function setPages(string $pages): self
    {
        $this->pages = $pages;

        return $this;
    }

}
