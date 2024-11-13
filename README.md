## Simple-blog
Blog simples para uso em aula.

# softwares necessários:

PostrgreSQL, Instale no linux:

```
sudo apt install postgresql
```

Xampp:

Fazer download:

```
sudo apt install apache2
```

Verificar se o serviço está rodando:
```
systemctl status apache2
```

# Instalação:

Instale o postreSQL

Usuário padrão:

> postgres

Defina a senha para:

> root

Crie um banco de dados com o nome 'simple_blog'
```
CREATE DATABASE simple_blog;
```
Estrutura da tabela 'posts' no banco de dados é definido pelo DDL:
```
 CREATE TABLE posts (
    id SERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```
# Webserver

Caso tenha recén instalado o xampp:

Acesse a pasta htdocs e apague os arquivos do diretório

Linux: /opt/lampp/htdocs

Copie o arquivo 'index.php' para a pasta htdocs

# Acesso no navegador

Acesse o "localhost:80" no navegador


