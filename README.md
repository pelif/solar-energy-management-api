# 🌞 Solar Energy Management API

API robusta para gestão de projetos de energia solar, desenvolvida com **Laravel** seguindo os princípios de **Clean Architecture**, com testes unitários e documentação Swagger interativa.

## 📋 Sobre o Projeto

Este sistema permite que integradores solares gerenciem de forma eficiente:

- **Clientes** - Cadastro, edição e exclusão com validação de CPF/CNPJ
- **Projetos** - Criação e gestão completa de projetos solares
- **Equipamentos** - Gerenciamento de categorias e quantidades por projeto
- **Localizações** - Suporte a todos os estados brasileiros (UF)
- **Tipos de Instalação** - Fibrocimento, Cerâmico, Metálico, Laje, Solo, etc

## 🎯 Objetivos Técnicos

✅ Implementar **Clean Architecture** em Laravel  
✅ Cobertura completa com **testes unitários (PHPUnit)**  
✅ Documentação interativa com **Swagger/OpenAPI**  
✅ **Docker** para ambiente reprodutível  
✅ **Código limpo** e boas práticas (PSR-12)  
✅ **Versionamento semântico** com Git
✅ **CI/CD no projeto com GIthub Actions* - Ao fazer um push para a branch main o GitHub Actions builda o ambiente docker para validar consistÊncia do mesmo e executa os testes unitários e de integração para validar a consistência do código

## 🚀 Quick Start (Docker)

### Pré-requisitos
- Docker & Docker Compose
- Git

### Instalação Rápida

```bash
# 1. Clone o repositório
git clone https://github.com/pelif/solar-energy-management-api.git

cd solar-energy-management-api

# 2. Configure variáveis de ambiente
cp .env.example .env

# 3. Configure as variáveis de ambiente no arquivo .env
Veja Quais São as variáveis de ambiente do Mysql no docker-compose.yml e configure no arquivo .env

# 4. Inicie os containers
docker-compose up -d --build

# 5. Rodando o comando você poderá ver os serviços representados por containers rodandoo
docker compose ps 

# 6. Acesse a aplicação
API:        http://localhost:8088
Swagger:    http://localhost:8088/api/documentation

OBS: 
O ambiente vai fazer um proxy reverso entre container Nginx com 
Container da Apicação e vai servir a API na porta 8088

```

## 📁 Estrutura do Projeto

```
solar-energy-management-api/
app/
├── Core
│   ├── Domain
│   │   ├── Client
│   │   │   ├── Entities
│   │   │   │   └── Client.php
│   │   │   ├── Repositories
│   │   │   │   └── ClientRepositoryInterface.php
│   │   │   └── ValueObjects
│   │   │       └── CpfCnpj.php
│   │   ├── Project
│   │   │   ├── Entities
│   │   │   │   └── Project.php
│   │   │   ├── Enums
│   │   │   │   ├── EquipmentType.php
│   │   │   │   ├── InstallationType.php
│   │   │   │   └── UF.php
│   │   │   ├── Repositories
│   │   │   │   └── ProjectRepositoryInterface.php
│   │   │   └── ValueObjects
│   │   │       └── Equipment.php
│   │   └── RepositoryInterface.php
│   ├── Infrastructure
│   │   └── Persistence
│   │       └── Eloquent
│   │           ├── Client
│   │           │   └── EloquentClientRepository.php
│   │           └── Project
│   │               └── EloquentProjectRepository.php
│   └── UseCase
│       ├── Client
│       │   ├── CreateClientUseCase.php
│       │   ├── DeleteClientUseCase.php
│       │   ├── DTO
│       │   │   ├── ClientInputDto.php
│       │   │   └── ClientOutputDto.php
│       │   ├── GetClientUseCase.php
│       │   ├── ListClientsUseCase.php
│       │   └── UpdateClientUseCase.php
│       ├── Project
│       │   ├── CreateProjectUseCase.php
│       │   ├── DeleteProjectUseCase.php
│       │   ├── DTO
│       │   │   ├── EquipmentDto.php
│       │   │   ├── ProjectInputDto.php
│       │   │   └── ProjectOutputDto.php
│       │   ├── GetProjectUseCase.php
│       │   ├── ListProjectsUseCase.php
│       │   └── UpdateProjectUseCase.php
│       └── UseCaseInterface.php
├── Http
│   ├── Controllers
│   │   ├── Api
│   │   │   ├── AuthController.php
│   │   │   ├── ClientController.php
│   │   │   ├── EquipmentTypeController.php
│   │   │   ├── InstallationTypeController.php
│   │   │   └── ProjectController.php
│   │   └── Controller.php
│   └── Requests
│       ├── StoreClientRequest.php
│       ├── StoreProjectRequest.php
│       ├── UpdateClientRequest.php
│       └── UpdateProjectRequest.php
├── Models
│   ├── Client.php
│   ├── ProjectEquipment.php
│   ├── Project.php
│   └── User.php
└── Providers
    ├── AppServiceProvider.php
    └── RepositoryServiceProvider.php
```

## 🧪 Testes Unitários

```bash
#Acesse o container de Aplicação com o seguinte comando: 
docker compose exec solar_energy_management_app sh 

#Se não acessar, tente o comando: 
docker compose exec solar_energy_management_app bash

#Para rodar todos os testes com Cobertura de testes rode
php artisan test --coverage --min=0

# Para rodar testes unitários
php artisan test tests/Unit

# Para rodar os testes de integração e E2E
php artisan test tests/Feature

```


### Cobertura Esperada
- ✅ Services e Use Cases (Application layer)
- ✅ Repository implementations
- ✅ Validações e DTOs
- ✅ Controllers (Feature tests)
- ✅ Modelos e relacionamentos

## 📚 Documentação

A documentação completa está organizada em:

| Documento | Descrição |
|-----------|-----------|
| [**ARCHITECTURE.md**](./docs/ARCHITECTURE.md) | Design técnico e padrões de Clean Architecture |
| [**DATABASE.md**](./docs/DATABASE.md) | Modelo de dados, entidades e relacionamentos |
| [**SETUP.md**](./docs/SETUP.md) | Instruções detalhadas de instalação e configuração |
| [**CONTRIBUTING.md**](./docs/CONTRIBUTING.md) | Padrões de código, git workflow e boas práticas |

## 🔗 Swagger UI (Documentação Interativa)

Após iniciar a aplicação, acesse a documentação interativa:

```
http://localhost:8088/api/documentation
```

Aqui você pode:
- ✅ Visualizar todos os endpoints
- ✅ Ver schemas de request/response
- ✅ Testar endpoints diretamente no navegador
- ✅ Copiar exemplos de code

**Usuário para Logar na API no Swagger:**
login: admin@solar.com
senha: password

**OBS** 
As credenciais acima são para serem usar no endpoint de login da API.

## 🏗️ Clean Architecture - Visão Geral

Este projeto implementa as 4 camadas de Clean Architecture:

```
┌─────────────────────────────────────────────┐
│         PRESENTATION LAYER                  │
│    (Controllers, HTTP Requests, Resources)  │
└────────────────────┬────────────────────────┘
                     │
┌────────────────────▼────────────────────────┐
│        APPLICATION LAYER                    │
│   (Services, Use Cases, DTOs, Validators)   │
└────────────────────┬────────────────────────┘
                     │
┌────────────────────▼────────────────────────┐
│       DOMAIN LAYER                          │
│    (Entities, Interfaces, Business Rules)   │
└────────────────────┬────────────────────────┘
                     │
┌────────────────────▼────────────────────────┐
│     INFRASTRUCTURE LAYER                    │
│  (Database, Repositories, External APIs)    │
└─────────────────────────────────────────────┘
```

Cada camada é independente e testável isoladamente.

Importante salientar que por ser um projeto de teste e pequeno não faz muito sentido implementar todas as camadas no diagrama acima. Resources, Rules, Validators e External API não foram implementados.

## 🔑 Principais Recursos

### Clientes
- ✅ CRUD completo (Create, Read, Update, Delete)
- ✅ Validação de CPF/CNPJ
- ✅ E-mail e telefone
- ✅ Relacionamento 1:N com Projetos

### Projetos
- ✅ Associação obrigatória a cliente
- ✅ Localização por UF
- ✅ Tipo de instalação (Fibrocimento, Cerâmico, Metálico, Laje, Solo)
- ✅ Gerenciamento de equipamentos com quantidades
- ✅ Adicionar, remover e alterar equipamentos

### Equipamentos
- ✅ Categorias: Módulo, Inversor, Microinversor, Estrutura, Cabos, String Box, Endcap
- ✅ Quantidades por projeto
- ✅ CRUD de categorias

**Observação:**     
No repositório GitHub, você pode encontrar o arquivo .github/workflows/main.yml que contém a configuração do GitHub Actions.

## 📦 Tecnologias Utilizadas

- **Laravel 12.0** - Framework PHP
- **PHP 8.3+** - Linguagem
- **Laravel Sanctum** - Autenticação
- **Nginx** - Servidor Web
- **MySQL 5.7** - Banco de dados
- **Docker & Docker Compose** - Containerização
- **Swagger/OpenAPI** - Documentação
- **PHPUnit** - Testes unitários
- **Composer** - Gerenciador de dependências
- **Git** - Controle de versão

## 🔐 Autenticação & Autorização

Por enquanto, a API é aberta para todos os endpoints. Para produção, considere implementar:

- [ ] Laravel Sanctum (autenticação por token)

### Docker não inicia
```bash
# Limpe volumes antigos
docker-compose down -v
docker-compose up -d
```

**Developed by Felipe Daniel**