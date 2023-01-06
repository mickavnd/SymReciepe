<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecipeController extends AbstractController
{

    /**
     * this controller  display   the recipes
     *
     * @param RecipeRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/recette', name: 'recipe_index', methods:['GET'])]
    public function index(RecipeRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        
        $recipes = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('pages/recipe/index.html.twig', [
            'recipes' => $recipes
        ]);
    }

    #[Route('/recette/add-recette', name:'Add_recipe',methods:['GET','POST'])]
    public function addRecipe() :Response
    {

        return $this->render('pages/recipe/add.html.twig');

    }
}
