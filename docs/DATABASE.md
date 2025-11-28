# 🗄️ DATABASE.md - Modelo de Dados

## Diagrama ER (Entity-Relationship)

```
┌──────────────────┐         ┌──────────────────┐
│    CLIENTES      │────────▶│    PROJETOS      │
├──────────────────┤    1:N  ├──────────────────┤
│ id (PK)          │         │ id (PK)          │
│ nome             │         │ nome             │
│ email            │         │ cliente_id (FK)  │
│ cpf_cnpj       
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Client extends Model
{
# 🗄️ DATABASE.md - Data Model

## ER Diagram (Entity-Relationship)

```
┌──────────────────────┐         ┌──────────────────────┐
│      CLIENTS         │────────▶│      PROJECTS        │
├──────────────────────┤    1:N  ├──────────────────────┤
│ id (PK)              │         │ id (PK)              │
│ name                 │         │ client_id (FK)       │
│ email                │         │ uf                   │
│ document             │         │ installation_type    │
│ phone                │         │ created_at           │
│ created_at           │         │ updated_at           │
│ updated_at           │         │ deleted_at           │
│ deleted_at           │         └──────────────────────┘
└──────────────────────┘                   │
                                           │ 1:N
                                           │
                              ┌────────────▼─────────────┐
                              │  PROJECT_EQUIPMENT       │
                              ├──────────────────────────┤
                              │ id (PK)                  │
                              │ project_id (FK)          │
                              │ equipment_type           │
                              │ quantity                 │
                              │ created_at               │
                              │ updated_at               │
                              └──────────────────────────┘
```

---

## 📊 Table Descriptions

### 1. CLIENTS

Stores information about clients (individuals or companies) who purchase solar projects.

| Field | Type | Nullable | Unique | Description |
|-------|------|----------|--------|-------------|
| `id` | UUID | No | Yes (PK) | Unique identifier |
| `name` | VARCHAR(255) | No | No | Person or company name |
| `email` | VARCHAR(255) | No | Yes | Unique email for contact |
| `document` | VARCHAR(20) | No | Yes | Valid CPF or CNPJ |
| `phone` | VARCHAR(20) | No | No | Contact phone |
| `created_at` | TIMESTAMP | Yes | No | Creation date |
| `updated_at` | TIMESTAMP | Yes | No | Update date |
| `deleted_at` | TIMESTAMP | Yes | No | Soft delete date |

**Indexes:**
- PK: `id`
- UNIQUE: `email`, `document`

**Business Validations:**
- CPF must be valid (check digit validation)
- CNPJ must be valid (check digit validation)
- Email must be unique in the system
- CPF/CNPJ must be unique in the system

**Example:**
```sql
INSERT INTO clients (id, name, email, document, phone, created_at, updated_at)
VALUES (UUID(), 'João Silva', 'joao@email.com', '123.456.789-10', '11999999999', NOW(), NOW());
```

---

### 2. PROJECTS

Stores solar energy projects associated with clients.

| Field | Type | Nullable | Unique | Description |
|-------|------|----------|--------|-------------|
| `id` | UUID | No | Yes (PK) | Unique identifier |
| `client_id` | UUID | No | No | Reference to client (FK) |
| `uf` | CHAR(2) | No | No | State (UF) of installation |
| `installation_type` | VARCHAR(255) | No | No | Installation type (Enum) |
| `created_at` | TIMESTAMP | Yes | No | Creation date |
| `updated_at` | TIMESTAMP | Yes | No | Update date |
| `deleted_at` | TIMESTAMP | Yes | No | Soft delete date |

**Indexes:**
- PK: `id`
- FK: `client_id` → `clients(id)`

**Constraints:**
- `client_id` NOT NULL (every project must have a client)
- `ON DELETE CASCADE` - If client is deleted, their projects are also deleted

**Business Validations:**
- A project MUST be associated with a valid client
- UF must be in the list of valid Brazilian states
- installation_type must be a valid type from the Enum

**Installation Types (Enum):**
```
- FIBROCIMENTO_MADEIRA (Fibrocimento - Wood)
- FIBROCIMENTO_METALICO (Fibrocimento - Metal)
- CERAMICO (Ceramic)
- METALICO (Metal)
- LAJE (Slab)
- SOLO (Ground)
```

**Example:**
```sql
INSERT INTO projects (id, client_id, uf, installation_type, created_at, updated_at)
VALUES (UUID(), 'client-uuid-here', 'SP', 'CERAMICO', NOW(), NOW());
```

---

### 3. PROJECT_EQUIPMENT

Stores equipment associated with projects with quantities.

| Field | Type | Nullable | Description |
|-------|------|----------|-------------|
| `id` | UUID | No | Unique identifier (PK) |
| `project_id` | UUID | No | Reference to project (FK) |
| `equipment_type` | VARCHAR(255) | No | Equipment type (Enum) |
| `quantity` | INTEGER | No | Equipment quantity |
| `created_at` | TIMESTAMP | Yes | Creation date |
| `updated_at` | TIMESTAMP | Yes | Update date |

**Indexes:**
- PK: `id`
- FK: `project_id` → `projects(id)`

**Constraints:**
- `quantity > 0`
- `ON DELETE CASCADE` - If project is deleted, equipment is removed

**Equipment Types (Enum):**
```
- MODULO (Module)
- INVERSOR (Inverter)
- MICROINVERSOR (Microinverter)
- ESTRUTURA (Structure)
- CABO_VERMELHO (Red Cable)
- CABO_PRETO (Black Cable)
- STRING_BOX (String Box)
- CABO_TRONCO (Trunk Cable)
- ENDCAP (Endcap)
```

**Example:**
```sql
INSERT INTO project_equipment (id, project_id, equipment_type, quantity, created_at, updated_at)
VALUES (UUID(), 'project-uuid-here', 'MODULO', 20, NOW(), NOW());
```

---

## 🔗 Relationships

### 1. Client → Projects (1:N)

A client can have **zero or multiple projects**.

```
client.id = uuid-1
├── project.id = uuid-10 (client_id = uuid-1)
├── project.id = uuid-11 (client_id = uuid-1)
└── project.id = uuid-12 (client_id = uuid-1)
```

**Restriction:** It's not possible to delete a client that has active projects (referential integrity).

### 2. Project → Equipment (1:N)

A project can have **one or multiple equipment items**.

```
project.id = uuid-1
├── equipment.id = uuid-e1 (equipment_type = MODULO, quantity = 20)
├── equipment.id = uuid-e2 (equipment_type = INVERSOR, quantity = 5)
└── equipment.id = uuid-e3 (equipment_type = ESTRUTURA, quantity = 15)
```

---

## 📋 Migrations

The migrations define the database schema:

### Migration: Create Clients Table
```php
Schema::create('clients', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->string('name');
    $table->string('email')->unique();
    $table->string('phone');
    $table->string('document')->unique();
    $table->timestamps();
    $table->softDeletes();
});
```

### Migration: Create Projects Table
```php
Schema::create('projects', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->uuid('client_id');
    $table->string('uf', 2);
    $table->string('installation_type');
    $table->timestamps();
    $table->softDeletes();

    $table->foreign('client_id')
        ->references('id')
        ->on('clients')
        ->onDelete('cascade');
});
```

### Migration: Create Project_Equipment Table
```php
Schema::create('project_equipment', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->uuid('project_id');
    $table->string('equipment_type');
    $table->integer('quantity');
    $table->timestamps();

    $table->foreign('project_id')
        ->references('id')
        ->on('projects')
        ->onDelete('cascade');
});
```

---

## 🌱 Seeders

Seeders populate initial data in the database:

### ClientSeeder
Creates sample clients for testing.

### ProjectSeeder (optional)
Creates sample projects associated with clients.

---

## 🔍 Useful Queries

### Get all projects from a client
```sql
SELECT p.* FROM projects p
WHERE p.client_id = 'uuid-here';
```

### Get equipment from a project with quantities
```sql
SELECT pe.*
FROM project_equipment pe
WHERE pe.project_id = 'uuid-here';
```

### Count how many projects each client has
```sql
SELECT c.name, COUNT(p.id) as total_projects
FROM clients c
LEFT JOIN projects p ON c.id = p.client_id
GROUP BY c.id, c.name;
```

### List projects by state
```sql
SELECT p.*, c.name as client_name
FROM projects p
INNER JOIN clients c ON p.client_id = c.id
WHERE p.uf = 'SP'
ORDER BY p.created_at DESC;
```

### Find most used equipment types
```sql
SELECT equipment_type, SUM(quantity) as total_quantity, COUNT(*) as usage_count
FROM project_equipment
GROUP BY equipment_type
ORDER BY total_quantity DESC;
```

---

## 📈 Performance Analysis

### Critical Indexes
- `clients(email)` - Search by unique email
- `clients(document)` - Search by document
- `projects(client_id)` - List projects from a client
- `projects(uf)` - Filter by state
- `project_equipment(project_id)` - Get equipment from project

### Optimized Queries
- Use `eager loading` with Laravel: `Project::with('client', 'equipment')->get()`
- Use `select()` to fetch only necessary columns
- Use `whereIn()` for multiple conditions instead of multiple `where()`

---

## 🔐 Data Integrity

### Database Level Validations

| Validation | Type | Description |
|-----------|------|-------------|
| NOT NULL | Constraint | Required fields |
| UNIQUE | Constraint | Unique values |
| FOREIGN KEY | Constraint | Referential integrity |
| CHECK | Constraint | Quantity > 0 |

### Application Level Validations

PHP validations add extra security layers implemented in the **Application Layer** (Services and Form Requests).

---

## 📊 Expected Data Growth

| Table | Growth | Considerations |
|--------|------------|---------------|
| clients | Slow (100-1000/month) | Few indexes needed |
| projects | Medium (1000-10000/month) | Index on client_id essential |
| project_equipment | Medium/High (proportional to projects) | Index on project_id critical |

---

## 🛠️ Maintenance

### Recommended Backups
```bash
# Daily backup
mysqldump -u root -p solar_db > backup_$(date +%Y%m%d_%H%M%S).sql
```

### Old Data Cleanup
```sql
-- Delete projects older than 2 years (and their equipment automatically)
DELETE FROM projects WHERE updated_at < DATE_SUB(NOW(), INTERVAL 2 YEAR);
```

### Integrity Verification
```sql
-- Verify referential integrity
SELECT * FROM projects WHERE client_id NOT IN (SELECT id FROM clients);
SELECT * FROM project_equipment WHERE project_id NOT IN (SELECT id FROM projects);
```

---

**The model follows the normalized relational pattern (3NF) ensuring data consistency and integrity! 🎯**
