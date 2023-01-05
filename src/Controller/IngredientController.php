<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

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

        $ingrediants = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 2),
            10
        );

        return $this->render('pages/ingredient/index.html.twig', [
            'ingredients' => $ingrediants
        ]);
    }

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
}
