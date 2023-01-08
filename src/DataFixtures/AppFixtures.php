<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator as FakerGenerator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
	//install package  composer require fakerphp/faker --dev    
	//install packege composer require --dev orm-fixtures
	private FakerGenerator $faker;

	// private UserPasswordHasherInterface $hasher;
	
	public function __construct()
	{
		$this->faker = Factory::create('fr_FR');
		// $this->hasher=$hasher;
		// UserPasswordHasherInterface $hasher
	}

	public function load(ObjectManager $manager): void
	{				//ingrdieent
		$ingredient = [];
		for ($i = 1; $i < 50; $i++) {
			$ingrediant = new Ingredient;
			$ingrediant->setName($this->faker->word())
				->setPrice(mt_rand(0, 100));

			$ingredient[] = $ingrediant;
			$manager->persist($ingrediant);
		}

		//recipie
		for ($j = 0; $j < 25; $j++) {

			$recipie = new Recipe();
			$recipie->setName($this->faker->word())
				->setTime(mt_rand(0, 1) == 1 ? mt_rand(1, 1440) : null)
				->setNbPeople(mt_rand(0, 1) == 1 ? mt_rand(1, 49) : null)
				->setDifficutly(mt_rand(0, 1) == 1 ? mt_rand(1, 5) : null)
				->setDescription($this->faker->text(200))
				->setPrice(mt_rand(1, 1000))
				->setIsFavorite(mt_rand(0, 1) == 1 ? true : false);


			for ($k = 0; $k < mt_rand(5, 15); $k++) {

				$recipie->addIngredient($ingredient[mt_rand(0, count($ingredient) - 1)]);
			}

			$manager->persist($recipie);
		}



		for ($i = 0; $i < 10; $i++) {
			$user = new User();
			$user->setFullName($this->faker->name())
				 ->setPseudo(mt_rand(0, 1) === 1 ? $this->faker->firstName() : null)
				 ->setEmail($this->faker->email())
				 ->setRoles(['ROLE USER'])
				 ->setPlainPassword('password');

		
				$manager->persist($user);
			
			
		}


		$manager->flush();
	}
}
