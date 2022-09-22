<?php

namespace App\Controller;

use App\Entity\CocktailName;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CocktailApiController extends AbstractController
{
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
                's' => $cocktailName->getCocktailName()
            ]
        ]);

        $parsedResponse = $response->toArray();

        if(empty($parsedResponse['drinks']) || empty($parsedResponse['drinks'][0])){
            return 'cocktail not found';
        }

        return $parsedResponse['drinks'];
    }
}