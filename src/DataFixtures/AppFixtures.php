<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\Seller;
use App\Entity\Option;
use App\Entity\Reparation;
use App\Entity\Vehicle;
use App\Entity\Sale;
use App\Entity\Supplier;
use App\Entity\Supply;
use App\Entity\VehicleOption;
use App\Enum\FuelType;
use App\Enum\TransmissionType;
use App\Enum\VehicleStatus;
use App\Enum\VehicleType;
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
        // Création de 20 clients
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

        // Création de 20 vendeurs
        for ($i = 0; $i < 20; $i++) {
            $seller = new Seller();
            $seller
                ->setName($this->faker->lastName())
                ->setPrenom($this->faker->firstName())
                ->setEmail($this->faker->unique()->email())
                ->setTelephone($this->faker->unique()->phoneNumber())
                ->setDateEmbauche(new \DateTime());

            $manager->persist($seller);
        }

        // Création de 10 options
        $options = [];
        for ($i = 0; $i < 10; $i++) {
            $option = new Option();
            $option->setName($this->faker->word());

            $manager->persist($option);
            $options[] = $option;
        }

        // Création de 20 véhicules
        $vehicles = [];
        for ($i = 0; $i < 20; $i++) {
            $vehicle = new Vehicle();
            $vehicle
                ->setBrand($this->faker->company())
                ->setModel($this->faker->word())
                ->setYear($this->faker->year())
                ->setPrice($this->faker->randomFloat(2, 5000, 50000))
                ->setMileage($this->faker->numberBetween(1000, 200000))
                ->setType(VehicleType::cases()[array_rand(VehicleType::cases())])
                ->setFuelType(FuelType::cases()[array_rand(FuelType::cases())])
                ->setTransmissionType(TransmissionType::cases()[array_rand(TransmissionType::cases())])
                ->setStatus(VehicleStatus::cases()[array_rand(VehicleStatus::cases())])
                ->setCreatedAt(new \DateTime());

            $manager->persist($vehicle);
            $vehicles[] = $vehicle;
        }

        // Création de 20 réparations
        for ($i = 0; $i < 20; $i++) {
            $repair = new Reparation();
            $repair
                ->setVehicle($vehicles[$this->faker->numberBetween(0, 19)])
                ->setDescription($this->faker->sentence())
                ->setRepairDate($this->faker->dateTimeThisDecade())
                ->setCost($this->faker->randomFloat(2, 50, 500));

            $manager->persist($repair);
        }

        // Création de 10 ventes
        for ($i = 0; $i < 10; $i++) {
            $sale = new Sale();
            $sale
                ->setSaleDate($this->faker->dateTimeThisDecade())
                ->setSalePrice($this->faker->randomFloat(2, 1000, 50000))
                ->setVehicle($vehicles[$this->faker->numberBetween(0, 19)]);

            $manager->persist($sale);
        }

        // Création de 10 fournisseurs
        $suppliers = [];
        for ($i = 0; $i < 10; $i++) {
            $supplier = new Supplier();
            $supplier
                ->setName($this->faker->company())
                ->setContact($this->faker->name())
                ->setAdress($this->faker->address())
                ->setEmail($this->faker->unique()->email())
                ->setPhone($this->faker->unique()->phoneNumber());

            $manager->persist($supplier);
            $suppliers[] = $supplier;
        }

        // Création de 20 approvisionnements
        for ($i = 0; $i < 20; $i++) {
            $supply = new Supply();
            $supply
                ->setSupplier($suppliers[$this->faker->numberBetween(0, 9)])
                ->setVehicle($vehicles[$this->faker->numberBetween(0, 19)])
                ->setQuantity($this->faker->numberBetween(1, 10))
                ->setSupplyDate($this->faker->dateTimeThisDecade())
                ->setPurchasePrice($this->faker->randomFloat(2, 100, 1000));

            $manager->persist($supply);
        }

        // Création de 10 options de véhicules
        for ($i = 0; $i < 10; $i++) {
            $vehicleOption = new VehicleOption();
            $numOptions = $this->faker->numberBetween(1, 5);
            $selectedOptions = $this->faker->randomElements($options, $numOptions);

            foreach ($selectedOptions as $option) {
                $vehicleOption->addOption($option);
            }

            $manager->persist($vehicleOption);
        }

        $manager->flush();
    }
}
