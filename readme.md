# Php-Online-Store
Esse repositório contém o código fonte de uma loja online desenvolvida em PHP, utilizando o padrão MVC, tailwindCss para os estilos, e PostgreSQL como banco de dados.

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
