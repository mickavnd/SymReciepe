<?php

namespace App\Controller;

use App\Entity\Mark;
use App\Entity\Recipe;
use App\Form\MarkType;
use App\Form\RecipeType;
use App\Repository\MarkRepository;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\ItemInterface;

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
    #[IsGranted('ROLE_USER')]
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

    #[Route('/recette/public','recipe_index_public', methods:['GET'])]
    public function  indexPublic( PaginatorInterface $paginator ,RecipeRepository $repository, Request $request ) : Response
    {
        $cache = new FilesystemAdapter();
        $data =  $cache->get('recipes',function(ItemInterface $itme) use ($repository){
            $itme->expiresAfter(15);
           return $repository->findPublicRecipe(null);
        });

        $recipes = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            4
        );

        return $this->render('pages/recipe/indexPublic.html.twig',[
            'recipes' => $recipes
        ]);
    }


  
    /**
     * this controller allow us to see a recipe  if this one is public
     *
     * @param Recipe $recipe
     * @return Response
     */ 
    #[Security("is_granted('ROLE_USER') and recipe.getIsPublic() === true || user === recipe.getUser() ")]
    #[Route('/recette/show/{id}','recipe_show', methods:['GET','POST'])]
    public function show(Recipe $recipe, Request $request, MarkRepository $markRepository,EntityManagerInterface $manager) :Response
    {
        $mark = new Mark();
        $form = $this->createForm(MarkType::class, $mark);
        $form ->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        { $mark->setUser($this->getUser())
            ->setRecipe($recipe);
     
         $existingMark = $markRepository->findOneBy([
           'user'=> $this->getUser(),
           'recipe'=> $recipe
         ]);

         if (!$existingMark ){
           $manager->persist($mark);
         }
         else{
            $existingMark->setMark(
                $form->getData()->getMark()
            );
         }  
           
           $manager->flush();

         $this->addFlash(
           'success',
           'Vous avez bien noter la recette');

           return $this->redirectToRoute('recipe_show',['id' =>$recipe->getId()]);
         }

         
           return $this->render('pages/recipe/show.html.twig',[
                            'recipe' => $recipe,
                            'form' => $form->createView()]);
           
        
    } 
             
        

    /**
     * this  controller add  new recipie
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/recette/add-recette', name:'recipe_add',methods:['GET','POST'])]
    #[IsGranted('ROLE_USER')]
    public function addRecipe(Request $request,EntityManagerInterface $manager) :Response
    {
        $recipe = new Recipe();
        $form  = $this->createForm(RecipeType::class,$recipe);

        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid())
        {
            // dd($form->getData());
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
    #[Security("is_granted('ROLE_USER') and user === recipe.getUser()")]
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
    #[Security("is_granted('ROLE_USER') and user === recipe.getUser()")]
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
