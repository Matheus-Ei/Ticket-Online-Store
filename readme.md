# Php-Online-Store
Esse repositório contém o código fonte de uma loja online de **ingressos** desenvolvida em PHP, utilizando o padrão MVC, tailwindCss para os estilos, e PostgreSQL como banco de dados.

**Eu hospedei esse site em: [Php-Online-Store](https://matheus-eickhoff.online) caso tenha interesse em ver**

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

## Hospedagem
Eu hospedei esse site pois achei que seria um bom extra para mostrar que tambem conheço de linux, maquinas virtuais e tambem de redes de computadores.

1. Para fazer isso eu ultilizei o sistema de hospedagem da Hetzner, que permite que você crie servidores virtuais na nuvem.

2. A partir disso usei o Docker e o Docker Compose para configurar o ambiente de desenvolvimento e produção, facilitando a replicação do ambiente em diferentes máquinas.

3. Os containers estavam expondo a porta 8080, então eu configurei o Nginx para fazer proxy reverso, redirecionando as requisições para a porta 80 da maquina virtual.

4. Usei um Dominio na GoDaddy (matheus-eickhoff.online), e apontei o domínio para o IP da máquina virtual na Hetzner com um registro A.

5. Depois disso eu usei o certbot para gerar um certificado SSL gratuito, garantindo que a comunicação entre o cliente e o servidor seja segura e com HTTPS.

6. Garanti que o firewall da maquina virtual permitisse apenas o acesso a porta 22 (SSH) e a 443 (HTTPS), bloqueando outras portas para aumentar a segurança do servidor.

## Explicando Decisões Técnicas
Na sessão a seguir, explico algumas decisões técnicas que tomei durante o desenvolvimento do projeto:

### Padrão MVC
Optei por utilizar o padrão MVC (Model-View-Controller) para organizar o código do projeto. Isso ajuda a separar as responsabilidades, tornando o código mais modular e fácil de manter.

### Gestão de Projetos com Github Projects
Utilizei o Github Projects para gerenciar as tarefas e funcionalidades do projeto. Isso me ajudou a manter o controle sobre o que estava sendo desenvolvido.

Aqui está o link do projeto: [Github Projects](https://github.com/users/Matheus-Ei/projects/10/views/1)

### Injeção de Dependências
Utilizei o padrão de injeção de dependências para gerenciar as dependências entre os componentes do sistema. 

Isso facilita a testabilidade e a manutenção do código, permitindo que as classes sejam mais independentes umas das outras. Tambem diminue o acoplamento entre as classes, tornando o código mais flexível e fácil de modificar.

Para isso eu criei uma classe `Container` que é responsável por gerenciar as dependências e instâncias dos serviços utilizados no projeto. Essa classe permite registrar serviços e resolvê-los quando necessário, facilitando a injeção de dependências em controllers e outros componentes.

Usei a classe estática `Provider` para registrar as binds no container.

### PostgreSQL com pasta `/sql`
Escolhi o PostgreSQL por que já tinha conhecimento prévio nesse banco de dados e porque acredito que é robusto e confiável.

A pasta `/sql` contém os scripts de criação do banco de dados e das tabelas, além de alguns dados iniciais para facilitar o desenvolvimento e os testes.

Preferi não colocar os scripts de criação do banco de dados diretamente no código PHP, pois isso poderia tornar o código mais complexo e menos legível. Em vez disso, optei por manter os scripts SQL separados, permitindo que sejam executados manualmente.

Tambem optei por não usar migrations ou seeders, pois o projeto é relativamente pequeno e não há necessidade de um sistema complexo de migração de banco de dados.

### TailwindCSS
Como não era a ideia principal do projeto focar no front-end e sim na lógica geral, escolhi a maneira mais facil e que eu já estava acostumado a ultilizar para fazer a estilização.

### Docker e Docker Compose
Já que era um dos bonus eu adicionei o Docker e o Docker Compose, mas mesmo que não fosse eu iria ultilizar, porque facilita a configuração e replicação do ambiente de desenvolvimento, e tambem facilita rodar o banco de dados PostgreSQL em um container isolado.

### Cron-tab para Expiração de Ingressos
Não achei uma maneira mais facil de implementar a expiração dos ingressos automatica reservados. Eu poderia simplimente delegar isso ao front-end(tambem fiz isso, mas não deixei apenas por isso), mas não teria uma forma de garantir que não teriam ingressos reservados no banco de dados e que não seriam expirados. 

Então ultilizei o front-end para expirar os ingressos, mas caso o cliente fechasse a tab ou tivesse algum erro, o cron-tab serviria para garantir que os ingressos seriam expirados após 2 minutos. 

Coloquei ele em um container separado para que ele pudesse rodar em background e não interferir no funcionamento da aplicação principal.

### Handler de Erro Global
Decidi implementar um handler de erro global porque isso removeria muito código repetido dos controllers(DRY - Don't Repeat Yourself) e tornaria o código mais limpo e fácil de manter.

Esse handler é responsável por capturar e tratar erros de forma centralizada, permitindo que a aplicação responda de maneira adequada a diferentes tipos de exceções. ele também registra os erros em um arquivo de log, facilitando a identificação e resolução de problemas.

### Validações em uma Camada Separada
Criei uma camada de validações separada para centralizar as regras de validação de dados. 

Isso ajuda a manter o código mais organizado e facilita a manutenção das regras de validação, limpando os controllers tambem e deixando eles mais focados na lógica de controle.

### Testes Unitários
Implementei testes unitários para os services do sistema, utilizando o PHPUnit. 

Os testes ajudam a garantir que as regras de negócio estejam funcionando corretamente e que futuras alterações no código não quebrem funcionalidades existentes.


## Arquitetura do Projeto
A arquitetura do projeto é baseada no padrão MVC (Model-View-Controller), que separa as responsabilidades em diferentes camadas. A seguir, uma breve descrição de cada camada:

### Views
Responsável por exibir as informações ao usuário. 

Aqui estão inclusos coisas como os layouts, que são como templates que posso usar e que removem muita repetição de código HTML.

Também tem os partials, que são pedaços de código HTML reutilizáveis, como cabeçalhos, rodapés e formulários.

### Controllers
Responsável por receber as requisições do usuário, processar os dados e chamar os serviços apropriados. 

### Services
Implementa a lógica de negócio do sistema. Os serviços contêm as regras de negócio e interagem com a camada de persistência (Model). 

Caso algo não esteja correto, eles lançam exceções que são tratadas pelo handler de erro global.

### Models
Interage diretamente com o banco de dados. Utiliza o PDO para executar consultas SQL e mapear os resultados para objetos do sistema.

### Validations
Responsável por validar os dados de entrada antes de serem processados pelos serviços. 

Lançam exceções em caso de dados inválidos (ValidationException).

### Exceptions
Define as exceções personalizadas utilizadas no sistema, como `ValidationException`, `NotFoundException`, `UnauthorizedException`, entre outras. 


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
  - [x] Proteção contra Session Fixation.

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

