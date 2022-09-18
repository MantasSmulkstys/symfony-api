<?php

namespace App\Entity;

class CocktailName
{
    protected string $cocktailName;

    /**
     * @return string
     */
    public function getCocktailName(): string
    {
        return $this->cocktailName;
    }

    /**
     * @param string $cocktailName
     */
    public function setCocktailName(string $cocktailName): void
    {
        $this->cocktailName = $cocktailName;
    }
}