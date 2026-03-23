# 🚀 Guide Démarrage Rapide - canisPro

## Synthèse Exécutive en 2 minutes

**canisPro** = Plateforme de gestion de cours de dressage canin avec gestion des propriétaires, chiens et inscriptions.

---

## ⚡ Installation Ultra-Rapide

```bash
# 1️⃣  Récupérer le code
git clone https://github.com/[vous]/canisPro.git
cd canisPro

# 2️⃣  Installer dépendances
composer install

# 3️⃣  Configurer BD (.env.local)
# DATABASE_URL=mysql://user:password@localhost/canisPro

# 4️⃣  Créer & initialiser BD
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

# 5️⃣  Charger données test (optionnel)
php bin/console doctrine:fixtures:load

# 6️⃣  Lancer le serveur
symfony serve

# 7️⃣  Ouvrir navigateur
# http://localhost:8000
```

---

## 📊 Structure Base de Données (Simple)

```
User ──1:1──→ Proprietaire ──1:n──→ Chien
                                      ↓ 1:n
                                  Inscription ←n:1─ Seance ←1:n─ Cour
                                                                    ↓
                                                                (Type + Niveau)
```

---

## 🎯 Principales Routes

| URL | Rôle | Description |
|-----|------|-------------|
| `/` | Tous | Accueil |
| `/liste-des-cours` | Tous | Catalogue |
| `/membre` | Membre | Dashboard |
| `/admin/chien` | Admin | Gérer chiens |

---

## 💻 Stack Technique

- **Backend**: Symfony 8.0 + PHP 8.4
- **ORM**: Doctrine
- **Template**: Twig
- **Auth**: Symfony Security
- **DB**: MySQL/MariaDB

---

## 🔐 Rôles

```
ROLE_USER  → Propriétaires (accès /membre)
ROLE_ADMIN → Administrateurs (accès complet /admin)
```

---

## 📁 Structure Projet Clé

```
src/
├── Controller/       ← Logique métier
├── Entity/          ← Modèles (User, Chien, Cour...)
├── Form/            ← Formulaires
└── Repository/      ← Requêtes BD

templates/
├── accueil/         ← Pages publiques
├── membre/          ← Pages membres
└── admin_*/         ← Pages admin
```

---

## ✅ Checklist Premier Lancement

- [ ] Cloner le code
- [ ] Faire `composer install`
- [ ] Configurer `.env.local` avec BD
- [ ] Créer BD: `doctrine:database:create`
- [ ] Migrer: `doctrine:migrations:migrate`
- [ ] Lancer: `symfony serve`
- [ ] Tester: http://localhost:8000

---

## 🐛 Dépannage Rapide

| Problème | Solution |
|----------|----------|
| BD non créée | `php bin/console doctrine:database:create` |
| Tables manquantes | `php bin/console doctrine:migrations:migrate` |
| Port 8000 occupé | `symfony serve --port=8001` |
| Cache problématique | `php bin/console cache:clear` |

---

## 📚 Documents Complets

- **DOCUMENTATION_COMPLETE.md** - Documentation technique complète (Contexte, Architecture, Code, etc.)
- **DOCUMENTATION.html** - Version HTML interactive
- **README.md** - Présentation générale
- **ARCHITECTURE.md** - Diagrammes UML, flux, modules
- **Ce fichier** - Démarrage rapide

---

## 🔗 Liens Utiles

- Symfony Docs: https://symfony.com/doc/
- Doctrine Docs: https://www.doctrine-project.org/
- Twig Docs: https://twig.symfony.com/

---

## 👤 Premier Test

```php
// Créer un utilisateur admin
php bin/console user:create admin@example.com
php bin/console user:upgrade admin@example.com ROLE_ADMIN
```

---

**Prêt à commencer ? Lancez `symfony serve` ! 🎉**
