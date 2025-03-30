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
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures extends Fixture
{
    private Generator $faker;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->faker = Factory::create('fr_FR');
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Création de clients
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

        // Création de vendeurs
        for ($i = 0; $i < 10; $i++) {
            $seller = new Seller();
            $seller
                ->setName($this->faker->lastName())
                ->setPrenom($this->faker->firstName())
                ->setEmail($this->faker->unique()->email())
                ->setTelephone($this->faker->unique()->phoneNumber())
                ->setDateEmbauche(new \DateTime());
            $manager->persist($seller);
        }

        // Options réelles
        $optionNames = [
            "Climatisation", "GPS", "Sièges chauffants", "Toit ouvrant",
            "Caméra de recul", "Régulateur de vitesse", "Freinage automatique",
            "Écran tactile", "Bluetooth", "Phares LED"
        ];

        $options = [];
        foreach ($optionNames as $name) {
            $option = new Option();
            $option->setName($name);
            $manager->persist($option);
            $options[] = $option;
        }

        // Marques et modèles réalistes
        $brandsModels = [
            "Toyota" => ["Yaris", "Corolla", "RAV4"],
            "Renault" => ["Clio", "Megane", "Captur"],
            "Peugeot" => ["208", "308", "3008"],
            "BMW" => ["Serie 1", "Serie 3", "X5"],
            "Audi" => ["A1", "A3", "Q5"]
        ];

        // Création des véhicules
        $vehicles = [];
        foreach ($brandsModels as $brand => $models) {
            foreach ($models as $model) {
                $vehicle = new Vehicle();
                $vehicle
                    ->setBrand($brand)
                    ->setModel($model)
                    ->setYear($this->faker->numberBetween(2015, 2024))
                    ->setPrice($this->faker->randomFloat(2, 8000, 70000))
                    ->setMileage($this->faker->numberBetween(5000, 150000))
                    ->setType($this->faker->randomElement(VehicleType::cases()))
                    ->setFuelType($this->faker->randomElement(FuelType::cases()))
                    ->setTransmissionType($this->faker->randomElement(TransmissionType::cases()))
                    ->setStatus($this->faker->randomElement(VehicleStatus::cases()))
                    ->setCreatedAt(new \DateTime());

                // Ajout d'options réalistes
                $numOptions = $this->faker->numberBetween(2, 5);
                foreach ($this->faker->randomElements($options, $numOptions) as $option) {
                    $vehicleOption = new VehicleOption();
                    $vehicleOption->addOption($option);
                    $manager->persist($vehicleOption);
                }

                $manager->persist($vehicle);
                $vehicles[] = $vehicle;
            }
        }

        // Création des ventes et réparations
        for ($i = 0; $i < 10; $i++) {
            $sale = new Sale();
            $sale
                ->setSaleDate($this->faker->dateTimeThisDecade())
                ->setSalePrice($this->faker->randomFloat(2, 10000, 50000))
                ->setVehicle($vehicles[array_rand($vehicles)]);
            $manager->persist($sale);
        }

        for ($i = 0; $i < 10; $i++) {
            $repair = new Reparation();
            $repair
                ->setVehicle($vehicles[array_rand($vehicles)])
                ->setDescription($this->faker->sentence())
                ->setRepairDate($this->faker->dateTimeThisDecade())
                ->setCost($this->faker->randomFloat(2, 100, 2000));
            $manager->persist($repair);
        }

        // Création de fournisseurs
        $suppliers = [];
        for ($i = 0; $i < 5; $i++) {
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

        // Création d'approvisionnements
        for ($i = 0; $i < 10; $i++) {
            $supply = new Supply();
            $supply
                ->setSupplier($suppliers[array_rand($suppliers)])
                ->setVehicle($vehicles[array_rand($vehicles)])
                ->setQuantity($this->faker->numberBetween(1, 5))
                ->setSupplyDate($this->faker->dateTimeThisDecade())
                ->setPurchasePrice($this->faker->randomFloat(2, 5000, 40000));
            $manager->persist($supply);
        }

        // Création des utilisateurs
        $users = [];

        // Création d'un administrateur
        $admin = new User();
        $admin->setEmail('admin@example.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));
        $manager->persist($admin);
        $users[] = $admin;

        // Création de vendeurs (ROLE_SELLER)
        for ($i = 0; $i < 5; $i++) {
            $seller = new User();
            $seller->setEmail($this->faker->unique()->email());
            $seller->setRoles(['ROLE_SELLER']);
            $seller->setPassword($this->passwordHasher->hashPassword($seller, 'seller123'));
            $manager->persist($seller);
            $users[] = $seller;
        }

        // Création de clients (ROLE_CUSTOMER)
        for ($i = 0; $i < 10; $i++) {
            $customer = new User();
            $customer->setEmail($this->faker->unique()->email());
            $customer->setRoles(['ROLE_CUSTOMER']);
            $customer->setPassword($this->passwordHasher->hashPassword($customer, 'customer123'));
            $manager->persist($customer);
            $users[] = $customer;
        }

        $manager->flush();
    }
}
