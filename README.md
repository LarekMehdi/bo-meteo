# bo-meteo

Backend Symfony pour la gestion des prévisions météo et des villes via l’API Open Meteo.

## Technologies

- Langage: PHP 8.x
- Framework: Symfony 6.x
- Base de données: MariaDb
- Port API: 8000 par defaut
- Outils supplémentaires: Docker, Docker Compose, - Adminer, LexikJWTAuthenticationBundle

## Quick Start

### Copier le .env en .env.local

Renseigner les variables d'environnement

```bash

DATABASE_URL="mysql://user:pass@127.0.0.1:3306/db?charset=utf8mb4"

MYSQL_DATABASE="db"
MYSQL_USER="user"
MYSQL_PASSWORD="pass"
MYSQL_ROOT_PASSWORD="root_pass"

###> lexik/jwt-authentication-bundle ###
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=909de91994fa7b6bb22c210d1e0ba59d1191871dcecf2955f010902e9238be59
###< lexik/jwt-authentication-bundle ###

###> nelmio/cors-bundle ###
CORS_ALLOW_ORIGIN='^https?://(localhost|127\.0\.0\.1)(:[0-9]+)?$'
###< nelmio/cors-bundle ###
```

### Installer les dépendances PHP

```bash
composer install
```

### Générer les clés JWT (Lexik)

```bash
mkdir -p config/jwt
php bin/console lexik:jwt:generate-keypair
```

### Sur Linux, l'utilisateur doit avoir les droits sur les clefs

```bash
chmod 600 config/jwt/private.pem
chmod 600 config/jwt/public.pem
```

### Lancer la base de données via Docker

```bash
docker compose --env-file .env.local up -d
```

### Démarrer le serveur Symfony

```bash
php bin/console serve
```

ou

```bash
php -S localhost:8000 -t public
```

ou

```bash
###> pour un port custom
php bin/console --port=8001
```

### Créer les tables en bdd

```bash
php bin/console doctrine:migrations:migrate
```

### Accéder à l’API

```bash
http://localhost:8000
```

### Adminer pour la DB

```bash
http://localhost:8082
```

Renseigner les infos de connection:

- Système: MySQL / MariaDB
- Serveur: database (le nom du service docker)
- Utilisateur: [MYSQL_USER]
- Mot de passe: [MYSQL_PASSWORD]
- Base de données: [MYSQL_DATABASE]

### Lancer les tests

```bash
###> tous les tests
php bin/phpunit

###> tous les tests avec logs
php bin/phpunit --debug

###> un test précis
php bin/phpunit tests/Service/SearchForecastHistoryServiceTest.php

```

## Configuration

Variables d’environnement: .env.local

Contient la configuration du projet et les secrets (base de données, JWT, CORS).

Exemple:

```bash
DATABASE_URL=mysql://user:password@db:3306/bo_meteo
CORS_ALLOW_ORIGIN=http://localhost:5173
JWT_PASSPHRASE=your_jwt_passphrase
```

JWT: config/jwt contient la paire de clés pour JWT
