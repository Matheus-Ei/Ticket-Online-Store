# Php-Online-Store
Esse repositório contém o código fonte de uma loja online de **ingressos** desenvolvida em PHP, utilizando o padrão MVC, tailwindCss para os estilos, e PostgreSQL como banco de dados.

## Instalação
Para instalar o projeto, é importante ter o Docker e o Docker Compose instalados em sua máquina. Siga os passos abaixo para configurar o ambiente:

1. Clone o repositório:
   ```bash
   git clone https://github.com/Matheus-Ei/Php-Online-Store.git
   ```

2. Navegue até o diretório do projeto:
   ```bash
    cd Php-Online-Store
   ```

3. Crie um arquivo `.env` a partir do arquivo `.env.example`:
   ```bash
    cp .env.example .env
   ```

4. Inicie os containers do Docker:
   ```bash
    docker-compose up -d
   ```

5. Acesse a pagina inicial da loja online em seu navegador:
   ```
   http://localhost:8080
   ```

## Rodar Testes
Para rodar os testes do projeto, você pode utilizar o PHPUnit. Certifique-se de que o PHPUnit está instalado e configurado corretamente. Em seguida, execute o seguinte comando:

```bash
./vendor/bin/phpunit
```

## Tecnologias Utilizadas
Aqui descrevi as principais tecnologias utilizadas no projeto:

- **PHP**: Linguagem de programação utilizada.
- **HTML**: Linguagem de marcação utilizada para estruturar o conteúdo da loja online.
- **Composer**: Gerenciador de dependências do PHP.
- **PostgreSQL**: Sistema de gerenciamento de banco de dados relacional.
- **TailwindCSS**: Framework CSS utilizado para estilização.
- **Docker**: Utilizado para containerização do ambiente de desenvolvimento.
- **Docker Compose**: Utilizado para orquestrar os containers do Docker.
- **PHPUnit**: Framework de testes para PHP.

## Funcionalidades
Listei as principais funcionalidades implementadas na loja online dos requisitos do projeto:
- [x] Landing page
    - [x] Rota para listagem de eventos.
    - [x] Botões para login e registro de usuários.
    - [x] Botões para login e registro de clientes.
 
- [x] Usuários (vendedor)
  - [x] Registro de usuários.
  - [x] Visualização do perfil de usuário (apenas o proprietário).
  - [x] Edição de perfil de usuário (apenas o proprietário).
  - [x] Deletar o perfil de usuário (apenas o proprietário).

- [x] Clientes
    - [x] Registro de clientes.
    - [x] Perfil de cliente (apenas o proprietário).
    - [x] Edição de perfil de cliente (apenas o proprietário).
    - [x] Deletar o perfil de cliente (apenas o proprietário).

- [x] Ingressos
  - [x] Listagem de ingressos disponíveis com quantidade superior a 0.
  - [x] QR Code para cada ingresso.
  - [x] Listagem de ingressos comprados pelo cliente (apenas o cliente proprietário).
  - [x] Comprar ingressos (apenas por usuários).

- [x] Eventos
    - [x] Listagem de eventos disponíveis.
    - [x] Listagem de eventos criados pelo usuário (apenas o usuário proprietário).
    - [x] Criação de eventos (apenas por usuários).
    - [x] Edição de eventos (apenas o proprietário).
    - [x] Deletar eventos (apenas o proprietário).

- [x] Compras
    - [x] Registro de compras quando um cliente compra ingressos (apenas clientes).
      - [x] Reserva de ingressos quando um cliente seleciona um evento por 2 minutos.
    - [x] Historico de compras do cliente (apenas o cliente proprietário).
    - [x] Gerar comprovante de compra PDF (apenas o cliente proprietário).

- [x] Autenticação
  - [x] Login de clientes.
  - [x] Login de usuários.
  - [x] Verificação de autenticação para acessar áreas restritas.
  - [x] Verificação de permissões para acessar áreas restritas.

- [x] Segurança
  - [x] Proteção contra CSRF (Cross-Site Request Forgery).
  - [x] Proteção contra XSS (Cross-Site Scripting).
  - [x] Proteção contra SQL Injection.

- [x] Validações
    - [x] Validação de dados de entrada.
    - [x] Validação de dados ao mostrar na view.
    - [x] Validações nos dados do banco de dados.

- [x] Testes
    - [x] Testes unitários dos services para as principais funcionalidades com regra de negócio.

## Estrutura de Endpoints
A seguir, a estrutura de endpoints da aplicação, agrupada por recurso.

#### Eventos
  - `GET /` ou `/events`: Rota principal que lista todos os eventos disponíveis.
  - `GET /events/purchased`: Lista os eventos cujos ingressos foram comprados pelo cliente logado.
  - `GET /events/save`: Exibe o formulário para criar ou editar um evento. Pode receber um `id` como parâmetro de query para edição (`/events/save?id={id}`).
  - `POST /events/save`: Processa a criação ou atualização de um evento.
  - `GET /events/seller`: Exibe todos os eventos criados pelo vendedor logado.
  - `GET /events/{id}`: Exibe os detalhes de um evento específico.
  - `POST /events/delete/{id}`: Deleta um evento específico (apenas o proprietário).

#### Usuários (Clientes e Vendedores)
  - `GET /users/register`: Exibe o formulário de registro.
  - `POST /users/register`: Processa o registro de um novo usuário.
  - `GET /users/login`: Exibe o formulário de login.
  - `POST /users/login`: Processa o login de um usuário.
  - `POST /users/logout`: Desconecta o usuário logado.
  - `GET /users/profile`: Exibe o perfil do usuário logado.
  - `GET /users/edit`: Exibe o formulário para editar o perfil.
  - `POST /users/edit`: Processa a atualização do perfil.
  - `POST /users/delete`: Deleta a conta do usuário logado.

#### Ingressos (Tickets)
  - `GET /tickets/purchased`: Lista todos os ingressos comprados pelo cliente logado.
  - `POST /tickets/reserve`: Reserva um ingresso para um evento, antes da compra efetiva.
  - `GET /tickets/buy`: Exibe a página de confirmação de compra de um ingresso reservado.
  - `POST /tickets/buy`: Efetiva a compra de um ingresso.
  - `POST /tickets/expire`: Endpoint para expirar uma reserva de ingresso que não foi comprada a tempo.
  - `GET /tickets/{id}`: Exibe os detalhes de um ingresso específico comprado.
  - `GET /tickets/generate-pdf/{ticketId}`: Gera e baixa o comprovante de compra de um ingresso em formato PDF.

