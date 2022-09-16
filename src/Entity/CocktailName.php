<?php

namespace App\Entity;

class CocktailName
{
    protected string $cocktailName;

    public function getName(): string
    {
        return $this->cocktailName;
    }

    public function setName(string $cocktailName): void
    {
        $this->cocktailName = $cocktailName;
    }

    public function __toString(): string
    {
        return $this->cocktailName;
    }


}