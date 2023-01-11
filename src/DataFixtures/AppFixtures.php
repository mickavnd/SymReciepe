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

	

	public function __construct()
	{
		$this->faker = Factory::create('fr_FR');
		
	}

	/**
	 * function fixture
	 *
	 * @param ObjectManager $manager
	 * @return void
	 */
	public function load(ObjectManager $manager): void
	{
		//fixture User
		$user=[];
		for ($i = 0; $i < 10; $i++) {
			$user = new User();
			$user->setFullName($this->faker->name())
				->setPseudo(mt_rand(0, 1) === 1 ? $this->faker->firstName() : null)
				->setEmail($this->faker->email())
				->setRoles(['ROLE_USER'])
				->setPlainPassword('password');

			$users[] = $user;
			$manager->persist($user);
		}


		//ingrdieent commande d:f:l(doctrine:fixture:load )
		$ingredient = [];
		for ($i = 1; $i < 50; $i++) {
			$ingrediant = new Ingredient;
			$ingrediant->setName($this->faker->word())
				->setPrice(mt_rand(0, 100))
				->setUser($users[mt_rand(0, count($users) -1)]);

			$ingredient[] = $ingrediant;
			$manager->persist($ingrediant);
		}

		// fixture recipie
		for ($j = 0; $j < 25; $j++) {

			$recipie = new Recipe();
			$recipie->setName($this->faker->word())
				->setTime(mt_rand(0, 1) == 1 ? mt_rand(1, 1440) : null)
				->setNbPeople(mt_rand(0, 1) == 1 ? mt_rand(1, 49) : null)
				->setDifficutly(mt_rand(0, 1) == 1 ? mt_rand(1, 5) : null)
				->setDescription($this->faker->text(200))
				->setPrice(mt_rand(1, 1000))
				->setIsFavorite(mt_rand(0, 1) == 1 ? true : false)
				->setUser($users[mt_rand(0, count($users) -1)]);


			for ($k = 0; $k < mt_rand(5, 15); $k++) {

				$recipie->addIngredient($ingredient[mt_rand(0, count($ingredient) - 1)]);
			}

			$manager->persist($recipie);
		}


		
		


		$manager->flush();
	}
}
