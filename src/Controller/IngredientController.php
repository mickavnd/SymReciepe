<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use PhpParser\Builder\Namespace_;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

// create Controller  with php bin/console make:Controller  nameController
class IngredientController extends AbstractController
{
    /**
     * this function dislpay ingredient
     *
     * @param IngredientRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/ingredient', name: 'homepage', methods: ['GET'])]
    public function index(IngredientRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        // $ingredients =  $repository->findAll();
        //install package composer require knplabs/knp-paginator-bundle
        $ingrediants = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('pages/ingredient/index.html.twig', [
            'ingredients' => $ingrediants
        ]);
    }
    /**
     * this controller dislpay a form and  create  a new  Ingredient
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/ingredient/add', name: 'ingredient_add', methods: ['GET', 'POST'])]
    public function addIngredient(Request $request, EntityManagerInterface $manager): Response
    {
        $ingredient = new Ingredient();
        $form  = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();

            $manager->persist($ingredient);
            $manager->flush();

            $this->addFlash(
                'success',
                'votre ingredient a bien etait ajouté'
            );

            return $this->redirectToRoute('homepage');
        }



        return $this->render('pages/Ingredient/add.html.twig', [
            'form' => $form->createView()

        ]);
    }
 
    /**
     * Undocumented function
     *
     * @param Ingredient $ingredient
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */ 
     #[Route('/ingredient/edit/{id}', name: 'ingredient_edit', methods: ['GET', 'POST'])]
    public function edit(Ingredient $ingredient, Request $request, EntityManagerInterface $manager): Response
    {

        $form  = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();

            $manager->persist($ingredient);
            $manager->flush();

            $this->addFlash(
                'success',
                'votre ingredient a bien modifier ajouté'
            );

            return $this->redirectToRoute('homepage');
        }



        return $this->render('pages/ingredient/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    
    
    /**
     * Undocumented function
     *
     * @param Ingredient $ingredient
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/ingredient/delete/{id}', 'ingredient_delete', methods: ['GET'])]
    public function delete(Ingredient $ingredient, EntityManagerInterface $manager): Response
    {


        $manager->remove($ingredient);
        $manager->flush();

        $this->addFlash(
            'success',
            'votre ingredient a bien ete supprimer'
        );

        return $this->redirectToRoute('homepage');
    }
}
