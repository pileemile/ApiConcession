<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR'); // Faker en français
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 20; $i++) {
            $customer = new Customer();
            $customer
                ->setLastName($this->faker->lastName())
                ->setFirstName($this->faker->firstName())
                ->setEmail($this->faker->unique()->email())
                ->setPhone($this->faker->unique()->phoneNumber())
                ->setAdress($this->faker->address())
                ->setRegisterDate(new \DateTime());


            $manager->persist($customer);
        }

        $manager->flush();
    }
}
