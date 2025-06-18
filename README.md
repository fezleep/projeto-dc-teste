# Sistema de Gestão de Vendas - Teste DC Tecnologia

Este projeto foi desenvolvido como parte do processo seletivo para a vaga de desenvolvedor na DC Tecnologia, focado na criação de um sistema básico de gestão de vendas.

## 🚀 Recursos Principais

O sistema implementa as seguintes funcionalidades:

* **Registro de Vendas:** Permite cadastrar vendas informando o cliente (opcional), itens de venda (produtos e quantidades), e forma de pagamento.
* **Geração de Parcelas:** Automaticamente gera parcelas com valor e vencimento baseados na forma de pagamento selecionada.
* **Autenticação de Vendedor:** Integra login de usuários, vinculando o vendedor logado a cada venda realizada.
* **Listagem de Vendas:** Exibe todas as vendas registradas com opções de `filtros` por Cliente, Vendedor e Período de Data.
* **Edição e Exclusão:** Funcionalidades completas para `editar` e `excluir` registros de vendas.
* **Exportação em PDF:** Possibilidade de `baixar o resumo` de cada venda em formato PDF.

## 🛠️ Tecnologias Utilizadas

* **Backend:** PHP 8.x, Laravel 10.x
* **Banco de Dados:** MySQL
* **Frontend:** HTML, CSS (Tailwind CSS via Laravel Breeze), JavaScript / jQuery
* **PDF:** barryvdh/laravel-dompdf

## 💻 Requisitos

Para rodar o projeto localmente, você precisa ter:

* PHP >= 8.1
* Composer
* Node.js e npm (ou Yarn)
* MySQL

## ⚙️ Instalação e Uso

Siga estes passos para configurar e executar o projeto:

1.  **Clone o repositório:**
    ```bash
    git clone <URL_DO_SEU_REPOSITORIO>
    cd <nome_da_pasta_do_projeto>
    ```

2.  **Instale as dependências:**
    ```bash
    composer install
    npm install
    # ou yarn install
    ```

3.  **Configure o ambiente:**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
    *Edite o arquivo `.env`* com suas credenciais de banco de dados (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).

4.  **Execute as migrações e seeders:**
    Este comando limpa o banco de dados e cria as tabelas, populando com dados essenciais (ex: usuário admin).
    ```bash
    php artisan migrate:fresh --seed
    ```

5.  **Compile os assets do frontend:**
    ```bash
    npm run dev
    # ou npm run build para produção
    ```

6.  **Inicie o servidor local:**
    ```bash
    php artisan serve
    ```

7.  **Acesse o sistema:**
    Abra seu navegador e vá para `http://127.0.0.1:8000` (ou o endereço indicado no console).

## 🔑 Credenciais de Acesso (Exemplo)

* **Email:** `admin@example.com`
* **Senha:** `password`

## ✨ Considerações

O projeto foi desenvolvido priorizando a clareza do código, uso de transações de banco de dados para garantir a integridade dos dados, e validações para entrada de dados. Os relacionamentos Eloquent foram utilizados para facilitar a manipulação e recuperação de informações entre as entidades.