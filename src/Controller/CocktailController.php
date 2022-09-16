<?php

namespace App\Controller;

use App\Form\CocktailFormType;
use App\Entity\CocktailName;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CocktailController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function new(Request $request): Response
    {
        $cocktailName = new CocktailName();

        $form = $this->createForm(CocktailFormType::class, $cocktailName);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $cocktailName = $form->getData();

        }

        return $this->render('cocktail/home.html.twig', [
            'cocktailForm' => $form->createView(),
            'cocktailName' => $cocktailName
        ]);

    }

}
