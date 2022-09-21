<?php

namespace App\Controller;

use App\Form\CocktailType;
use App\Entity\CocktailName;
use http\QueryString;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use function MongoDB\BSON\toJSON;
use function PHPUnit\Framework\isNull;

class CocktailController extends AbstractController
{
    private HttpClientInterface $httpClient;

    private const URL = 'https://www.thecocktaildb.com/api/json/v1/1/search.php';

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    #[Route('/', name: 'initForm')]
    public function initForm()
    {
        $cocktailName = new CocktailName();

        $form = $this->createForm(CocktailType::class, $cocktailName, [
            'action' => $this->generateUrl('submitName'),
        ]);

        return $this->render('cocktail/input.html.twig', [
            'cocktailForm' => $form->createView(),
            'cocktailName' => '',
            'errorMessage' => ''
        ]);
    }

    #[Route('/submit', name: 'submitName')]
    public function submitName(Request $request): Response
    {
        $cocktailName = new CocktailName();

        $form = $this->createForm(CocktailType::class, $cocktailName);
        $form->handleRequest($request);

        //TODO: add validations so only string or numstring would be preferred.
        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render('cocktail/submit.html.twig', [
                'cocktailForm' => $form,
                'result' => 'Error on submitting value'
            ]);
        }

        //TODO read about phpDoc

        /** @var CocktailName $cocktailNameEntity */
        $cocktailNameEntity = $form->getData();

        $cocktailName->setCocktailName($cocktailNameEntity->getCocktailName());

        //TODO API request action

        return $this->render('cocktail/submit.html.twig', [
            'cocktailForm' => $form->createView(),
            'result' => $this->cocktailNameRequest($cocktailName)
        ]);
    }

    private function cocktailNameRequest(CocktailName $cocktailName): string
    {
        //TODO error logger

        $response = $this->httpClient->request('GET', self::URL, [
            'query' => [
                's' => $cocktailName->getCocktailName()
            ]
        ]);

        $parsedResponse = $response->toArray();

        if(empty($parsedResponse['drinks']) && empty($parsedResponse['drinks'][0])){
            return 'cocktail not found';
        }

        return $parsedResponse['drinks'][0]['strDrink'];
    }
}