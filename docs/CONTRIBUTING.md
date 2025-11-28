# 📝 CONTRIBUTING.md - Boas Práticas e Versionamento

## 🎯 Guia de Contribuição

Este documento define os padrões e boas práticas para contribuir com este projeto.

---

## 📋 Índice

1. [Antes de Começar](#antes-de-começar)
2. [Estrutura de Branches](#estrutura-de-branches)
3. [Commits Semânticos](#commits-semânticos)
4. [Pull Requests](#pull-requests)
5. [Código Limpo](#código-limpo)
6. [Code Review](#code-review)
7. [Deployment](#deployment)

---

## Antes de Começar

### Setup Inicial

```bash
# 1. Fork o repositório (se não for owner)
# 2. Clone seu fork
git clone https://github.com/seu-usuario/solar-energy-management-api.git
cd solar-energy-management-api

# 3. Adicione o upstream
git remote add upstream https://github.com/pelif/solar-energy-management-api.git

# 4. Configure seu usuário Git
git config --global user.name "Seu Nome"
git config --global user.email "seu.email@example.com"

# 5. Instale dependências
composer install
docker-compose up -d
```

### Verificação Inicial

```bash
# Rode os testes
php artisan test

# Verifique o código
php artisan tinker
```

---

## 🌳 Estrutura de Branches

Usamos **Git Flow** adaptado para este projeto:

```
main
├── release/1.0.0
├── release/1.1.0
└── develop
    ├── feature/adicionar-validacao-cpf
    ├── feature/melhorar-performance
    ├── bugfix/corrigir-equipamentos
    └── hotfix/urgente-corrigir-banco
```

### Tipos de Branches

| Tipo | Base | Propósito |
|------|------|----------|
| `feature/*` | develop | Novas funcionalidades |
| `bugfix/*` | develop | Correção de bugs |
| `hotfix/*` | main | Correções urgentes em produção |
| `release/*` | develop | Preparar release |
| `docs/*` | develop | Documentação |

### Criando uma Branch

```bash
# Atualize develop
git checkout develop
git pull upstream develop

# Crie sua branch
git checkout -b feature/descricao-da-feature

# Exemplo real
git checkout -b feature/adicionar-validacao-cpf
git checkout -b bugfix/corrigir-paginacao
git checkout -b hotfix/urgente-corrigir-banco
```

---

## 💬 Commits Semânticos

Usamos **Conventional Commits** para manter histórico claro e organizado.

### Formato

```
<tipo>(<escopo>): <descrição>

<corpo opcional>

<footer opcional>
```

### Tipos Permitidos

| Tipo | Descrição | Exemplo |
|------|-----------|---------|
| **feat** | Nova funcionalidade | `feat(cliente): adicionar validação de CPF` |
| **fix** | Correção de bug | `fix(projeto): corrigir paginação` |
| **docs** | Documentação | `docs(api): atualizar README` |
| **style** | Formatação (sem lógica) | `style(codigo): remover espaços em branco` |
| **refactor** | Refatoração de código | `refactor(service): simplificar lógica` |
| **perf** | Otimizações | `perf(query): otimizar busca de clientes` |
| **test** | Testes | `test(cliente): adicionar testes de validação` |
| **chore** | Tarefas auxiliares | `chore(deps): atualizar composer` |
| **ci** | CI/CD | `ci(github): configurar testes automáticos` |

### Escopos Comuns

- `cliente` - Contexto de Clientes
- `projeto` - Contexto de Projetos
- `equipamento` - Contexto de Equipamentos
- `api` - Geral da API
- `db` - Banco de Dados
- `docker` - Containerização
- `auth` - Autenticação

### Exemplos de Commits

```bash
# ✅ Bom: descritivo e específico
git commit -m "feat(cliente): adicionar validação de CPF/CNPJ"

git commit -m "fix(projeto): corrigir bug ao adicionar equipamentos"
git commit -m "fix(projeto): corrigir quantidade de equipamentos"

git commit -m "docs(api): atualizar endpoint de clientes no Swagger"

git commit -m "refactor(service): extrair lógica de validação em classe"

git commit -m "test(cliente): adicionar testes para validação de CPF"

git commit -m "perf(query): adicionar índice em clientes.email"

# Com corpo
git commit -m "feat(cliente): adicionar validação de CPF

Implementa validação de CPF usando algoritmo de dígito verificador.
- Valida formato XXX.XXX.XXX-XX
- Verifica dígitos verificadores
- Rejeita CPFs conhecidos como inválidos (111.111.111-11)

Closes #123"

# ❌ Ruim: muito genérico
git commit -m "fix: correções"
git commit -m "atualizar codigo"
git commit -m "WIP"
```

### Template de Commit

Configure um template local:

```bash
cat > .git/hooks/commit-msg << 'EOF'
#!/bin/bash
# Valida formato conventional commit

if ! head -1 "$1" | grep -qE "^(feat|fix|docs|style|refactor|perf|test|chore|ci)(\(.+\))?!?: .{1,}"; then
  echo "❌ Commit deve seguir formato: type(scope): description"
  exit 1
fi
EOF

chmod +x .git/hooks/commit-msg
```

---

## 🔄 Workflow Completo (Exemplo)

### Cenário: Adicionar Validação de CPF

```bash
# 1. Atualize a branch develop
git checkout develop
git pull upstream develop

# 2. Crie uma feature branch
git checkout -b feature/adicionar-validacao-cpf

# 3. Trabalhe no código
# - Edite arquivos
# - Crie testes
# - Execute testes: php artisan test

# 4. Stage suas mudanças
git add app/Application/Validators/CPFValidator.php
git add tests/Unit/Application/Validators/CPFValidatorTest.php

# 5. Faça commits semânticos
git commit -m "feat(cliente): implementar validação de CPF"
git commit -m "test(cliente): adicionar testes para CPFValidator"

# 6. Push para seu fork
git push origin feature/adicionar-validacao-cpf

# 7. Abra Pull Request no GitHub
# - Descreva a mudança
# - Referencie issues relacionadas
# - Aguarde review
```

---

## 📥 Pull Requests

### Antes de Abrir um PR

- [ ] Rode todos os testes: `php artisan test`
- [ ] Verifique cobertura: `php artisan test --coverage`
- [ ] Siga padrão de commits
- [ ] Atualize documentação se necessário
- [ ] Adicione testes para novas funcionalidades
- [ ] Limpe código comentado

### Template de PR

```markdown
## 📝 Descrição

Descreva brevemente as mudanças feitas.

## 🎯 Relacionado

- Closes #123
- Relates to #456

## 🧪 Testes

- [ ] Novos testes adicionados
- [ ] Testes existentes passam
- [ ] Cobertura >= 80%

## 📋 Checklist

- [ ] Commits seguem conventional commits
- [ ] Documentação atualizada
- [ ] Sem código comentado
- [ ] Sem conflitos com develop

## 🎬 Como Testar

Passo a passo para testar:
```bash
git checkout feature/minha-feature
docker-compose up -d
php artisan test
```

## 📸 Screenshots (se aplicável)

Adicione screenshots do Swagger se alterou endpoints.
```

---

## 🧹 Código Limpo

### Padrão PSR-12

Seguimos PSR-12 (PHP Standard Recommendation):

```php
// ✅ Bom
class ClienteService
{
    public function criar(array $dados): Cliente
    {
        // 4 espaços de indentação
        if (empty($dados['nome'])) {
            throw new \InvalidArgumentException('Nome é obrigatório');
        }

        return $this->repository->criar(new CriarClienteDTO(...$dados));
    }
}

// ❌ Ruim
class ClienteService{
    public function criar($dados){
        //código mal formatado
        if(!isset($dados['nome']))throw new \Exception('erro');
        return $this->repository->criar($dados);
    }
}
```

### Naming Conventions

```php
// Classes: PascalCase
class ClienteService { }

// Methods: camelCase
public function criarCliente() { }

// Constants: UPPER_SNAKE_CASE
const SISTEMA_VERSAO = '1.0.0';

// Variables: $camelCase
$clienteId = 1;

// Interfaces: add Interface suffix
interface ClienteRepositoryInterface { }

// Traits: add Trait suffix
trait TimestampTrait { }
```

### Linhas Longas

Máximo de 120 caracteres por linha:

```php
// ✅ Bom
$resultado = $this->service->criar(
    $nome,
    $email,
    $cpf_cnpj,
    $telefone
);

// ❌ Ruim (> 120 chars)
$resultado = $this->service->criar($nome, $email, $cpf_cnpj, $telefone, $endereco, $cidade, $estado);
```

### Type Hints & Return Types

Sempre use type hints:

```php
// ✅ Bom
public function criar(string $nome, string $email): Cliente
{
    // código
}

public function listar(): array
{
    // código
}

// ❌ Ruim
public function criar($nome, $email)
{
    // código
}
```

### Documentação (PHPDoc)

```php
/**
 * Cria um novo cliente no sistema.
 *
 * @param CriarClienteDTO $dto Data transfer object com dados do cliente
 * @return Cliente Instância do cliente criado
 * @throws \DomainException Se CPF/CNPJ for inválido
 * @throws \InvalidArgumentException Se dados forem incompletos
 */
public function criar(CriarClienteDTO $dto): Cliente
{
    // código
}
```

---

## 👀 Code Review

### Como Revisar

1. **Leia o PR Description** - Entenda o objetivo
2. **Verifique Commits** - São semânticos?
3. **Analise Código** - Segue padrões?
4. **Rode Localmente** - Funciona?
5. **Execute Testes** - Todos passam?

### Comentários no Review

```python
# ✅ Positivo
"Excelente refatoração! Ficou bem mais legível."

# 🤔 Sugestão
"Que tal usar um loop aqui para reduzir repetição?"

# ⚠️ Problema
"Esta mudança quebra o test de autenticação. Pode corrigir?"

# 🚨 Bloqueante
"Isso aumenta o N+1 queries. Precisa ser otimizado."
```

### Checklist do Reviewer

- [ ] Código segue padrões do projeto
- [ ] Testes adequados e bem escritos
- [ ] Sem code smells óbvios
- [ ] Documentação atualizada
- [ ] Performance aceitável
- [ ] Segurança OK

---

## 🚀 Deployment

### Versioning (Semantic Versioning)

Usamos **semver**: `MAJOR.MINOR.PATCH`

```
1.2.3
│ │ │
│ │ └─ PATCH: Correções de bug (1.2.3 → 1.2.4)
│ └─── MINOR: Novas features (1.2.0 → 1.3.0)
└───── MAJOR: Breaking changes (1.0.0 → 2.0.0)
```

### Release Checklist

- [ ] Todos os PRs mergeados em develop
- [ ] Testes passando 100%
- [ ] Documentação atualizada
- [ ] CHANGELOG.md atualizado
- [ ] Versão em `composer.json` atualizada
- [ ] Tag criada: `git tag -a v1.2.3 -m "Release 1.2.3"`
- [ ] Push tag: `git push origin v1.2.3`

### Arquivo CHANGELOG.md

```markdown
# Changelog

## [1.2.3] - 2024-11-28

### Added
- Nova validação de CPF/CNPJ

### Fixed
- Corrigido bug de paginação

### Changed
- Melhorada performance de queries

### Security
- Atualizado pacote vulnerável
```

---

## 📚 Recursos Úteis

- [Conventional Commits](https://www.conventionalcommits.org/)
- [Semantic Versioning](https://semver.org/lang/pt_BR/)
- [PSR-12](https://www.php-fig.org/psr/psr-12/)
- [Clean Code PHP](https://github.com/jupeter/clean-code-php)
- [Git Flow Cheatsheet](https://danielkummer.github.io/git-flow-cheatsheet/)

---

## ❓ Dúvidas?

1. Consulte a documentação do projeto
2. Abra uma discussão (Discussion)
3. Entre em contato

---

**Obrigado por contribuir! Seu código é importante para o projeto! 💜**
