<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Ingredient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator as FakerGenerator;


class AppFixtures extends Fixture
{

		private FakerGenerator $faker;

		public function __construct()
		{
			$this->faker = Factory::create('fr_FR');
		}

    public function load(ObjectManager $manager): void
    {
					for ($i=1 ; $i < 50 ; $i++ ) { 
						$ingrediant = new Ingredient;
            $ingrediant->setName($this->faker->word())
                   		 ->setPrice(mt_rand(0,100));

						$manager->persist($ingrediant);			 
					}

            
											 
			

        $manager->flush();
    }
}
