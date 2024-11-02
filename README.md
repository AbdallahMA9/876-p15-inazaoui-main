
# Mon Projet Symfony InaZaoui

## Description

Ceci est un projet Symfony pour la gestion des médias et des albums. Il permet aux utilisateurs de télécharger des fichiers multimédias, de les organiser dans des albums et de gérer leurs contenus.

## Prérequis

Avant de commencer, assurez-vous d'avoir installé les éléments suivants :

- [PHP](https://www.php.net/downloads) (version 8.0 ou supérieure)
- [Composer](https://getcomposer.org/download/)
- [Symfony CLI](https://symfony.com/download)
- Une base de données (SQLite, MySQL, etc.)

## Installation

1. **Clonez le dépôt :**

   ```bash
   git clone https://github.com/AbdallahMA9/876-p15-inazaoui-main
   
   ```

2. **Installez les dépendances avec Composer :**

   ```bash
   composer install
   ```

3. **Configurez votre base de données :**
   - Modifiez le fichier `.env` pour configurer la connexion à votre base de données.

4. **Créez la base de données :**

   ```bash
   php bin/console doctrine:database:create
   ```

5. **Mettez à jour le schéma de la base de données :**

   ```bash
   php bin/console doctrine:schema:update --force
   ```

6. **Chargez les fixtures (optionnel) :**

   ```bash
   php bin/console doctrine:fixtures:load
   ```

## Utilisation

Pour démarrer le serveur de développement intégré de Symfony, exécutez :

```bash
php bin/console server:start
```

Accédez à l'application à l'adresse suivante : [http://localhost:8000](http://localhost:8000)

## Tests

Pour exécuter les tests, utilisez PHPUnit :

```bash
php bin/phpunit
```

Assurez-vous d'avoir une base de données de test configurée.

