# 🗄️ DATABASE.md - Modelo de Dados

## Diagrama ER (Entity-Relationship)

```
┌──────────────────┐         ┌──────────────────┐
│    Clients       │────────▶│    Projects     │
├──────────────────┤    1:N  ├──────────────────┤
│ id (PK)          │         │ id (PK)          │
│ name             │         │ name             │
│ email            │         │ client_id (FK)   │
│ phone            │         │ uf               │
│ document         │         │ tipo_install_id  │
│ created_at       │         │ created_at       │
│ updated_at       │         │ updated_at       │
│ deleted_at       │         │ deleted_at       │
└──────────────────┘         └──────────────────┘
                                      │
                                      │ N:M
                                      │
                            ┌─────────▼──────────┐
                            │ project_equipment  │
                            ├────────────────────┤
                            │ project_id (FK)    │
                            │ equipement_type    │
                            │ quantity           │
                            │ created_at         │
                            │ updated_at         │
                            │ deleted_at         │
                            └────────────────────┘                                    


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

---

### 2. PROJECTS

Stores information about solar energy installation projects for clients.

| Field | Type | Nullable | Unique | Description |
|-------|------|----------|--------|-------------|
| `id` | UUID | No | Yes (PK) | Unique identifier |
| `client_id` | UUID | No | No | Foreign key to clients table |
| `uf` | VARCHAR(2) | No | No | Brazilian state code (UF) |
| `installation_type` | VARCHAR(255) | No | No | Type of installation (residential, commercial, industrial, rural) |
| `created_at` | TIMESTAMP | Yes | No | Creation date |
| `updated_at` | TIMESTAMP | Yes | No | Update date |
| `deleted_at` | TIMESTAMP | Yes | No | Soft delete date |

**Indexes:**
- PK: `id`
- FK: `client_id` → `clients(id)`

**Business Validations:**
- `client_id` must reference an existing client
- `uf` must be a valid Brazilian state code (2 letters)
- `installation_type` must be one of: residential, commercial, industrial, rural
- Cascade delete when client is deleted

---

### 3. PROJECT_EQUIPMENT

Stores information about equipment associated with each project.

| Field | Type | Nullable | Unique | Description |
|-------|------|----------|--------|-------------|
| `id` | UUID | No | Yes (PK) | Unique identifier |
| `project_id` | UUID | No | No | Foreign key to projects table |
| `equipment_type` | VARCHAR(255) | No | No | Type of equipment (module, inverter, structure, others) |
| `quantity` | INTEGER | No | No | Quantity of equipment |
| `created_at` | TIMESTAMP | Yes | No | Creation date |
| `updated_at` | TIMESTAMP | Yes | No | Update date |

**Indexes:**
- PK: `id`
- FK: `project_id` → `projects(id)`

**Business Validations:**
- `project_id` must reference an existing project
- `equipment_type` must be one of: module, inverter, structure, others
- `quantity` must be a positive integer (> 0)
- Cascade delete when project is deleted

