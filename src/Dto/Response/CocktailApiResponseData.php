<?php

namespace App\Dto\Response;

class CocktailApiResponseData
{
    /** @var CocktailData[]|null */
    private $cocktailData;

    /**
     * @var string
     */
    private string $error;

    public function __construct
    (
        $cocktailData,
        string $error
    ) {
        $this->cocktailData = $cocktailData;
        $this->error = $error;
    }

    /**
     * @return CocktailData[]|null
     */
    public function getCocktailData(): ?array
    {
        return $this->cocktailData;
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }
}