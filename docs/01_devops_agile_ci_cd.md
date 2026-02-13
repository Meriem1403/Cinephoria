# Documentation 01 - Fondamentaux DevOps, Agile, CI/CD et YAML (avec preuves de production)

## 1) Contexte du projet

**Projet:** Cinephoria (application web Symfony)  
**Objectif pedagogique:** demonstrer la capacite a preparer le deploiement d'une application securisee dans une demarche DevOps.

Ce document constitue une preuve de comprehension et d'application des notions suivantes:
- bases de la demarche DevOps;
- methodes Agile pour le developpement logiciel;
- bases d'un environnement de test;
- integration continue (CI) et deploiement continu (CD);
- introduction et usage du YAML.

> Important: cette version est orientee **preuves de production**.  
> Chaque notion est reliee a un artefact concret dans le repository (fichier, script, workflow, execution verifiable).

---

## 2) Les bases de la demarche DevOps

La demarche DevOps vise a rapprocher le developpement (Dev) et l'exploitation (Ops) pour livrer plus vite, de facon fiable et securisee.

Principes appliques sur Cinephoria:
- **collaboration:** code, infrastructure et deploiement sont versionnes dans GitHub;
- **automatisation:** build (GitHub Actions ou local), deploiement continu via Railway au push;
- **mesure et feedback:** verification des statuts de jobs CI/CD avant validation;
- **amelioration continue:** correction des pipelines et scripts apres incidents.

Benefices attendus:
- reduction des erreurs manuelles;
- meilleure repetabilite des deploiements;
- reduction du temps entre developpement et mise en ligne.

---

## 3) Methodes Agile pour le developpement logiciel

Le projet suit une logique Agile avec iterations courtes:
- priorisation des fonctionnalites par valeur (reservation, e-ticket, admin);
- livraison incrementale (fonctionnalite par fonctionnalite);
- adaptation continue selon retours (UI booking, fixtures, PDF).

Application concrete:
- lotissement des evolutions en petits increments;
- commits reguliers et traces;
- verification frequente de la stabilite applicative.

Lien Agile x DevOps:
- Agile organise le flux de travail;
- DevOps automatise la livraison et la qualite technique.

---

## 4) Introduction au YAML

Le YAML est un format declaratif utilise pour definir des workflows d'automatisation.

Dans ce projet, YAML est utilise pour:
- decrire les etapes CI/CD dans GitHub Actions;
- definir les jobs, triggers, permissions et variables;
- standardiser les executions de build/deploiement.

Exemples de fichiers:
- `.github/workflows/Cinephoria-build.yaml` (etapes de build)
- `.github/workflows/Cinephoria-orchestration.yaml` (orchestration)

Le deploiement en production est assure par **Railway** (liaison GitHub → build Dockerfile a chaque push). Voir `docs/04_deploiement_railway.md`.

---

## 5) Mise en place de l'integration continue (CI)

Objectif CI:
- verifier automatiquement qu'une version est construisible et deployable.

Elements CI identifies dans le projet:
- declencheurs automatiques sur push/workflow;
- etape de build front (`yarn install`, `yarn build`);
- etape d'installation dependances PHP (`composer install`);
- packaging d'artefact de deploiement.

Valeur pour l'examen:
- preuve d'automatisation du build;
- preuve d'un pipeline reproductible;
- preuve d'industrialisation du cycle de livraison.

---

## 6) Mise en place de la livraison/deploiement continu (CD)

Objectif CD:
- deployer automatiquement une version validee vers une cible d'hebergement.

Elements CD identifies:
- deploiement continu via **Railway** (connexion du depot GitHub au projet Railway);
- a chaque push sur la branche suivie, Railway rebuild l'image (Dockerfile) et redéploie;
- variables d'environnement et base MySQL configurees sur Railway.

Preuves observables:
- run GitHub Actions (build) avec statut;
- deploiements Railway declenches au push (logs et tableau de bord);
- version associee au SHA Git.

---

## 7) Bases d'un environnement de test

Un environnement de test doit etre isole, reproductible et proche de la production.

Application sur Cinephoria:
- environnement Docker compose pour standardiser les services;
- base de donnees alimentee avec fixtures;
- scripts de chargement des donnees de test.

Objectifs techniques:
- reproduire les cas d'usage fonctionnels;
- valider les migrations et la coherence des donnees;
- verifier les parcours critiques avant mise en production.

---

## 8) Preparer le deploiement d'une application

Preparation attendue:
- definir prerequis techniques;
- externaliser la configuration sensible (secrets);
- verifier la construction applicative;
- planifier les verifications post-deploiement.

Checklist de preparation (niveau fondation):
- code versionne et branche de reference definie;
- pipeline CI/CD present et documente;
- variables d'environnement listees;
- procedure de rollback definie;
- plan de test disponible.

---

## 9) Points de vigilance securite (fondamentaux)

Pour une application securisee, les elements suivants sont obligatoires:
- ne pas exposer de secrets dans les workflows;
- utiliser les secrets GitHub/plateforme d'hebergement;
- limiter les permissions des jobs CI/CD;
- tracer les deploiements (qui, quoi, quand).

Amelioration immediate recommandee:
- remplacer toute valeur sensible en dur dans les workflows par des secrets.

---

## 10) Matrice de preuves (competence -> production realisee)

| Competence a prouver | Production / Artefact | Emplacement |
|---|---|---|
| Bases de la demarche DevOps | Pipelines versionnes + scripts d'automatisation | `.github/workflows/`, `scripts/` |
| Methodes Agile | Historique de livraison incrementale (commits, evolutions fonctionnelles) | historique GitHub du projet |
| Introduction YAML | Workflows CI/CD en YAML | `.github/workflows/Cinephoria-build.yaml`, `.github/workflows/Cinephoria-orchestration.yaml` |
| Mise en place CI | Build front + install composer automatise | `.github/workflows/Cinephoria-build.yaml` |
| Mise en place CD | Deploiement continu vers Railway (liaison GitHub, build Dockerfile a chaque push) | Railway ; `docs/04_deploiement_railway.md` |
| Environnement de test | Environnement conteneurise + fixtures | `compose.yaml`, `scripts/load-fixtures.sh`, `src/DataFixtures/` |
| Preparation du deploiement | Dockerfile, compose, scripts et workflow de livraison | `Dockerfile`, `compose.yaml`, `.github/workflows/` |
| Documentation du processus | Dossier `docs/` | `docs/` |

---

## 11) Preuves d'execution a joindre dans le rendu

Pour transformer ce document en preuve exam, joindre les captures suivantes:

1. **Capture GitHub Actions - build OK**
   - onglet Actions, run du workflow de build (Cinephoria-build ou orchestration)
   - statut vert + SHA du commit.

2. **Capture deploiement Railway**
   - tableau de bord Railway : services Cinephoria + MySQL ;
   - ou Deploy Logs montrant un deploy reussi apres un push.

3. **Capture des fichiers YAML**
   - visualisation de `Cinephoria-build.yaml` montrant les etapes `yarn` et `composer`.

4. **Capture execution locale environnement de test**
   - `docker compose up --build`
   - puis chargement fixtures via `scripts/load-fixtures.sh`.

5. **Capture resultat applicatif**
   - page accueil en ligne ou en test + parcours reservation.

---

## 12) Commandes de verification (reproductibilite)

Commandes minimales a executer et archiver dans le rapport:

```bash
docker compose up --build -d
./scripts/load-fixtures.sh
docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction
docker compose exec php php bin/console app:showtimes:generate --days=14
```

Commandes CI/CD (preuve pipeline):
- push sur la branche connectee a Railway (`main`) pour declencher le build et le deploiement;
- verifier le statut dans GitHub Actions (build) et dans le tableau de bord Railway (deploy);
- associer le deploy au commit SHA.

---

## 13) Limites actuelles et actions correctives planifiees

Limite identifiee:
- presence d'une variable sensible en dur dans le workflow build (a supprimer avant rendu final).

Action corrective prevue:
- migrer cette valeur vers GitHub Secrets;
- mettre a jour la documentation securite (trace de correction).

Hebergeur cible retenu: **Railway** (PaaS, build depuis Dockerfile, MySQL managé). La documentation de deploiement est dans `docs/04_deploiement_railway.md`.

Cette section est importante pour prouver la capacite d'analyse, de correction et d'amelioration continue (attendu DevOps).

---

## 14) Conclusion

Ce document montre la maitrise des fondamentaux:
- Agile pour organiser la livraison;
- DevOps pour automatiser et fiabiliser;
- YAML pour declarer les pipelines;
- CI/CD pour industrialiser build et deploiement;
- environnement de test pour valider avant production.

La suite documentaire detaille:
- le deploiement sur Railway (`docs/04_deploiement_railway.md`);
- la strategie de test et les scenarios;
- la securite des tests et du deploiement.

