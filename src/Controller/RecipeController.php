<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
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
            $repository->findBy(['user'=>$this->getUser()]),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('pages/recipe/index.html.twig', [
            'recipes' => $recipes
        ]);
    }
    /**
     * this  controller add  new recipie
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/recette/add-recette', name:'recipe_add',methods:['GET','POST'])]
    public function addRecipe(Request $request,EntityManagerInterface $manager) :Response
    {
        $recipe = new Recipe();
        $form  = $this->createForm(RecipeType::class,$recipe);

        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid())
        {
         $recipe=$form->getData();
         $recipe->setUser( $this->getUser());

         $manager->persist($recipe);
         $manager->flush();

         $this->addFlash(
            'success',
            'votre recette a bien  ajouté'
        );


         return $this->redirectToRoute('recipe_index');

        }

        return $this->render('pages/recipe/add.html.twig',[
            'form'=>$form->createView()
        ]);

    }

     /**
     * this function  upadate  an recipe
     *
     * @param Ingredient $ingredient
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */ 
    #[Route('/recette/edit/{id}', name: 'recipe_edit', methods: ['GET', 'POST'])]
    public function edit(Recipe $recipe, Request $request, EntityManagerInterface $manager): Response
    {

        $form  = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $recipe= $form->getData();

            $manager->persist($recipe);
            $manager->flush();

            $this->addFlash(
                'success',
                'votre recette bien éte modifier'
            );

            return $this->redirectToRoute('recipe_index');
        }



        return $this->render('pages/recipe/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    
    
    /**
     * this function  delete  an recipe by Id
     *
     * @param Ingredient $ingredient
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/recette/delete/{id}', 'recipe_delete', methods: ['GET'])]
    public function delete(Recipe $recipe, EntityManagerInterface $manager): Response
    {


        $manager->remove($recipe);
        $manager->flush();

        $this->addFlash(
            'success',
            'votre recette a bien ete supprimer'
        );

        return $this->redirectToRoute('recipe_index');
    }
}
