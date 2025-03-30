# API Concession

Le but de cette API est de permettre la gestion complète d'une concession automobile. Elle couvre la vente de véhicules neufs et d'occasion, les réparations automobiles (rapides ou importantes) ainsi que la gestion du magasin de pièces détachées.

🔗 **Repository GitHub** : [ApiConcession](https://github.com/pileemile/ApiConcession)

A [Docker](https://www.docker.com/)-based installer and runtime for the [Symfony](https://symfony.com) web framework,
with [FrankenPHP](https://frankenphp.dev) and [Caddy](https://caddyserver.com/) inside!

![CI](https://github.com/dunglas/symfony-docker/workflows/CI/badge.svg)

## Getting Started

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/) (v2.10+)
2. Run `docker compose build --no-cache` to build fresh images
3. Run `docker compose up --pull always -d --wait` to set up and start a fresh Symfony project
4. Open `https://localhost` in your favorite web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)
5. Run `docker compose down --remove-orphans` to stop the Docker containers.

## Bibliothèques Utilisées

Le projet utilise plusieurs bibliothèques Symfony et PHP pour le développement et les tests :

### 1. **FakerPHP** (Génération de données factices)

FakerPHP permet de générer des données aléatoires pour alimenter la base de données avec des valeurs factices.

- Installation :
  ```sh
  composer require fakerphp/faker --dev
  ```

### 2. **PHPUnit** (Tests unitaires)

PHPUnit est utilisé pour exécuter les tests unitaires et vérifier le bon fonctionnement des composants du projet.

- Installation :
  ```sh
  composer require --dev phpunit/phpunit
  ```

### 3. **NelmioApiDocBundle** (Documentation API avec Swagger)

NelmioApiDocBundle est utilisé pour générer la documentation Swagger de l'API Symfony.

- Installation :
  ```sh
  composer require nelmio/api-doc-bundle
  ```

### 4. **DoctrineFixturesBundle** (Données de test en base de données)

Permet d'insérer des données de test en base de données avec des fixtures.

- Installation :
  ```sh
  composer require --dev doctrine/doctrine-fixtures-bundle
  ```

### 5. **Symfony MakerBundle** (Génération de code)

MakerBundle facilite la génération d'entités, de contrôleurs et d'autres fichiers Symfony.

- Installation :
  ```sh
  composer require symfony/maker-bundle --dev
  ```

### 6. **Doctrine Migrations** (Gestion des migrations de base de données)

Doctrine Migrations permet de gérer les évolutions du schéma de la base de données.

- Installation :
  ```sh
  composer require doctrine/migrations
  ```

## Exécution des Tests

Pour exécuter les tests PHPUnit, utilisez la commande suivante :

```sh
php bin/phpunit
```

## Chargement des Fixtures

Pour insérer des données de test dans la base de données :

```sh
php bin/console doctrine:fixtures:load
```

---
# Configuration de l'Environnement

Avant de démarrer l'application, assurez-vous de configurer les variables d'environnement nécessaires.

## Exemple de fichier `.env.local`

```dotenv
APP_ENV=dev
APP_SECRET=your_secret_key
DATABASE_URL="mysql://root\:password@localhost:3306/db_name"

```
 # Clés API et Services Tiers
Si vous utilisez des services tiers (comme un service d'authentification ou un fournisseur d'email), ajoutez vos clés API dans ce fichier.

# Exemple de Requêtes API
Voici quelques exemples de requêtes pour interagir avec l'API.

## Récupérer la liste des véhicules

```` 
curl -X GET https://localhost/api/vehicles

 ````
## Ajouter un nouveau véhicule
```
 curl -X POST https://localhost/api/vehicles -H "Content-Type: application/json" -d '{"brand": "Toyota", "model": "Corolla", "year": 2022, "price": 20000}'

 ```

# Gestion des Erreurs
L'API renvoie des erreurs dans un format JSON standard avec le code HTTP correspondant.

## Exemple d'erreur 404 (Not Found)

```
{
  "status": 404,
  "message": "Resource not found"
}

```

## Exemple d'erreur 400 (Bad Request)

```
{
  "status": 400,
  "message": "Invalid data provided"
}

```

# Sécurité
L'API utilise JWT pour l'authentification. Vous devrez inclure un jeton d'authentification dans l'en-tête Authorization pour accéder aux ressources sécurisées.

## Exemple de header pour l'authentification

```
Authorization: Bearer <votre_token_jwt>

```

# Architecture du Projet
L'architecture de l'API est divisée en plusieurs modules, chacun responsable de différentes fonctionnalités de la concession automobile.

- Véhicules : Gestion des véhicules neufs et d'occasion.
- Réparations : Suivi des réparations automobiles.
- Magasin de pièces détachées : Gestion des stocks de pièces.
- Vente : Suivi des ventes de véhicules.



Ce README couvre l'installation et l'utilisation des bibliothèques clés de votre projet Symfony. 

