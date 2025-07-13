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

## Tecnologias Utilizadas
Aqui descrevi as principais tecnologias utilizadas no projeto:

- **PHP**: Linguagem de programação utilizada.
- **HTML**: Linguagem de marcação utilizada para estruturar o conteúdo da loja online.
- **Composer**: Gerenciador de dependências do PHP.
- **PostgreSQL**: Sistema de gerenciamento de banco de dados relacional.
- **TailwindCSS**: Framework CSS utilizado para estilização.
- **Docker**: Utilizado para containerização do ambiente de desenvolvimento.
- **Docker Compose**: Utilizado para orquestrar os containers do Docker.

## Funcionalidades
Listei as principais funcionalidades implementadas na loja online dos requisitos do projeto:
- [ ] Landing page
    - [ ] Tela explicativa do projeto.
    - [ ] Rota para listagem de ingressos.
    - [ ] Botões para login e registro de usuários.
    - [ ] Botões para login e registro de clientes.
 
- [ ] Usuários
  - [ ] Registro de usuários.
  - [ ] Visualização do perfil de usuário (apenas o proprietário).
  - [ ] Edição de perfil de usuário (apenas o proprietário).
  - [ ] Deletar o perfil de usuário (apenas o proprietário).

- [ ] Clientes
    - [ ] Registro de clientes.
    - [ ] Perfil de cliente (apenas o proprietário).
    - [ ] Edição de perfil de cliente (apenas o proprietário).
    - [ ] Deletar o perfil de cliente (apenas o proprietário).

- [ ] Ingressos
  - [ ] Listagem de ingressos disponíveis com quantidade superior a 0.
  - [ ] QR Code para cada ingresso.
  - [ ] Listagem de ingressos comprados pelo cliente (apenas o cliente proprietário).
  - [ ] Criação de ingressos (apenas por usuários).
  - [ ] Edição de ingressos (apenas o usuário proprietário).
  - [ ] Deletar ingressos (apenas o usuário proprietário).

- [ ] Eventos
    - [ ] Listagem de eventos disponíveis.
    - [ ] Listagem de eventos criados pelo usuário (apenas o usuário proprietário).
    - [ ] Criação de eventos (apenas por usuários).
    - [ ] Edição de eventos (apenas o proprietário).
    - [ ] Deletar eventos (apenas o proprietário).

- [ ] Compras
    - [ ] Registro de compras quando um cliente compra ingressos (apenas clientes).
      - [ ] Reserva de ingressos quando um cliente seleciona um evento por 2 minutos.
    - [ ] Historico de compras do cliente (apenas o cliente proprietário).
    - [ ] Gerar comprovante de compra PDF (apenas o cliente proprietário).

- [ ] Autenticação
  - [ ] Login de clientes.
  - [ ] Login de usuários.
  - [ ] Verificação de autenticação para acessar áreas restritas.
  - [ ] Verificação de permissões para acessar áreas restritas.

- [ ] Segurança
  - [ ] Proteção contra CSRF (Cross-Site Request Forgery).
  - [ ] Proteção contra XSS (Cross-Site Scripting).
  - [ ] Proteção contra SQL Injection.
  - [ ] Proteção contra ataques de força bruta.

- [ ] Validações
    - [ ] Validação de dados de entrada.
    - [ ] Validações nos dados do banco de dados.

## Estrutura de Endpoints
Como os endpoints são organizados no projeto, cada recurso tem suas próprias rotas para operações.
- `/`: Rota para a página inicial.

- `/users/register`: Rota para o registro de usuários.
- `/users/login`: Rota para o login de usuários.
- `/users/profile`: Rota para o perfil de usuário.
- `/users/edit`: Rota para editar o perfil de usuário.
- `/users/delete`: Rota para deletar o perfil de usuário.

- `/tickets`: Rota para listar ingressos disponíveis.
- `/tickets/{id}`: Rota para visualizar detalhes de um ingresso (apenas o proprietário).

- `/tickets/reserve`: Rota para criar e reservar ingressos (apenas clientes).
- `/tickets/buy/{id}`: Rota para comprar ingressos (apenas clientes).
- `/tickets/purchased`: Rota para listar ingressos comprados pelo cliente (apenas o cliente proprietário).

- `/events`: Rota para listar eventos disponíveis.
- `/events/{id}`: Rota para visualizar detalhes de um evento.
- `/events/save`: Rota para criar eventos (apenas usuários).
- `/events/save?id={id}`: Rota para editar eventos (apenas o proprietário).
- `/events/delete/{id}`: Rota para deletar eventos (apenas o proprietário).
- `/events/purchased`: Rota para listar eventos comprados pelo cliente (apenas o cliente proprietário).
