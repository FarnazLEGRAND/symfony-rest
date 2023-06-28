<?php

namespace App\Entity;

// manuelement je vais ecrir cette adress pour validation yek test!
use Symfony\Component\Validator\Constraints as Assert;

class Genre
{

    public function __construct(
    //  Validation lable:
        #[Assert\NotBlank]
        private string $label,
        private ?int $id = null
    ) {
    }

    /**
     * @return 
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param  $id 
     * @return self
     */
    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label 
     * @return self
     */
    public function setLabel(string $label): self
    {
        $this->label = $label;
        return $this;
    }
}