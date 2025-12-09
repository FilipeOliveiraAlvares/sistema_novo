# Configuração do Arquivo .env

## 📋 Instruções

As configurações do banco de dados foram movidas para o arquivo `.env` para maior segurança e flexibilidade.

### 1. Criar o arquivo .env

⚠️ **IMPORTANTE**: O arquivo deve se chamar `.env` (com ponto no início), não apenas `env`.

Copie o arquivo `env` para `.env` na raiz do projeto:

```bash
# Windows (PowerShell)
Copy-Item env .env

# Linux/Mac
cp env .env
```

**Nota**: O arquivo `env` é apenas um template. O CodeIgniter 4 procura especificamente pelo arquivo `.env` (com ponto).

### 2. Configurar as variáveis de banco de dados

Abra o arquivo `.env` e configure as seguintes variáveis:

```env
# Configurações do banco de dados padrão
database.default.hostname = localhost
database.default.database = goodex_google
database.default.username = root
database.default.password = sua_senha_aqui
database.default.DBDriver = MySQLi
database.default.DBPrefix = 
database.default.port = 3306
database.default.charset = utf8mb4
database.default.DBCollat = utf8mb4_unicode_ci
database.default.pConnect = false
database.default.DBDebug = true
database.default.encrypt = false
database.default.compress = false
database.default.strictOn = false
database.default.numberNative = false
database.default.foundRows = false
```

### 3. Valores padrão

Se as variáveis não estiverem definidas no `.env`, o sistema usará os seguintes valores padrão:

- **hostname**: `localhost`
- **database**: `goodex_google`
- **username**: `root`
- **password**: `` (vazio)
- **DBDriver**: `MySQLi`
- **port**: `3306`
- **charset**: `utf8mb4`
- **DBCollat**: `utf8mb4_unicode_ci`
- **DBDebug**: `true`
- **pConnect**: `false`
- **encrypt**: `false`
- **compress**: `false`
- **strictOn**: `false`
- **numberNative**: `false`
- **foundRows**: `false`

### 4. Segurança

⚠️ **IMPORTANTE**: O arquivo `.env` está no `.gitignore` e **NÃO** será commitado no repositório. Isso garante que suas credenciais de banco de dados não sejam expostas.

### 5. Para produção

Em produção, configure o arquivo `.env` com as credenciais corretas do servidor e defina:

```env
CI_ENVIRONMENT = production
database.default.DBDebug = false
```

## ✅ Verificação

Após configurar o `.env`, teste a conexão acessando a aplicação. Se houver problemas, verifique:

1. Se o arquivo `.env` existe na raiz do projeto
2. Se as variáveis estão descomentadas (sem `#` no início)
3. Se os valores estão corretos
4. Se o banco de dados está acessível


