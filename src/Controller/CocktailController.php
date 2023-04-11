<?php

namespace App\Controller;

use App\Api\CocktailApiRequest;
use App\Dto\Response\CocktailApiResponseData;
use App\Entity\CocktailName;
use App\Form\CocktailType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CocktailController extends AbstractController
{
    private CocktailApiRequest $cocktailApiRequest;

    public function __construct(CocktailApiRequest $cocktailApiRequest)
    {
        $this->cocktailApiRequest = $cocktailApiRequest;
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

        $cocktailApiResponseData = $this->cocktailApiRequest->getCocktails($cocktailName);
        //TODO API request action

        return $this->render('cocktail/submit.html.twig', [
            'cocktailForm' => $form->createView(),
            'cocktails' => $cocktailApiResponseData->getCocktailData(),
            'error' => $cocktailApiResponseData->getError()
        ]);
    }
}