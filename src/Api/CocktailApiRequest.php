<?php

namespace App\Api;

use App\Dto\Response\CocktailApiResponseData;
use App\Dto\Response\CocktailData;
use App\Entity\CocktailName;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CocktailApiRequest
{
    private const queryParam = 's';
    private HttpClientInterface $httpClient;
    private const URL = 'https://www.thecocktaildb.com/api/json/v1/1/search.php';

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function cocktailNameRequest(CocktailName $cocktailName)
    {
        $response = $this->httpClient->request('GET', self::URL, [
            'query' => [
                self::queryParam => $cocktailName->getCocktailName(),
            ],
        ]);

        $parsedResponse = $response->toArray();

        if (!$parsedResponse['drinks'] || empty($parsedResponse['drinks'][0])) {
            return new CocktailApiResponseData (
                null,
                sprintf(
                    'Cocktail by name "%s" not found',
                    $cocktailName->getCocktailName()
                )
            );
        }

        $cocktailData = [];

        foreach ($parsedResponse['drinks'] as $drink) {
            $cocktailData[] = new CocktailData (
                $drink['idDrink'],
                $drink['strDrink']
            );
        }

        return new CocktailApiResponseData($cocktailData, '');
    }
}