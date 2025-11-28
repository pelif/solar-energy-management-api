# 🚀 SETUP.md - Instalação e Configuração

## Índice

1. [Opção 1: Setup com Docker (Recomendado)](#opção-1-setup-com-docker-recomendado)
3. [Configuração de Variáveis de Ambiente](#configuração-de-variáveis-de-ambiente)
4. [Populando o Banco de Dados](#populando-o-banco-de-dados)
5. [Verificação da Instalação](#verificação-da-instalação)
6. [Troubleshooting](#troubleshooting)

---

## Opção 1: Setup com Docker (Recomendado)

### Pré-requisitos

- **Docker** 20.10+ ([Instalar](https://docs.docker.com/get-docker/))
- **Docker Compose** 2.0+ ([Instalar](https://docs.docker.com/compose/install/))
- **Git** (para clonar o repositório)

### Passo a Passo

#### 1. Clonar o Repositório

```bash
git clone https://github.com/pelif/solar-energy-management-api.git
cd solar-energy-management-api
```

#### 2. Configurar Variáveis de Ambiente

```bash
cp .env.example .env
```

**OBS:** 
Configure as variáveis de ambiente do Mysql conforme o que foi setado do arquivo docker-compose.yml

#### 3. Iniciar os Containers

```bash
docker-compose up -d
```

**Verificar status:**
```bash
docker-compose ps
```

Você deve ver 3 containers rodando:
- `solar_energy_management_app` (Laravel)
- `solar_energy_management_db` (MySQL)
- `solar_energy_management_nginx` (Nginx)

#### 4. Instalar Dependências

```bash
docker-compose exec solar_energy_management_app sh
composer install
```

#### 5. Gerar Chave da Aplicação

```bash
docker-compose exec solar_energy_management_app sh "caso já esteja no container, não é mais necessário rodar este comando"
php artisan key:generate
```

#### 6. Executar Migrations

```bash
docker-compose exec solar_energy_management_app sh "caso já esteja no container, não é mais necessário rodar este comando" 
php artisan migrate
```

#### 7. Popular com Dados (Seeding)

```bash
docker-compose exec solar_energy_management_app sh "caso já esteja no container, não é mais necessário rodar este comando" 
php artisan db:seed
```

Ou ambos simultaneamente:
```bash
docker-compose exec solar_energy_management_app sh "caso já esteja no container, não é mais necessário rodar este comando" 
php artisan migrate --seed
```

#### 8. Gerar Documentação Swagger

```bash
docker-compose exec solar_energy_management_app sh "caso já esteja no container, não é mais necessário rodar este comando" 
php artisan l5-swagger:generate
```

**Usuário para Logar na API no Swagger:**
login: admin@solar.com
senha: password

**OBS** 
As credenciais acima são para serem usar no endpoint de login da API.

#### 9. Acessar a Aplicação

- **API Base:** http://localhost:8088
- **Swagger UI:** http://localhost:8088/api/documentation
- **MySQL:** localhost:3306 (user: `solar_energy_management`, password: `123456`)

---

