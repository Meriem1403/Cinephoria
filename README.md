# 🎬 Cinéphoria Web - Experience User & Admin


![Platform](https://img.shields.io/badge/platform-web-lightgrey)
![Symfony](https://img.shields.io/badge/Symfony-7.0-blue)
![PHP](https://img.shields.io/badge/PHP-8.4.5-777bb3)
![Status](https://img.shields.io/badge/status-in%20progress-yellow)
![License: MIT](https://img.shields.io/badge/license-MIT-green)


**Projet de TP – Développeur d’Applications (CDA) – 2025**  
Cinéphoria est une application **multi-plateformes** (web, mobile, desktop) de gestion de séances de cinéma.

Le projet permet :
- la réservation de séances,
- la notation de films,
- la gestion des utilisateurs (visiteurs, membres, employés, administrateurs),
- le signalement et suivi des incidents techniques.

---

## 📌 Objectif pédagogique

Ce projet est réalisé dans le cadre de la validation du **TP CDA**. Il permet de démontrer les compétences en :
- Développement web fullstack (Symfony, PHP, JS)
- Développement mobile (React Native / Expo)
- Développement bureautique (Python Tkinter)
- Utilisation de bases de données relationnelle et NoSQL
- Versionning avec Git & GitHub
- Gestion de projet (kanban, documentation technique)
- Structuration MVC et séparation des responsabilités

---

## 📁 Technologies utilisées

| Plateforme | Technologies                                                            |
|------------|-------------------------------------------------------------------------|
| Web        | PHP 8.4.5, Symfony 7, HTML5, CSS3, JavaScript, Tailwind CSS, Webpack Encore |
| Mobile     | React Native / Expo                                                     |
| Bureau     | Python / Tkinter                                                        |
| Base de données | MySQL (relationnelle), MongoDB (NoSQL)                                 |
| Outils     | Git, GitHub, Composer, Node.js, Yarn, Mailpit, Apache24, HeidiSQL       |

---

## 🚧 Statut actuel

🧪 Projet **en développement**  
📆 Rendu prévu le **22 mai 2025**

---

---

## 🚀 Installation (mannuelle ou avec Docker)

```bash
# Cloner le dépôt
git clone https://github.com/Meriem1403/Cinephoria.git
cd Cinephoria

# Installer les dépendances
composer install
yarn install
yarn dev

# Créer la base de données
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

# Lancer le serveur local Symfony (via PHP natif)
php -S 0.0.0.0:8000 -t public


---

## 🐳 Lancer le projet avec Docker (environnement complet)

> Cette méthode est recommandée pour exécuter le projet avec PHP, MySQL et Mailpit sans configuration manuelle.

### 📦 Prérequis

- Docker Desktop installé (https://www.docker.com/products/docker-desktop)
- Git installé

### ▶️ Commandes à exécuter

```bash
# Cloner le projet
git clone https://github.com/Meriem1403/cinephoria-web.git
cd cinephoria-web

# Lancer tous les services
docker compose up --build
