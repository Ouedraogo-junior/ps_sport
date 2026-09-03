# PS Sport (ZenBet)

Plateforme web d'abonnement à des pronostics sportifs (« coupons ») avec activation par code d'accès, paiement Mobile Money soumis manuellement, et un module d'abonnements « investissement » à rendement journalier avec demandes de retrait.

![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20?logo=laravel&logoColor=white)
![Livewire](https://img.shields.io/badge/Livewire-3-4E56A6?logo=livewire&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/TailwindCSS-4-06B6D4?logo=tailwindcss&logoColor=white)
![SQLite](https://img.shields.io/badge/DB-SQLite%2FMySQL-003B57?logo=sqlite&logoColor=white)

---

## Sommaire

- [Architecture](#architecture)
- [Stack technique](#stack-technique)
- [Structure du projet](#structure-du-projet)
- [Prérequis](#prérequis)
- [Installation](#installation)
- [Démarrage](#démarrage)
- [Rôles et authentification](#rôles-et-authentification)
- [Modules fonctionnels](#modules-fonctionnels)
- [Parcours utilisateur](#parcours-utilisateur)
- [Aperçu des routes](#aperçu-des-routes)
- [Back-office admin](#back-office-admin)
- [Tâches planifiées](#tâches-planifiées)
- [Licence](#licence)

---

## Architecture

```
Navigateur
    ↓
Application Laravel 13 monolithique (Blade + Livewire + Alpine.js)
    ↓
Base de données (SQLite en local / MySQL en production)
    ↓
API externe api-sports.io (RapidAPI) — calendrier & scores des matchs
```

Application monolithique classique Laravel (pas de séparation front/back-end) : rendu Blade côté serveur, interactivité ponctuelle via Livewire (back-office admin) et Alpine.js.

---

## Stack technique

| Couche | Technologie |
|---|---|
| Framework | Laravel 13 (PHP 8.3) |
| Composants dynamiques | Livewire 3 |
| Interactivité front | Alpine.js |
| Style | TailwindCSS v4 |
| Base de données | SQLite (dev) / MySQL (prod) |
| SEO | artesaos/seotools + spatie/laravel-sitemap |
| Données football | API-Football via RapidAPI |
| Bundler | Vite |

---

## Structure du projet

```
ps_sport/
├── app/
│   ├── Console/Commands/
│   │   └── CrediteSoldesInvestissement.php   # Crédit journalier des plans investissement
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── HomeController.php            # Vitrine publique
│   │   │   ├── CalendrierController.php      # Calendrier des matchs du jour
│   │   │   ├── CouponController.php          # Coupons (liste, détail, performances)
│   │   │   ├── DashboardController.php       # Espace utilisateur
│   │   │   ├── Auth/                         # Connexion / inscription (par téléphone)
│   │   │   └── Admin/                        # Contrôleurs back-office
│   │   └── Middleware/
│   │       ├── CheckAbonnement.php           # Bloque l'accès aux coupons sans abonnement actif
│   │       └── CheckAdmin.php                # Restreint le back-office au rôle admin
│   ├── Livewire/Admin/                       # Composants Livewire du back-office
│   │   ├── PaiementsEnAttente.php
│   │   ├── GestionRetraits.php
│   │   ├── GestionPlans.php
│   │   ├── GestionCoupons.php
│   │   ├── GestionUtilisateurs.php
│   │   ├── GestionCodes.php
│   │   └── GestionParametres.php
│   └── Models/
│       ├── User.php                          # Auth par téléphone, rôle user/admin
│       ├── Plan.php                          # Plans d'abonnement (classiques + investissement)
│       ├── Abonnement.php
│       ├── AccessCode.php                    # Codes d'activation d'abonnement
│       ├── Paiement.php                      # Paiements Mobile Money soumis (avec capture)
│       ├── Coupon.php / Selection.php / CouponCode.php   # Pronostics et codes bookmaker
│       ├── SoldeInvestissement.php / TransactionSolde.php
│       ├── DemandeRetrait.php
│       └── Parametre.php                     # Paramètres clé/valeur (WhatsApp, USSD, ...)
├── database/
│   ├── migrations/
│   └── seeders/
│       ├── PlanSeeder.php
│       ├── ParametreSeeder.php
│       ├── CouponSeeder.php
│       └── CouponCodeSeeder.php
├── resources/
│   ├── views/                                # Blade (public, dashboard, admin)
│   └── views/admin/                          # Vues du back-office
└── routes/
    ├── web.php
    └── console.php                           # Planification des commandes
```

---

## Prérequis

- PHP 8.3+
- Composer
- Node.js 18+ et npm
- SQLite (par défaut) ou MySQL en production
- Une clé API [API-Football (RapidAPI)](https://rapidapi.com/api-sports/api/api-football) pour le calendrier et les scores

---

## Installation

### 1. Cloner le projet

```bash
git clone https://github.com/Ouedraogo-junior/ps_sport.git
cd ps_sport
```

### 2. Installer les dépendances

```bash
composer install
npm install
```

### 3. Configurer l'environnement

```bash
cp .env.example .env
php artisan key:generate
```

Renseigner dans `.env` :

```env
RAPIDAPI_KEY=votre_cle_rapidapi
RAPIDAPI_HOST=v3.football.api-sports.io
```

> ⚠️ Le dépôt contient une clé `RAPIDAPI_KEY` d'exemple dans `.env.example` — remplacez-la par votre propre clé et évitez de committer une clé réelle dans `.env.example`.

### 4. Base de données

Par défaut `DB_CONNECTION=sqlite` :

```bash
touch database/database.sqlite
php artisan migrate
```

### 5. Créer le premier compte administrateur

Aucun compte admin n'est créé automatiquement. Créez-en un via Tinker :

```bash
php artisan tinker
```

```php
\App\Models\User::create([
    'telephone' => '70000000',
    'nom'       => 'Admin',
    'password'  => bcrypt('votre_mot_de_passe'),
    'role'      => 'admin',
    'statut'    => 'actif',
]);
```

### 6. Charger les données de référence (optionnel)

Les seeders métier ne sont pas appelés automatiquement par `DatabaseSeeder` : à lancer individuellement selon besoin.

```bash
php artisan db:seed --class=ParametreSeeder   # Numéro WhatsApp, codes USSD Orange/Moov
php artisan db:seed --class=PlanSeeder        # Plans d'abonnement par défaut
php artisan db:seed --class=CouponSeeder      # Coupons de démonstration (nécessite un admin existant)
php artisan db:seed --class=CouponCodeSeeder
```

### 7. Compiler les assets

```bash
npm run build
```

---

## Démarrage

```bash
composer run dev
```

Cette commande lance en parallèle le serveur Laravel, le worker de file d'attente, les logs (`pail`) et Vite en mode watch. Application accessible sur `http://localhost:8000`.

---

## Rôles et authentification

Connexion par **numéro de téléphone** (identifiant d'authentification) + mot de passe, via formulaire classique Laravel (sessions, pas d'API token).

| Rôle | Accès |
|---|---|
| `user` | Espace personnel : abonnement, coupons (si abonnement actif), paiement, solde investissement, retraits |
| `admin` | Back-office complet (`/admin`) |

Middlewares :
- `abonnement` (`CheckAbonnement`) — bloque l'accès aux coupons si l'utilisateur n'a pas d'abonnement actif ou si son compte est suspendu.
- `admin` (`CheckAdmin`) — restreint `/admin/*` au rôle administrateur.

---

## Modules fonctionnels

- **Vitrine publique** — accueil avec statistiques de réussite, calendrier des matchs du jour (LDC, Premier League, Liga, Ligue 1, Bundesliga, Serie A, CAN), historique de performances et captures d'écran de gains
- **Abonnements** — plans classiques (hebdomadaire, mensuel, premium) donnant accès aux coupons du jour
- **Activation par code** — un code d'accès (`ACC-XXXXXXXX`) généré par l'admin ou après validation d'un paiement active l'abonnement de l'utilisateur
- **Paiement Mobile Money** — soumission d'une capture d'écran de paiement (Orange Money / Moov Money), validée manuellement par un admin, qui génère alors un code d'accès
- **Coupons** — pronostics avec sélections de matchs, niveau de risque, analyse, et codes correspondants par bookmaker (1xBet, BetWinner, Melbet, 1Win)
- **Plans investissement** — certains plans (`est_investissement`) créditent quotidiennement un pourcentage (`taux_journalier`) du montant payé sur un solde utilisateur, retirable via Mobile Money une fois un seuil atteint
- **Demandes de retrait** — soumises par l'utilisateur (Orange Money, Moov Money, Wave), validées ou rejetées par un admin
- **Paramètres** — configuration clé/valeur (numéro WhatsApp support, message pré-rempli, codes USSD Orange/Moov)

---

## Parcours utilisateur

### Souscription classique
1. L'utilisateur s'inscrit avec son numéro de téléphone
2. Il choisit un plan et soumet une capture de paiement Mobile Money, **ou** saisit un code d'accès reçu via WhatsApp
3. Un admin valide le paiement → un code d'accès est généré et communiqué à l'utilisateur
4. L'utilisateur saisit le code → son abonnement est activé
5. Il accède aux coupons du jour tant que l'abonnement est actif

### Plan investissement
1. L'utilisateur souscrit à un plan marqué `est_investissement`
2. Chaque jour à minuit, la commande `investissement:crediter` crédite le gain journalier sur son solde
3. Une fois le seuil de retrait atteint, l'utilisateur soumet une demande de retrait
4. Un admin valide (débite le solde, enregistre la transaction) ou rejette la demande

---

## Aperçu des routes

Préfixe des vues utilisateur en français (`/inscription`, `/connexion`, etc.).

### Public
| Route | Description |
|---|---|
| `GET /` | Accueil |
| `GET /calendrier` | Calendrier des matchs du jour |
| `GET /calendrier/performances` | Performances liées au calendrier |
| `GET /performances` | Historique et statistiques publiques |
| `GET /performances/captures` | Captures d'écran de gains |
| `GET /sitemap.xml` | Sitemap SEO |

### Authentification
| Route | Description |
|---|---|
| `GET/POST /inscription` | Inscription |
| `GET/POST /connexion` | Connexion |
| `POST /deconnexion` | Déconnexion |

### Espace utilisateur (connecté)
| Route | Description |
|---|---|
| `GET /dashboard` | Tableau de bord (abonnement, solde, retraits) |
| `POST /dashboard/activer` | Activer un abonnement via code |
| `POST /dashboard/paiement` | Soumettre un paiement Mobile Money |
| `POST /dashboard/retrait` | Soumettre une demande de retrait |
| `GET /upgrade-plan` | Passer à un plan investissement supérieur |
| `GET /coupons` *(abonnement requis)* | Liste des coupons du jour |
| `GET /coupons/{coupon}` *(abonnement requis)* | Détail d'un coupon |

---

## Back-office admin

Préfixe `/admin`, réservé au rôle `admin`.

| Domaine | Routes principales |
|---|---|
| Dashboard | `GET /admin` — statistiques globales |
| Paiements | `GET /admin/paiements`, `POST /admin/paiements/{id}/valider`, `POST /admin/paiements/{id}/rejeter` |
| Codes d'accès | `GET/POST /admin/codes`, `DELETE /admin/codes/{id}/revoquer` |
| Coupons | CRUD complet + `POST /admin/coupons/{id}/publier`, `/depublier`, `/resultat` |
| Utilisateurs | `GET /admin/utilisateurs`, `GET /admin/utilisateurs/{id}`, `POST .../bloquer`, `POST .../debloquer` |
| Plans | `GET /admin/plans` |
| Retraits | `GET /admin/retraits` |
| Paramètres | `GET /admin/parametres` |

La plupart de ces écrans sont pilotés par des composants Livewire (`app/Livewire/Admin/*`) pour les interactions sans rechargement de page.

---

## Tâches planifiées

Définies dans `routes/console.php` :

| Commande | Fréquence | Rôle |
|---|---|---|
| `investissement:crediter` | Tous les jours à 00:00 | Crédite le gain journalier des abonnés à un plan investissement actif |
| `app:mettre-a-jour-scores` | Toutes les heures | Référencée dans la planification — à implémenter/vérifier, absente de `app/Console/Commands` au moment de la rédaction |

Le scheduler Laravel doit être exécuté en continu (`php artisan schedule:work` en dev, ou une entrée cron `* * * * * php artisan schedule:run` en production).

---

## Licence

Projet propriétaire. Tous droits réservés. Usage et distribution soumis à l'autorisation du propriétaire du projet.

---

*PS Sport — 2026*
