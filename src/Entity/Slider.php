<?php

namespace App\Entity;

use App\Repository\SliderRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SliderRepository::class)
 */
class Slider
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $urlFoto;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrlFoto(): ?string
    {
        return $this->urlFoto;
    }

    public function setUrlFoto(string $urlFoto): self
    {
        $this->urlFoto = $urlFoto;

        return $this;
    }
}
