<?php

namespace App\Tests\Unit;

use App\Entity\Recipe;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RecipeTest extends KernelTestCase
{
    public function getEntity() : Recipe
    {
        return(new Recipe())
        ->setName('Recipe#1')
        ->setDescription("description#1")
        ->setIsFavorite(true)
        ->setCreatAt(new \DateTimeImmutable())
         ->setUpdateAt(new \DateTimeImmutable());
    }
    
    public function testEntityIsValid(): void
    {
         self::bootKernel();

         $container = static::getContainer();

         $recipe = $this->getEntity();

         $errors = $container->get('validator')->validate($recipe);

         $this->assertCount(0,$errors);

        
    }


    public function testInvalidName()
    {   
        self::bootKernel();

        $container = static::getContainer();

    $recipe =$this->getEntity();
    $recipe->setName('');

               $errors = $container->get('validator')->validate($recipe);

               $this->assertCount(2,$errors);      

    }
}
