# Cinéphoria

Plateforme de gestion de séances de cinéma : réservation, notation, comptes visiteurs, employés et administrateurs.

Projet réalisé pour le titre de concepteur développeur d'applications (2025). L'application mobile est dans un dépôt séparé.

## Stack

- PHP, Symfony
- API Platform, EasyAdmin, Doctrine
- Docker

## Démarrage

```bash
cp .env.example .env
docker compose up --build
```

Renseigner `APP_SECRET` et `DATABASE_URL` dans `.env`. Ce fichier n'est pas versionné.
