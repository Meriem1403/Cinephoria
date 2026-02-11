
# 🧪 PLAN DE TEST PROFESSIONNEL — Cinéphoria

Ce document décrit les cas de test validés sur les trois plateformes (Web Symfony, Application Mobile Expo React Native, Application Desktop Python Tkinter) dans le cadre du projet Cinéphoria.  
Les tests couvrent les **fonctionnalités principales, les erreurs attendues, la sécurité et l’UX**.

---

## 🗂️ 1. Données de référence

- Utilisateur admin : `admin@cinephoria.com / Admin123*`
- Utilisateur standard : `alice@cinephoria.com / User123*`
- Employé : `employee@cinephoria.com / Emp123*`

---

## 🌐 2. Tests Application Web Symfony (site utilisateur et EasyAdmin)

### 🧑 Utilisateur

| Test n° | Fonction                  | Données testées                           | Attendu                                                    | Résultat |
|--------|----------------------------|--------------------------------------------|-------------------------------------------------------------|----------|
| W-01   | Connexion réussie         | Email + mot de passe corrects              | Redirection vers la page d’accueil                         | ✅       |
| W-02   | Connexion échouée         | Email ou mot de passe erroné               | Message d'erreur affiché                                   | ✅       |
| W-03   | Consultation des films    | Accès public ou connecté                   | Liste des films affichée avec filtres                      | ✅       |
| W-04   | Détail d’un film          | Cliquez sur une affiche                    | Affiche : résumé, genres, séances, bouton réservation       | ✅       |
| W-05   | Réservation de sièges     | Choix de 2 sièges                          | Redirection vers choix formule > ticket généré             | ✅       |
| W-06   | Siège déjà réservé        | Tentative de double réservation            | Blocage avec message d’erreur                              | ✅       |
| W-07   | Envoi d’un avis           | Commentaire et note validés                | Avis enregistré avec modération admin                      | ✅       |

### 🛠️ EasyAdmin (Administrateur)

| Test n° | Fonction                    | Action                                        | Attendu                                              | Résultat |
|--------|------------------------------|-----------------------------------------------|-------------------------------------------------------|----------|
| A-01   | Connexion admin             | Accès à /admin                                | Tableau de bord admin visible                         | ✅       |
| A-02   | Ajout d’un film             | Via EasyAdmin > Film                          | Film enregistré, visible côté public                  | ✅       |
| A-03   | Ajout d’une séance          | Choix du film, salle, horaire                 | La séance apparaît dans les listes                    | ✅       |
| A-04   | Modération d’avis          | Avis utilisateur en attente                   | Peut être approuvé ou supprimé                        | ✅       |
| A-05   | Gestion d’utilisateur       | CRUD via EasyAdmin                            | Edition, suppression, activation/désactivation        | ✅       |
| A-06   | Statistiques générales      | Accès aux données de réservation              | Affichage de données filtrables par date/cinéma       | ✅       |

---

## 📱 3. Tests Application Mobile (Expo React Native)

| Test n° | Fonction                      | Action                                     | Attendu                                               | Résultat |
|--------|-------------------------------|--------------------------------------------|--------------------------------------------------------|----------|
| M-01   | Lancement de l'app            | Expo Go                                    | Page de login visible                                 | ✅       |
| M-02   | Connexion utilisateur         | Email + mot de passe                       | Redirection vers la HomeScreen                        | ✅       |
| M-03   | Affichage films               | Liste par genre                            | Affiches affichées par section                        | ✅       |
| M-04   | Réservation mobile            | Sélection film > séance > sièges           | Réservation validée + redirection ticket              | ✅       |
| M-05   | Affichage ticket              | BookingConfirmationScreen.js               | Ticket généré avec QR code par siège                  | ✅       |
| M-06   | Envoi par message             | Bouton “Envoyer” sur ticket                | Message système ouvert avec contenu du ticket         | ✅       |
| M-07   | Gestion erreurs API           | Connexion sans réseau                      | Message d’erreur affiché                              | ✅       |

---

## 🖥️ 4. Tests Application Desktop Employé (Python)

| Test n° | Fonction                          | Action                                 | Attendu                                                | Résultat |
|--------|-----------------------------------|----------------------------------------|---------------------------------------------------------|----------|
| D-01   | Lancement de l’application        | `python app.py`                        | Tableau des incidents chargé depuis l’API              | ✅       |
| D-02   | Déclaration d’un incident         | Formulaire (titre + description)       | Enregistrement côté API, visible dans EasyAdmin        | ✅       |
| D-03   | Changement d’état d’un siège      | Case à cocher dans la table            | MAJ immédiate via API PUT                              | ✅       |
| D-04   | Refresh automatique               | Bouton "Rafraîchir"                    | Récupération des nouvelles données                     | ✅       |
| D-05   | Filtrage des incidents            | Sélection par salle ou statut          | Table dynamique filtrée                                | ✅       |

---

## 🔒 5. Tests de sécurité

| Test n° | Fonction                     | Tentative                              | Comportement attendu                                   | Résultat |
|--------|------------------------------|----------------------------------------|---------------------------------------------------------|----------|
| S-01   | Accès admin sans login       | Aller à /admin                         | Redirection vers page de connexion                     | ✅       |
| S-02   | Injections SQL               | Email : `' OR 1=1 --`                 | Blocage + log sécurité                                 | ✅       |
| S-03   | Accès API sans token JWT     | Appel `/api-client/reservations`      | Erreur 401 non autorisé                                | ✅       |
| S-04   | Données privées              | Voir profil d’un autre utilisateur     | Redirection ou interdiction (403)                      | ✅       |

---

## ✅ Conclusion

Tous les tests ont été validés sur environnements locaux et simulateurs.  
Les scénarios couvrent les parcours critiques, les erreurs, les rôles utilisateurs, l’administration, et la sécurité API.

