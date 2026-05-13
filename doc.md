# CanisPro Éducation — E5 Revision Cheat Sheet (BTS SIO SLAM)

Short practical notes for the oral exam. Symfony, PHP 8.x, MVC, Doctrine, MySQL, Twig, Bootstrap.

---

## 1. Project summary

- **CanisPro Éducation** is a web app for a canine education center.
- Built with **Symfony** (MVC), **PHP 8.x**, **Doctrine ORM**, **MySQL**, **Twig**, **Bootstrap**.
- **Public** visitors can browse **courses** (`Cour`) and **sessions** (`Seance`).
- **Members** (`ROLE_USER`) manage their **dogs** (`Chien`) and **registrations** (`Inscription`).
- **Admins** (`ROLE_ADMIN`) manage courses, sessions, users, and data.
- **GitHub** for versioning; **ClickUp** for project management.

---

## 2. Important Symfony files

| What you change | Role | Typical paths (examples) |
|-----------------|------|---------------------------|
| **Entity** | PHP class mapped to a DB table; properties = columns / relations | `src/Entity/Seance.php`, `src/Entity/Cour.php` |
| **Repository** | Custom queries (`findBy…`, DQL) | `src/Repository/SeanceRepository.php` |
| **Controller** | HTTP actions: list, show, new, edit, delete | `src/Controller/SeanceController.php` |
| **Form** | Fields, validation linked to an entity | `src/Form/SeanceType.php` |
| **Twig template** | HTML + `{{ }}` display | `templates/seance/index.html.twig`, `new.html.twig`, `edit.html.twig` |
| **Migration** | SQL diff for the database | `migrations/VersionXXXXXXXXXXXXXX.php` |
| **Security config** | Roles, firewalls, access rules | `config/packages/security.yaml` |
| **Routes** | URL → controller (often attributes on the controller) | `#[Route('/admin/seance', name: 'app_seance_')]` on methods, or `config/routes.yaml` |

**Oral tip:** “I change the **entity** for the data model, **migration** for the database, **form** for input, **controller** for logic and HTTP, **Twig** for the page.”

---

## 3. CRUD cheat sheet for `Seance`

### CREATE

- **What it does:** Shows a form to add a session; saves a new `Seance` in the database.
- **Files:** `Seance` entity, `SeanceType` form, controller `new()` action, `templates/seance/new.html.twig`.
- **Doctrine:** `$entityManager->persist($seance);` then `$entityManager->flush();`
- **Command (scaffold):** `php bin/console make:crud Seance` (or `make:form` + manual controller).

**Oral:** “Create uses a **form**, **persist** and **flush** to insert the row.”

---

### READ

- **What it does:** List all sessions or show one session.
- **Files:** `SeanceRepository` (`findAll()`, `find($id)`), controller `index()` / `show()`, Twig table or detail page.
- **Example:** `$seances = $seanceRepository->findAll();` return `render('seance/index.html.twig', ['seances' => $seances]);`

**Oral:** “Read uses the **repository** to fetch data and **Twig** to display it.”

---

### UPDATE

- **What it does:** Load an existing `Seance`, show the same form pre-filled, save changes.
- **Files:** Controller `edit($id)`, same `SeanceType`, `edit.html.twig`.
- **Doctrine:** get entity, handle form submit, `flush()` (no `persist` needed if entity is already managed).

**Oral:** “Update reuses the **create form**; after submit I **flush** to apply changes.”

---

### DELETE

- **What it does:** Remove a session from the database (and related rules if any).
- **Files:** Controller `delete()` (POST), optional confirm form with **CSRF** token.
- **Doctrine:** `$entityManager->remove($seance);` then `$entityManager->flush();`

**Oral:** “Delete uses **POST**, a **CSRF token** to avoid forgery, then **remove** and **flush**.”

---

## 4. Example: adding a new field to `Seance`

**Goal:** Add **`capacity`** (e.g. max participants per session).

1. **Modify entity `Seance`** — add a property (e.g. `private ?int $capacity = null;` or `int` if required).
2. **Add property** `capacity` with correct type and nullable rule.
3. **Generate getter/setter** — IDE or `php bin/console make:entity Seance` to add the field interactively.
4. **Create migration:** `php bin/console make:migration` then review the generated file in `migrations/`.
5. **Run migration:** `php bin/console doctrine:migrations:migrate`
6. **Add field in `SeanceType`:** map `capacity` to a form type (`IntegerType`, etc.).
7. **Display in Twig:** `{{ seance.capacity }}` in show/list/edit templates as needed.
8. **Validation:** add `#[Assert\Positive]` or `#[Assert\NotNull]` on the property if business rules require it.

**Entity (PHP property example):**

```php
#[ORM\Column(nullable: true)]
private ?int $capacity = null;

public function getCapacity(): ?int { return $this->capacity; }
public function setCapacity(?int $capacity): static { $this->capacity = $capacity; return $this; }
```

**Form (`SeanceType.php` example):**

```php
->add('capacity', IntegerType::class, [
    'required' => false,
    'label' => 'Capacity',
])
```

**Twig (example):**

```twig
<p>Capacity: {{ seance.capacity ?? '—' }}</p>
```

**Commands:**

```bash
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

---

## 5. Example: deleting a field from `Seance`

**Goal:** Remove **`capacity`** from `Seance`.

1. **Entity:** remove the `$capacity` property and **getters/setters**.
2. **Form (`SeanceType`):** remove `->add('capacity', ...)`.
3. **Controller:** remove any code that reads/writes `$seance->getCapacity()` / `setCapacity()`.
4. **Twig:** remove `{{ seance.capacity }}` and any labels/inputs for capacity.
5. **Database:** `php bin/console make:migration` — migration should **DROP COLUMN** `capacity` (check the file before running).
6. **Run:** `php bin/console doctrine:migrations:migrate`
7. **Test:** list, show, create, edit pages for `Seance`.

**Checklist when deleting:**

| Layer | Remove |
|--------|--------|
| **Entity** | Property + accessors |
| **Form** | Form field |
| **Controller** | Logic using the field |
| **Twig** | Display / form fragments |
| **DB** | Column via **migration** (do not only delete PHP — DB must match) |

---

## 6. Example: creating a relation

**Business rule:** A **Seance** belongs to **one Cour**. A **Cour** has **many Seances**.

- **Cardinality:** Cour **1,N** Seance (one course, many sessions).
- **Doctrine:**
  - `Seance` **ManyToOne** `Cour` (foreign key on `seance` table, e.g. `cour_id`).
  - `Cour` **OneToMany** `Seance` (inverse side: collection `$seances`).

**Command:**

```bash
php bin/console make:entity Seance
```

**Choices (typical):**

- Edit entity: **Seance**
- Add relation: **yes**
- Relation type: **ManyToOne**
- Target class: **Cour**
- Nullable: **no** if every session must have a course; **yes** if optional
- **Inverse side on Cour:** **yes** → `OneToMany` mappedBy on `Cour`, `mappedBy` / `inversedBy` correctly set

**Then:**

1. `php bin/console make:migration` → review FK creation.
2. `php bin/console doctrine:migrations:migrate`
3. **`SeanceType`:** add `EntityType::class` (or `CourType` subset) for choosing the course.
4. **Twig:** show `{{ seance.cour.nom }}` (example) on show/index.

**Oral:** “ManyToOne holds the **foreign key**; OneToMany is the **inverse** side for navigation from Cour to its sessions.”

---

## 7. Example: removing a relation

**Goal:** Remove the link between **Seance** and **Cour**.

1. **Seance:** remove **ManyToOne** property to `Cour` (and annotations/attributes).
2. **Cour:** remove **OneToMany** collection and `mappedBy` / orphanRemoval if any.
3. **Form:** remove the course field from `SeanceType`.
4. **Twig:** remove `seance.cour` display.
5. **Migration:** `make:migration` — should drop `cour_id` (or equivalent). **Review** before migrate.
6. **Migrate:** `doctrine:migrations:migrate`
7. **DB:** confirm FK dropped (phpMyAdmin, `SHOW CREATE TABLE seance`, or Doctrine schema check).

**Warning:** Before removing a relation, check **Inscription**, reports, or constraints — data may **depend** on `Cour` for business logic even if only `Seance` is edited in code.

---

## 8. Registration of dogs to a session

**Relations:**

- `Seance` **OneToMany** `Inscription`
- `Chien` **OneToMany** `Inscription`
- `Inscription` **ManyToOne** `Seance`
- `Inscription` **ManyToOne** `Chien`

**Simple explanation:**  
`Inscription` is an **association entity** between **Chien** and **Seance**. One row = one dog registered to one session. Later you can add **extra columns** on `Inscription` (date registered, status, payment, etc.) without duplicating dog or session tables.

**Oral:** “I use an **intermediate entity** so many dogs can join many sessions, with **metadata** on the link.”

---

## 9. Cardinalities for oral exam

| Relation | Doctrine | Cardinality | Explanation |
|----------|----------|-------------|-------------|
| Cour — Type | Cour ManyToOne Type; Type OneToMany Cour | **N,1** — **1,N** | Many courses share one type; one type groups many courses. |
| Cour — Niveau | Cour ManyToOne Niveau; Niveau OneToMany Cour | **N,1** — **1,N** | Many courses per level; one level lists many courses. |
| Cour — Seance | Cour OneToMany Seance; Seance ManyToOne Cour | **1,N** — **N,1** | One course has many sessions; each session belongs to one course. |
| Seance — Inscription | Seance OneToMany Inscription; Inscription ManyToOne Seance | **1,N** — **N,1** | One session has many registrations; each registration targets one session. |
| Chien — Inscription | Chien OneToMany Inscription; Inscription ManyToOne Chien | **1,N** — **N,1** | One dog can have many registrations (over time); each registration is for one dog. |
| Proprietaire — Chien | Proprietaire OneToMany Chien; Chien ManyToOne Proprietaire | **1,N** — **N,1** | One owner, many dogs; each dog has one owner. |
| User — Proprietaire | User OneToOne Proprietaire; Proprietaire OneToOne User | **1,1** — **1,1** | One login account linked to one owner profile (and reverse). |

---

## 10. Security checklist

- **`ROLE_ADMIN`:** protect admin CRUD (courses, sessions, users) with `access_control` or `#[IsGranted('ROLE_ADMIN')]`.
- **`ROLE_USER`:** member area (dogs, registrations) — not the same as public pages.
- **Password hashing:** Symfony `UserPasswordHasher` — never store plain text passwords.
- **Access control:** `security.yaml` `access_control` rules + voter / attributes on controllers.
- **CSRF:** delete (and sensitive) forms use a **token**; validate in controller before `remove()`.
- **Form validation:** Symfony constraints + `#[Valid]` on nested forms if needed.
- **Twig escaping:** `{{ variable }}` auto-escapes HTML — safe output by default.

**Oral:** “I separate **roles**, **hash passwords**, **restrict URLs**, and **CSRF** on destructive actions.”

---

## 11. Useful Symfony commands

```bash
composer install
symfony server:start

php bin/console make:entity
php bin/console make:controller
php bin/console make:form
php bin/console make:crud

php bin/console make:migration
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load

php bin/console cache:clear
```

---

## 12. E5 oral questions and short answers

| Question | Short answer |
|----------|----------------|
| **Why Symfony?** | Mature framework, components (routing, forms, security), documentation, fits professional PHP apps. |
| **Why MVC?** | Separates **Model** (data), **View** (Twig), **Controller** (HTTP) — easier maintenance and teamwork. |
| **Why Doctrine?** | Maps objects to **MySQL**; migrations; less raw SQL; relations in PHP. |
| **What is an Entity?** | PHP class representing a **table** (and relations) managed by Doctrine. |
| **What is a Repository?** | Class with **query methods** (`find`, `findAll`, custom DQL) for an entity. |
| **What is a Controller?** | Handles a **route**; gets data, processes forms, returns a **Response** or **render()** Twig. |
| **What is Twig?** | Symfony’s **template engine** — HTML with `{{ }}`, `{% %}`, inheritance (`extends`). |
| **Why use roles?** | **ROLE_ADMIN** vs **ROLE_USER** (and anonymous) to **restrict** features and URLs. |
| **Purpose of Inscription?** | **Link** a dog to a session; can store registration-specific data. |
| **What CRUD did you implement?** | **Seance** (session) management: list, add, edit, delete in admin. |
| **How do you add a new field?** | Entity + migration + form + Twig + validation if needed. |
| **How do you create a relation?** | `make:entity` (ManyToOne / OneToMany), migration, form field, display in Twig. |
| **How do you secure delete?** | **POST** only + **CSRF** token validation before `remove()` + `flush()`. |

---

## 13. Personal work summary (oral)

You can say:

> “I personally worked on **session management CRUD**, the **admin templates for sessions**, **dog registration to sessions**, **navigation improvements**, the **home page**, **course detail and general information** pages, **`AccueilController`**, **Symfony project setup**, **Git setup and commits**, **mockups**, and **navigation and UI tests**.”

---

*Good luck for E5.*
