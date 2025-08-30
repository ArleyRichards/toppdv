
# Sistema PDV Simples - CodeIgniter 4

## Descrição

Este projeto é um sistema PDV (Ponto de Venda) simples, desenvolvido com o framework CodeIgniter 4. O objetivo é oferecer uma solução leve e eficiente para gestão de vendas, produtos, clientes, fornecedores e usuários, com recursos essenciais para pequenos comércios.

## Funcionalidades Principais

- Cadastro e gerenciamento de produtos
- Controle de vendas e histórico
- Gestão de clientes e fornecedores
- Controle de garantias
- Categorias de produtos
- Gestão de usuários e permissões
- Registro de logs de operações
- Relatórios básicos

## Estrutura dos Models

- `CategoriaModel`: Gerencia categorias de produtos.
- `ClienteModel`: Cadastro e controle de clientes.
- `FornecedorModel`: Cadastro e controle de fornecedores.
- `GarantiaModel`: Controle de garantias dos produtos.
- `LogModel`: Registro de operações e eventos do sistema.
- `ProdutoModel`: Cadastro e gerenciamento de produtos.
- `ProdutoVendaModel`: Relaciona produtos às vendas realizadas.
- `UsuarioModel`: Gerenciamento de usuários do sistema.
- `VendaModel`: Controle de vendas realizadas.

## Requisitos

- PHP 8.1 ou superior
- Extensões PHP: intl, mbstring, json, mysqlnd, libcurl

## Instalação

1. Clone o repositório ou baixe os arquivos.
2. Instale as dependências com Composer:
	```
	composer install
	```
3. Copie o arquivo `env` para `.env` e configure as variáveis, principalmente o `baseURL` e as credenciais do banco de dados.
4. Importe o banco de dados inicial a partir do arquivo `DB/ci_pdv.sql`.
5. Configure o servidor web para apontar para a pasta `public`.

## Uso

Acesse o sistema via navegador apontando para o endereço configurado no `baseURL`. Realize o login e utilize os módulos disponíveis para gerenciar vendas, produtos, clientes, fornecedores e demais funcionalidades.

## Licença

Este projeto segue a licença MIT.
