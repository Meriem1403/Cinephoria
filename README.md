# 🎬 Cinéphoria

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

## 🚀 Installation manuelle (Symfony sans Docker)

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