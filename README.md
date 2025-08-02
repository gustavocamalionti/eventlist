# EventList SaaS 🚀

**SaaS para gestão de eventos com multitenancy em Laravel e frontend moderno com React**

[![License](https://img.shields.io/github/license/gustavocamalionti/eventlist)](https://github.com/gustavocamalionti/eventlist/blob/main/LICENSE)  
[![Laravel](https://img.shields.io/badge/Laravel-10.x-red.svg)](https://laravel.com/)  
[![PHP](https://img.shields.io/badge/PHP-8.1%2B-brightgreen.svg)](https://www.php.net/)  
[![Node.js](https://img.shields.io/badge/Node.js-18%2B-green.svg)](https://nodejs.org/)

---

## 🌐 Sobre

**EventList** é um sistema SaaS desenvolvido por nós da **AmongTech** (Mariana Gomes e Gustavo Camalionti) para empresas de eventos que desejam gerenciar seus eventos, credenciamento, pagamentos online e muito mais.

A plataforma oferece:

- Multitenancy com bancos separados para cada cliente
- Frontend modular com Blade e React (em transição completa para React + Inertia)
- Integração com **Asaas** (split de pagamento e automações)
- Automatização de fluxos: do lead à criação da conta

O sistema será futuramente comercializado como produto SaaS no domínio [eventlist.com.br](https://eventlist.com.br).

---

## ⚙️ Rodando em desenvolvimento

> Recomendado rodar em ambiente Ubuntu limpo, apenas com `nvm` e `curl` instalados

1. Clone o repositório e crie `.env` com base no `.env.example`

2. Gere `vendor` com:
    ```bash
    docker run --rm \
      -u "$(id -u):$(id -g)" \
      -v "$(pwd):/var/www/html" \
      -w /var/www/html \
      laravelsail/php82-composer:latest \
      composer install --ignore-platform-reqs
    ```

3. Suba o ambiente:
    ```bash
    ./vendor/bin/sail up -d
    ```

4. Use Node conforme `.nvmrc`:
    ```bash
    nvm use
    ```

5. No .env, Altere o usuário do banco de `sail` para `root`.

6. Instale e rode:
    ```bash
    ./vendor/bin/sail artisan migrate #sobe as tabelas do master
    ./vendor/bin/sail artisan tenants:migrate #atualiza todos os tenants com as novas tabelas
    ./vendor/bin/sail npm install #instala os pacotes do react
    ./vendor/bin/sail composer install #instala as dependencias do PHP
    ./vendor/bin/sail npm run build #monta um pacote do projeto em modo 'produção'
    ./vendor/bin/sail npm run dev #roda o projeto em modo 'desenvolvimento'
    ```

7. Acesse: `http://localhost:6001`

## OBS: Se der algum erro, pare o container da aplicação e inicie novamente `sail stop && sail up -d`

## 🧱 Estrutura de Exemplos

```text

├── Common/
├── Systems/
    └── Master/ ← Sistema principal
        ├── General/
        └── Modules/
           ├── Admin/   ← Painel administrativo
           ├── Site/    ← Site público
           └── Auth/    ← Autenticação
    └── Tenant/ ← Sistema dos locatários
        ├── General/
        └── Modules/
           ├── Admin/   ← Painel administrativo
           ├── Site/    ← Site público
           └── Auth/    ← Autenticação
resources/
├── legacy/          ← Blade + jQuery
└── react/           ← React + Inertia + Tailwind
```

- **Controllers** → Services → Repositories (com BaseService/Repository)

---

## 💾 Multitenancy

Cada empresa (tenant) tem seu banco isolado, com:

- Segurança e escalabilidade
- Backups e manutenções individualizadas
- Seleção automática via middleware

---

## 💳 Integração com Asaas

- Cobranças e pagamentos recorrentes
- Futuro suporte a **split de pagamento**
- Criação automatizada de contas para novos clientes

---

## 📦 Filas e Jobs

Jobs assíncronos para:

- Pagamentos
- E-mails e notificações
- Relatórios pesados

---

## ✅ Dev & CI

- **GitHub Actions**: Prettier, Commitlint, Secretlint
- **Husky**: hooks para pré-commit e pre-push
- Commits no padrão `feat:`, `fix:`, etc.
- Branch por feature/bugfix

---

## 👩‍💻 Equipe

Desenvolvido por **AmongTech**:

- [Mariana Gomes](https://github.com/marigomes-br)
- [Gustavo Camalionti](https://github.com/gustavocamalionti)

---

## 📎 Links

- 🔗 Repositório: https://github.com/gustavocamalionti/eventlist
- 🌐 Site: https://eventlist.com.br
- 📄 Asaas API: https://docs.asaas.com/
- 🐳 Laravel Sail: https://laravel.com/docs/sail
- ⚛️ Inertia.js: https://inertiajs.com
- 🎨 Tailwind: https://tailwindcss.com

---

## 🚧 Roadmap

- Lançar produção em `eventlist.com.br`
- Completar migração para React
- Automatizar deploy com CI/CD
- Adicionar testes e documentação
