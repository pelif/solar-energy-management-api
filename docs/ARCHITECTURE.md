# 🏗️ ARCHITECTURE.md - Clean Architecture

## Visão Geral da Arquitetura

Este projeto implementa **Clean Architecture** conforme proposto por Robert C. Martin (Uncle Bob), dividindo a aplicação em 4 camadas concêntricas, cada uma com responsabilidades bem definidas e independentes.

### Diagrama de Camadas

```
                ┌─────────────────────────────────────┐
                │   PRESENTATION LAYER                │
                │  (Controllers, HTTP)                │
                └──────────────┬──────────────────────┘
                               │
                ┌──────────────▼──────────────────────┐
                │  APPLICATION LAYER                  │
                │ (DTOs)                              │
                └──────────────┬──────────────────────┘
                               │
                ┌──────────────▼──────────────────────┐
                │   DOMAIN LAYER                      │
                │ (Entities, Interfaces)              │
                └──────────────┬──────────────────────┘
                               │
                ┌──────────────▼──────────────────────┐
                │ INFRASTRUCTURE LAYER                │
                │ (Database, Repositories)            │
                └─────────────────────────────────────┘
```

---

## 1️⃣ PRESENTATION LAYER (Camada de Apresentação)

**Localização:** `app/Http/Controllers`

Responsável por receber requisições HTTP, validar entrada e retornar respostas.

### Componentes

#### 1.1 Controllers 
- Orquestram o fluxo de requisição
- Delegam lógica para Services
- Retornam Resources formatadas

**Exemplo:**
```php
// app/Presentation/Http/Controllers/ClienteController.php

namespace App\Http\Controllers\Api;

use App\Core\UseCase\Client\CreateClientUseCase;
use App\Core\UseCase\Client\DeleteClientUseCase;
use App\Core\UseCase\Client\DTO\ClientInputDto;
use App\Core\UseCase\Client\GetClientUseCase;
use App\Core\UseCase\Client\ListClientsUseCase;
use App\Core\UseCase\Client\UpdateClientUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

class ClientController extends Controller
{
    public function __construct(
        private CreateClientUseCase $createClientUseCase,
        private UpdateClientUseCase $updateClientUseCase,
        private DeleteClientUseCase $deleteClientUseCase,
        private ListClientsUseCase $listClientsUseCase,
        private GetClientUseCase $getClientUseCase
    ) {}
```

#### 1.2 Form Requests 

**Localização:** `app/Http/Requests`

- Validam entrada de dados
- Transformam dados antes de passar para Service
- Retornam erros 422 se inválido

**Exemplo:**
```php
// app/Presentation/Http/Requests/StoreClienteRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'document' => 'required|string|max:255',
        ];
    }
```


## 2️⃣ APPLICATION LAYER (Camada de Aplicação)

**Localização:** `app/Core/UseCase`

#### 2.2 DTOs (Data Transfer Objects) (`DTOs/`)
- Objetos para transferência de dados entre camadas
- Imutáveis
- Tipadas fortemente

**Exemplo:**
```php

namespace App\Core\UseCase\Project\DTO;

class EquipmentDto
{
    public function __construct(
        public readonly string $type,
        public readonly int $quantity
    ) {}
}
```

---

## 3️⃣ DOMAIN LAYER (Camada de Domínio)

**Localização:** `app/Domain/`

Contém a lógica mais importante do negócio - independente de frameworks.

### Componentes

#### 3.1 Entities (`Entities/`)
- Representam conceitos do negócio
- Contêm regras de domínio
- Não dependem de banco de dados

**Exemplo:**
```php

namespace App\Core\Domain\Client\Entities;

use App\Core\Domain\Client\ValueObjects\CpfCnpj;

class Client
{
    public function __construct(
        private ?string $id,
        private string $name,
        private string $email,
        private string $phone,
        private CpfCnpj $document
    ) {}

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getDocument(): CpfCnpj
    {
        return $this->document;
    }

    public function update(string $name, string $email, string $phone): void
    {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
    }
}
```

#### 3.2 Repository Interfaces (`Repositories/`)
- Definem contrato para persistência
- Agnósticas a implementação de banco de dados

**Exemplo:**
```php
// app/Domain/Repositories/ClienteRepositoryInterface.php

namespace App\Core\Domain\Client\Repositories;

use App\Core\Domain\Client\Entities\Client;
use App\Core\Domain\RepositoryInterface;

interface ClientRepositoryInterface extends RepositoryInterface
{
    public function create(Client $client): Client;
    public function update(Client $client): Client;
    public function delete(string $id): bool;
    public function findById(string $id): ?Client;
    /**
     * @return Client[]
     */
    public function findAll(array $filters = []): array;
    public function findByEmail(string $email): ?Client;
    public function findByDocument(string $document): ?Client;
}
```

#### 3.3 Value Objects
- Objetos que representam valores
- Imutáveis e comparáveis por valor

**Exemplo:**
```php
// App\Core\Domain\Client\ValueObjects

namespace App\Core\Domain\Client\ValueObjects;

use InvalidArgumentException;

class CpfCnpj
{
    private string $value;
    private string $type; // 'CPF' or 'CNPJ'

    public function __construct(string $value)
    {
        $sanitized = $this->sanitize($value);

        if (!$this->isValid($sanitized)) {
            throw new InvalidArgumentException("Invalid CPF/CNPJ: $value");
        }

        $this->value = $sanitized;
        $this->type = strlen($sanitized) === 11 ? 'CPF' : 'CNPJ';
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function __toString(): string
    {
        return $this->format();
    }

    public function format(): string
    {
        if ($this->type === 'CPF') {
            return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $this->value);
        }
        return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $this->value);
    }

    private function sanitize(string $value): string
    {
        return preg_replace('/\D/', '', $value);
    }

    private function isValid(string $value): bool
    {
        if (strlen($value) === 11) {
            return $this->validateCpf($value);
        }
        if (strlen($value) === 14) {
            return $this->validateCnpj($value);
        }
        return false;
    }

    private function validateCpf(string $cpf): bool
    {
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }

    private function validateCnpj(string $cnpj): bool
    {
        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }

        $b = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        for ($i = 0, $n = 0; $i < 12; $n += $cnpj[$i] * $b[++$i]);
        if ($cnpj[12] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
            return false;
        }

        for ($i = 0, $n = 0; $i <= 12; $n += $cnpj[$i] * $b[$i++]);
        if ($cnpj[13] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
            return false;
        }
        return true;
    }
}
```

---

## 4️⃣ INFRASTRUCTURE LAYER (Camada de Infraestrutura)

**Localização:** `app/Infrastructure/`

Implementa detalhes técnicos: banco de dados, APIs externas, etc.

### Componentes

#### 4.1 Repository Implementations (`Repositories/`)
- Implementam interfaces do Domain
- Usam Models Eloquent
- Isolam detalhes do banco de dados

**Exemplo:**
```php
// app/Infrastructure/Persistence/Eloquent

namespace App\Core\Infrastructure\Persistence\Eloquent\Project;

use App\Core\Domain\Project\Entities\Project as ProjectEntity;
use App\Core\Domain\Project\Enums\EquipmentType;
use App\Core\Domain\Project\Enums\InstallationType;
use App\Core\Domain\Project\Enums\UF;
use App\Core\Domain\Project\Repositories\ProjectRepositoryInterface;
use App\Core\Domain\Project\ValueObjects\Equipment;
use App\Models\Project as ProjectModel;
use App\Models\ProjectEquipment as ProjectEquipmentModel;

class EloquentProjectRepository implements ProjectRepositoryInterface
{
    public function create(ProjectEntity $project): ProjectEntity
    {
        $model = ProjectModel::create([
            'client_id' => $project->getClientId(),
            'uf' => $project->getUf()->value,
            'installation_type' => $project->getInstallationType()->value,
        ]);

        // Create equipment records
        foreach ($project->getEquipment() as $equipment) {
            ProjectEquipmentModel::create([
                'project_id' => $model->id,
                'equipment_type' => $equipment->getType()->value,
                'quantity' => $equipment->getQuantity(),
            ]);
        }

        // Reload with relationships
        $model->load('equipment');

        return $this->toEntity($model);
    }

    ...
```

#### 4.2 Models Eloquent (`Models/`)
- Representam tabelas no banco
- Usados apenas na camada de Infraestrutura
- NÃO devem conter lógica de negócio

**Exemplo:**
```php
// app/Models/Client.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'email',
        'phone',
        'document',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }
}
```

---

## 💡 Benefícios da Arquitetura

✅ **Testabilidade** - Cada camada pode ser testada independentemente  
✅ **Manutenibilidade** - Código organizado e fácil de modificar  
✅ **Escalabilidade** - Fácil adicionar novas features  
✅ **Independência de Framework** - Lógica não depende de Laravel  
✅ **Flexibilidade** - Trocar banco de dados ou API sem afetar negócio  

---

## 📦 Padrões Utilizados

### Dependency Injection
Todas as dependências são injetadas via construtor:

```php
public function __construct(
    private ClienteRepositoryInterface $repository,
    private ClienteService $service
) {}
```

### Repository Pattern
Abstração para acesso a dados:

```php
interface ClienteRepositoryInterface
{
    public function criar(...): Cliente;
    public function obterPorId(int $id): ?Cliente;
}
```

### DTO Pattern
Transferência tipada de dados:

```php
readonly class CriarClienteDTO
{
    public function __construct(
        public string $nome,
        public string $email,
    ) {}
}
```

### Service Locator (via Container)
Laravel Service Container gerencia dependências

---

## 🎯 Regras de Design

1. **Dependências apontam para dentro** - Camadas externas dependem de internas, nunca o oposto
2. **Sem lógica de negócio em Controllers** - Controllers são finos
3. **Models não têm lógica de negócio** - São apenas mapeadores ORM
4. **Entities podem ser testadas sem banco** - São POPOs (Plain Old PHP Objects)
5. **Interfaces definem contratos** - Implementações podem mudar

