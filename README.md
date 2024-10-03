
# Comerc Test API

Este é um projeto de uma API RESTFul para o gerenciamento de pedidos de uma pastelaria, desenvolvida com **Lumen** e utilizando autenticação JWT, Eloquent ORM e filas para envio de emails. O front-end pode ser integrado utilizando Vue.js, e a aplicação é totalmente dockerizada para fácil instalação e execução.

## Tecnologias Utilizadas

- **Lumen 9**: Uma versão mais leve do Laravel, ideal para micro-serviços e APIs.
- **JWT (JSON Web Token)**: Para autenticação sem estado, garantindo segurança nas transações.
- **Eloquent ORM**: ORM (Object-Relational Mapping) que simplifica a interação com o banco de dados.
- **MySQL**: Sistema de gerenciamento de banco de dados relacional.
- **Docker**: Para facilitar a configuração do ambiente de desenvolvimento e produção.
- **PHP_CodeSniffer**: Para manter o código padronizado com PSR-12.

## Passos para Rodar o Projeto

### 1. Pré-requisitos

Certifique-se de ter os seguintes componentes instalados na sua máquina:

- **Docker** e **Docker Compose**: [Instalação Docker](https://docs.docker.com/get-docker/)
- **Git**: [Instalação Git](https://git-scm.com/book/en/v2/Getting-Started-Installing-Git)

### 2. Clonar o Repositório

Abra o terminal e execute o comando abaixo para clonar o repositório:

```bash
git clone git@github.com:dirceugrupoep/comerc_test.git
cd comerc_test
```

### 3. Configurar o Docker

O projeto já está configurado para ser executado via Docker. Para subir os containers, utilize o seguinte comando:

```bash
docker-compose up -d
```

Este comando irá inicializar o ambiente com os seguintes serviços:

- **MySQL**: Banco de dados relacional.
- **PHP-FPM**: Processador PHP.
- **Nginx**: Servidor web.
  
### 4. Configurar as Dependências do PHP

Dentro do container, execute o comando para instalar as dependências via **Composer**:

```bash
docker exec -it comerc_test_app composer install
```

```bash
docker exec -it comerc_test_app composer require illuminate/mail
```

### 5. Configurar o Banco de Dados

O arquivo `.env` já está configurado para o uso do banco de dados MySQL. Agora, rode as migrações para criar as tabelas:

```bash
docker exec -it comerc_test_app php artisan migrate
```

Se você deseja popular o banco com dados de exemplo (seeders):

```bash
docker exec -it comerc_test_app php artisan db:seed
```

### 6. Gerar a Chave JWT

A API usa autenticação JWT. Para gerar uma chave secreta, execute o comando abaixo:

```bash
docker exec -it comerc_test_app php artisan jwt:secret
```

### 7. Endpoints Disponíveis

Todos os endpoints seguem o padrão api/v1/.

#### Autenticação

- **Login**
  - **Método**: `POST`
  - **URL**: `/api/v1/login`
  - **Parâmetros**: 
    ```json
    {
      "email": "admin@example.com",
      "password": "@admin$123"
    }
    ```
  - **Resposta**:
    ```json
    {
      "token": "eyJ0eXAiOiJKV1..."
    }
    ```
Para enviar o token, adicione o cabeçalho Authorization com o valor Bearer {seu_token_aqui}. Exemplo no Insomnia:

Authorization: Bearer eyJ0eXAiOiJKV1...


#### Clientes

- **Listar Todos os Clientes**
  - **Método**: `GET`
  - **URL**: `/api/v1/clients`

- **Obter Detalhes de um Cliente**
  - **Método**: `GET`
  - **URL**: `/api/v1/clients/{id}`

- **Criar um Novo Cliente**
  - **Método**: `POST`
  - **URL**: `/api/v1/clients`
  - **Parâmetros**:
    ```json
    {
      "nome": "John Doe",
      "email": "johndoe@test.com",
      "telefone": "123456789",
      "data_nascimento": "1990-01-01",
      "endereco": "Rua 123",
      "bairro": "Centro",
      "cep": "12345-678"
    }
    ```
- **Atualizar um Cliente**
  - **Método**: `PUT`
  - **URL**: `/api/v1/clients/{id}`
- **Parâmetros**:
    ```json
    {
      "nome": "John Doe",
      "email": "johndoe@test.com",
      "telefone": "123456789",
      "data_nascimento": "1990-01-01",
      "endereco": "Rua 123",
      "bairro": "Centro",
      "cep": "12345-678"
    }
    ```    
- **Deletar um Cliente**
  - **Método**: `DELETE`
  - **URL**: `/api/v1/clients/{id}`

#### Produtos

- **Listar Todos os Produtos**
  - **Método**: `GET`
  - **URL**: `/api/v1/products`

- **Obter um produto**
  - **Método**: `GET`
  - **URL**: `/api/v1/products/{id}`

- **Criar um Novo Produto**
  - **Método**: `POST`
  - **URL**: `/api/v1/products`
  - **Parâmetros**:
    ```json
    {
      "nome": "Pastel de Queijo",
      "preco": 12.50,
      "foto": "image.jpg"
    }
    ```
- **Atualizar um Produto**
  - **Método**: `PUT`
  - **URL**: `/api/v1/products/{id}`
  - **Parâmetros**:
    ```json
    {
      "nome": "Pastel de Queijo",
      "preco": 12.50,
      "foto": "image.jpg"
    }
    ```
- **Deletar um Produto**
  - **Método**: `DELETE`
  - **URL**: `/api/v1/products`

#### Pedidos

- **Listar todos os Pedidos**
  - **Método**: `GET`
  - **URL**: `/orders`
  
- **Obter Detalhes de um Pedido**
  - **Método**: `GET`
  - **URL**: `/api/v1/orders/{id}`

- **Criar um Novo Pedido**
  - **Método**: `POST`
  - **URL**: `/api/v1/orders`
  - **Parâmetros**:
    ```json
    {
      "cliente_id": 1,
      "produtos": [
        {"id": 1, "quantidade": 2},
        {"id": 2, "quantidade": 1}
      ]
    }
    ```
- **Atualizar um Pedido**
  - **Método**: `PUT`
  - **URL**: `/api/v1/orders/{id}`
  - **Parâmetros**:
    ```json
    {
      "cliente_id": 1,
      "produtos": [
        { "id": 2, "quantidade": 3 }
      ]
    }
    ```
- **Deletar um Pedido**
  - **Método**: `DELETE`
  - **URL**: `/api/v1/orders/{id}`

#### Email

Ao criar um pedido, um email é enviado automaticamente ao cliente com os detalhes do pedido.

### 8. Testes Unitários

O projeto utiliza o **PHPUnit** para testes unitários. Para rodar os testes, execute o seguinte comando:

```bash
docker exec -it comerc_test_app ./vendor/bin/phpunit
```

Os testes estão localizados na pasta `tests/`.

### 9. Padrão PSR-12

O código está conforme o padrão **PSR-12**. Para verificar se o código segue esse padrão, utilize o **PHP_CodeSniffer**:

```bash
docker exec -it comerc_test_app ./vendor/bin/phpcs --standard=PSR12 app/ routes/ database/
```

### 10. Variáveis de Ambiente

Aqui estão algumas das variáveis do `.env` para configuração da API:

```env
APP_NAME=Comerc_test
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=comerc_test
DB_USERNAME=root
DB_PASSWORD=root

CACHE_DRIVER=file
QUEUE_CONNECTION=sync

MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME="Pastelaria API"

JWT_SECRET=Q+Kt9q0epO4yLSc9MuQ5AYTupOEtn4XFR3hwnaavt2g
```

### 11. Build para Produção

Para fazer o build do projeto para produção, siga os seguintes passos:

1. Certifique-se que todas as dependências estão instaladas e configuradas corretamente.
2. Compile o projeto com o Docker para produção:

```bash
docker-compose -f docker-compose.prod.yml up --build -d
```

### 12. Conclusão

Esse projeto fornece uma API robusta para gerenciar clientes, produtos e pedidos, com funcionalidades completas de autenticação JWT e envio de email.
