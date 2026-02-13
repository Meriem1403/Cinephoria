# Documentation 04 - Déploiement Cinephoria sur Railway

## Objectif

Déployer Cinephoria sur **Railway** (PaaS conteneurisé) pour une démonstration d'examen : build à partir du dépôt GitHub, base MySQL managée, déploiement continu.

---

## 1) Prérequis

- Compte [Railway](https://railway.app/) (connexion GitHub)
- Dépôt GitHub **Cinephoria** à jour (branche `main`)
- Projet Railway avec au moins :
  - un **service applicatif** (build depuis Dockerfile)
  - un **service MySQL** (template ou ajout manuel)

---

## 2) Architecture sur Railway

| Composant | Rôle |
|-----------|------|
| **Service Cinephoria** | Application Symfony (PHP 8.2 + Apache), build via Dockerfile, déclenché par push sur `main` |
| **Service MySQL** | Base de données managée par Railway ; l’URL est fournie via variable d’environnement |

Le service Cinephoria est déployé à partir du **Dockerfile** à la racine du projet. Au démarrage du conteneur, l’**entrypoint** exécute migrations, installation des assets des bundles, puis lance Apache.

---

## 3) Création du projet et des services

1. **Nouveau projet** : dans Railway, créer un projet (ex. « Cinephoria »).
2. **MySQL** : ajouter un service **MySQL** (template ou « Add MySQL »). Noter les variables exposées (ex. `MYSQL_URL`).
3. **Service applicatif** : « New » → **GitHub Repo** → sélectionner le dépôt Cinephoria.
4. **Root Directory** : laisser vide (racine du dépôt).
5. **Build** : Railway détecte le Dockerfile et lance un build d’image.
6. **Déploiement** : après chaque push sur la branche suivie (ex. `main`), un nouveau déploiement est déclenché.

---

## 4) Variables d'environnement (service Cinephoria)

Dans **Variables** du service Cinephoria, configurer :

| Variable | Valeur | Remarque |
|----------|--------|----------|
| `DATABASE_URL` | `${{NomDuServiceMySQL.MYSQL_URL}}` | Référence au service MySQL. **Remplacer `NomDuServiceMySQL`** par le nom exact du service MySQL dans le projet (ex. `MySQL` ou `mysql`, sensible à la casse). Railway injecte alors l’URL interne (réseau privé). |
| `APP_ENV` | `prod` | Environnement Symfony |
| `APP_DEBUG` | `0` | Désactiver le mode debug |
| `APP_SECRET` | *clé longue aléatoire* | Générer une clé sécurisée (ex. `openssl rand -hex 32`) |
| `LOAD_DEMO_DATA` | `1` (optionnel) | Si défini à `1`, au démarrage le conteneur charge les fixtures (films, etc.) et génère des séances sur 14 jours. Utile pour une démo avec une base vide. Après le premier déploiement, tu peux retirer la variable. |

Ne pas commiter de secrets : tout est configuré dans l’interface Railway.

---

## 5) Fichiers techniques du déploiement

- **`Dockerfile`** (racine)  
  - Image de base : `php:8.2-apache`  
  - Extensions PHP : intl, pdo_mysql, zip ; installation de wkhtmltopdf pour les PDF  
  - Un seul MPM Apache (prefork) + mod_rewrite  
  - Composer, Node/Yarn, build des assets au build  
  - Entrypoint personnalisé

- **`docker/entrypoint.sh`**  
  - Force `APP_ENV=prod`  
  - Corrige la config MPM Apache au démarrage (éviter « More than one MPM loaded »)  
  - Migrations Doctrine en prod  
  - `assets:install` pour EasyAdmin / ApiPlatform  
  - Démarrage d’Apache

- **`docker/000-default.conf`**  
  - DocumentRoot = `/var/www/html/public`  
  - Règles de réécriture pour Symfony (front controller)

---

## 6) Exposer le service (URL publique)

Par défaut le service peut être « Unexposed ».

1. Onglet **Settings** du service Cinephoria.
2. Section **Networking** → **Generate Domain** (ou **Public Networking**).
3. Railway génère une URL (ex. `cinephoria-production-xxxx.up.railway.app`).

Conserver cette URL pour les tests et les preuves.

---

## 7) Vérification post-déploiement

- **Build** : les logs de build doivent se terminer sans erreur (Composer, Yarn, assets).
- **Deploy** : pas d’erreur « More than one MPM loaded » ; Apache démarre correctement.
- **Application** :  
  - Page d’accueil accessible  
  - Liste des films visible  
  - Parcours de réservation fonctionnel  
  - Page de confirmation et téléchargement du PDF e-ticket (si wkhtmltopdf OK)

En cas d’erreur 500 : vérifier les variables d’environnement et les logs du déploiement (migrations, permissions, `DATABASE_URL`).

---

## 7bis) Dépannage : « Connection refused » (MySQL)

Si l’application affiche **SQLSTATE[HY000] [2002] Connection refused** :

1. **Ne pas utiliser le `.env` local dans l’image**  
   Le projet contient un **`.dockerignore`** qui exclut `.env` du build. Ainsi, en prod seul le `DATABASE_URL` défini dans Railway est utilisé (et non une URL en `127.0.0.1`).

2. **Vérifier la variable `DATABASE_URL`**  
   - Dans Railway → service **Cinephoria** → **Variables**.  
   - Il doit y avoir une entrée **`DATABASE_URL`** dont la **valeur** est une **référence** au service MySQL, par ex. :  
     `${{MySQL.MYSQL_URL}}`  
     (adapter le nom du service : si ton MySQL s’appelle `mysql` en minuscules, utiliser `${{mysql.MYSQL_URL}}`).

3. **Les deux services dans le même projet**  
   Le service MySQL et le service Cinephoria doivent être dans le **même projet** Railway pour que la référence soit résolue.

4. **Vérifier que MySQL est démarré**  
   Dans le tableau de bord, le service MySQL doit être **Online**. Après un redéploiement, attendre quelques secondes que MySQL soit prêt avant de recharger l’app.

Après modification des variables, Railway redéploie automatiquement. Attendre la fin du déploiement puis réessayer.

---

## 8) Points de vigilance

- Ne jamais commiter `.env` ou secrets ; tout passe par les variables Railway.
- Le coût Railway dépend du plan (heures, mémoire) ; surveiller l’usage si compte gratuit.
- Si le conteneur redémarre en boucle : vérifier les logs (migrations, connexion MySQL, erreurs PHP/Apache).

---

## 9) Preuves à fournir (dossier d'examen)

- Capture du **tableau de bord Railway** : projet, services Cinephoria + MySQL.
- Capture des **Variables** du service Cinephoria (masquer les valeurs sensibles).
- Capture des **Deploy Logs** : build réussi, Apache démarré sans erreur MPM.
- **URL publique** de l’application (domaine Railway).
- Capture de l’**application en ligne** : accueil, liste films, réservation ou e-ticket.

---

## 10) Résumé des commandes (référence)

Aucune commande manuelle côté Railway : le déploiement est déclenché par **push sur la branche connectée** (ex. `main`). En local, pour pousser une nouvelle version :

```bash
git add .
git commit -m "Message"
git push origin main
```

Railway rebuild et redéploie automatiquement.
