-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 31/08/2025 às 15:41
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `ci_pdv`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `c1_categorias`
--

CREATE TABLE `c1_categorias` (
  `c1_id` bigint(11) NOT NULL,
  `c1_categoria` varchar(100) NOT NULL,
  `c1_comissao` decimal(5,2) NOT NULL DEFAULT 0.00,
  `c1_created_at` datetime DEFAULT NULL,
  `c1_updated_at` datetime DEFAULT NULL,
  `c1_deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `c2_clientes`
--

CREATE TABLE `c2_clientes` (
  `c2_id` bigint(11) NOT NULL,
  `c2_nome` varchar(100) NOT NULL,
  `c2_cpf` varchar(14) NOT NULL,
  `c2_rg` varchar(20) DEFAULT NULL,
  `c2_data_nascimento` date NOT NULL,
  `c2_idade` int(11) NOT NULL,
  `c2_cep` varchar(9) NOT NULL,
  `c2_cidade` varchar(100) NOT NULL,
  `c2_uf` char(2) NOT NULL,
  `c2_endereco` varchar(255) NOT NULL,
  `c2_bairro` varchar(100) NOT NULL,
  `c2_complemento` varchar(255) DEFAULT NULL,
  `c2_numero` varchar(10) DEFAULT NULL,
  `c2_ponto_referencia` varchar(255) DEFAULT NULL,
  `c2_telefone` varchar(15) DEFAULT NULL,
  `c2_celular` varchar(15) NOT NULL,
  `c2_email` varchar(100) DEFAULT NULL,
  `c2_situacao` varchar(20) DEFAULT 'Pendente',
  `c2_created_at` datetime DEFAULT NULL,
  `c2_updated_at` datetime DEFAULT NULL,
  `c2_deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `c3_configuracoes`
--

CREATE TABLE `c3_configuracoes` (
  `c3_id` bigint(20) UNSIGNED NOT NULL,
  `c3_nome_app` varchar(255) NOT NULL DEFAULT 'Sistema PDV',
  `c3_versao_app` varchar(20) NOT NULL DEFAULT '1.0.0',
  `c3_nome_empresa` varchar(255) NOT NULL,
  `c3_cnpj_empresa` varchar(18) DEFAULT NULL,
  `c3_email_contato` varchar(255) NOT NULL,
  `c3_telefone_empresa` varchar(15) DEFAULT NULL,
  `c3_site_empresa` varchar(255) DEFAULT NULL,
  `c3_endereco_empresa` text DEFAULT NULL,
  `c3_logo_path` varchar(255) DEFAULT 'logo.png',
  `c3_favicon_path` varchar(255) DEFAULT 'favicon.ico',
  `c3_timezone` varchar(50) NOT NULL DEFAULT 'America/Sao_Paulo',
  `c3_idioma` varchar(10) NOT NULL DEFAULT 'pt-BR',
  `c3_moeda` varchar(10) NOT NULL DEFAULT 'BRL',
  `c3_simbolo_moeda` varchar(5) NOT NULL DEFAULT 'R$',
  `c3_casas_decimais` tinyint(1) NOT NULL DEFAULT 2,
  `c3_separador_decimal` char(1) NOT NULL DEFAULT ',',
  `c3_separador_milhar` char(1) NOT NULL DEFAULT '.',
  `c3_tema` enum('light','dark','auto') NOT NULL DEFAULT 'dark',
  `c3_limite_backup` int(11) NOT NULL DEFAULT 30,
  `c3_email_notificacoes` varchar(255) DEFAULT NULL,
  `c3_smtp_host` varchar(255) DEFAULT NULL,
  `c3_smtp_port` int(5) DEFAULT NULL,
  `c3_smtp_usuario` varchar(255) DEFAULT NULL,
  `c3_smtp_senha` varchar(255) DEFAULT NULL,
  `c3_smtp_criptografia` enum('ssl','tls','none') DEFAULT 'ssl',
  `c3_status_loja` enum('aberta','fechada','manutencao') NOT NULL DEFAULT 'aberta',
  `c3_mensagem_manutencao` text DEFAULT NULL,
  `c3_created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `c3_updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `c3_deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabela de configurações do sistema PDV';

--
-- Despejando dados para a tabela `c3_configuracoes`
--

INSERT INTO `c3_configuracoes` (`c3_id`, `c3_nome_app`, `c3_versao_app`, `c3_nome_empresa`, `c3_cnpj_empresa`, `c3_email_contato`, `c3_telefone_empresa`, `c3_site_empresa`, `c3_endereco_empresa`, `c3_logo_path`, `c3_favicon_path`, `c3_timezone`, `c3_idioma`, `c3_moeda`, `c3_simbolo_moeda`, `c3_casas_decimais`, `c3_separador_decimal`, `c3_separador_milhar`, `c3_tema`, `c3_limite_backup`, `c3_email_notificacoes`, `c3_smtp_host`, `c3_smtp_port`, `c3_smtp_usuario`, `c3_smtp_senha`, `c3_smtp_criptografia`, `c3_status_loja`, `c3_mensagem_manutencao`, `c3_created_at`, `c3_updated_at`, `c3_deleted_at`) VALUES
(1, 'Sistema PDV', '1.0.0', 'Sua Empresa', NULL, 'contato@empresa.com', NULL, NULL, NULL, 'logo.png', 'favicon.ico', 'America/Sao_Paulo', 'pt-BR', 'BRL', 'R$', 2, ',', '.', 'dark', 30, NULL, NULL, NULL, NULL, NULL, 'ssl', 'aberta', NULL, '2025-08-30 19:11:02', '2025-08-30 19:11:02', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `f1_fornecedores`
--

CREATE TABLE `f1_fornecedores` (
  `f1_id` bigint(20) NOT NULL,
  `f1_razao_social` varchar(255) NOT NULL,
  `f1_nome_fantasia` varchar(255) NOT NULL,
  `f1_cnpj` varchar(18) NOT NULL,
  `f1_cep` varchar(9) NOT NULL,
  `f1_cidade` varchar(100) NOT NULL,
  `f1_uf` char(2) NOT NULL,
  `f1_endereco` varchar(255) NOT NULL,
  `f1_bairro` varchar(100) NOT NULL,
  `f1_complemento` varchar(255) DEFAULT NULL,
  `f1_numero` varchar(10) DEFAULT NULL,
  `f1_ponto_referencia` varchar(255) DEFAULT NULL,
  `f1_telefone` varchar(15) DEFAULT NULL,
  `f1_celular` varchar(15) NOT NULL,
  `f1_email` varchar(100) DEFAULT NULL,
  `f1_created_at` datetime DEFAULT NULL,
  `f1_updated_at` datetime DEFAULT NULL,
  `f1_deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `g1_garantias`
--

CREATE TABLE `g1_garantias` (
  `g1_id` bigint(11) NOT NULL,
  `g1_data_garantia` datetime DEFAULT current_timestamp(),
  `g1_nome` varchar(255) NOT NULL,
  `g1_observacao` text DEFAULT NULL,
  `g1_data` date NOT NULL,
  `g1_descricao` longtext NOT NULL,
  `g1_created_at` datetime DEFAULT NULL,
  `g1_updated_at` datetime DEFAULT NULL,
  `g1_deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `l2_logs`
--

CREATE TABLE `l2_logs` (
  `l2_id` bigint(20) UNSIGNED NOT NULL,
  `l2_id_usuario` bigint(20) UNSIGNED NOT NULL,
  `l2_tipo_log` enum('login','logout','caixa_abertura','caixa_fechamento','venda_iniciada','venda_finalizada','venda_cancelada','produto_adicionado','produto_removido','cliente_cadastrado','cliente_editado','produto_cadastrado','produto_editado','fornecedor_cadastrado','fornecedor_editado','categoria_cadastrada','categoria_editada','usuario_cadastrado','usuario_editado','senha_alterada','relatorio_gerado','backup_realizado','erro_sistema','acesso_negado','configuracao_alterada') NOT NULL,
  `l2_acao` varchar(500) NOT NULL,
  `l2_detalhes` text DEFAULT NULL,
  `l2_valor_envolvido` decimal(10,2) DEFAULT NULL,
  `l2_id_referencia` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'ID da venda, produto, cliente, etc relacionado à ação',
  `l2_ip_address` varchar(45) DEFAULT NULL,
  `l2_user_agent` text DEFAULT NULL,
  `l2_status` enum('sucesso','erro','pendente','cancelado') DEFAULT 'sucesso',
  `l2_data_hora` timestamp NOT NULL DEFAULT current_timestamp(),
  `l2_sessao_id` varchar(255) DEFAULT NULL,
  `l2_created_at` datetime DEFAULT NULL,
  `l2_updated_at` datetime DEFAULT NULL,
  `l2_deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabela para registrar logs de atividades do sistema PDV';

-- --------------------------------------------------------

--
-- Estrutura para tabela `p1_produtos`
--

CREATE TABLE `p1_produtos` (
  `p1_id` bigint(20) NOT NULL,
  `p1_imagem_produto` varchar(255) DEFAULT 'produto-sem-imagem.webp',
  `p1_nome_produto` varchar(255) NOT NULL,
  `p1_codigo_produto` varchar(255) NOT NULL,
  `p1_fornecedor_id` bigint(20) NOT NULL,
  `p1_categoria_id` bigint(20) NOT NULL,
  `p1_garantia_id` bigint(20) NOT NULL,
  `p1_quantidade_produto` int(11) NOT NULL,
  `p1_preco_unitario_produto` decimal(10,2) DEFAULT 0.00,
  `p1_preco_compra_produto` decimal(10,2) DEFAULT 0.00,
  `p1_preco_venda_produto` decimal(10,2) DEFAULT 0.00,
  `p1_preco_total_em_produto` decimal(10,2) DEFAULT 0.00,
  `p1_created_at` datetime DEFAULT NULL,
  `p1_updated_at` datetime DEFAULT NULL,
  `p1_deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `p2_produtos_venda`
--

CREATE TABLE `p2_produtos_venda` (
  `p2_id` bigint(20) NOT NULL,
  `p2_venda_id` bigint(20) NOT NULL,
  `p2_produto_id` bigint(20) NOT NULL,
  `p2_quantidade` int(11) NOT NULL,
  `p2_valor_unitario` decimal(10,2) NOT NULL,
  `p2_subtotal` decimal(10,2) NOT NULL,
  `p2_desconto` decimal(10,2) DEFAULT 0.00,
  `p2_valor_com_desconto` decimal(10,2) NOT NULL,
  `p2_created_at` datetime DEFAULT NULL,
  `p2_updated_at` datetime DEFAULT NULL,
  `p2_deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabela de produtos por venda - Relação N para N';

-- --------------------------------------------------------

--
-- Estrutura para tabela `u1_usuarios`
--

CREATE TABLE `u1_usuarios` (
  `u1_id` bigint(20) UNSIGNED NOT NULL,
  `u1_cpf` varchar(14) NOT NULL,
  `u1_nome` varchar(255) NOT NULL,
  `u1_email` varchar(255) NOT NULL,
  `u1_usuario_acesso` varchar(100) NOT NULL,
  `u1_senha_usuario` varchar(255) NOT NULL,
  `u1_tipo_permissao` enum('administrador','cadastro','venda','usuario') DEFAULT 'usuario',
  `u1_data_ultimo_acesso` timestamp NOT NULL DEFAULT current_timestamp(),
  `u1_horario_geracao_token` timestamp NULL DEFAULT NULL,
  `u1_token_reset_senha_acesso` varchar(255) DEFAULT NULL,
  `u1_created_at` datetime DEFAULT NULL,
  `u1_updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `u1_deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `v1_vendas`
--

CREATE TABLE `v1_vendas` (
  `v1_id` bigint(20) NOT NULL,
  `v1_numero_da_venda` int(10) UNSIGNED NOT NULL,
  `v1_cliente_id` bigint(20) NOT NULL,
  `v1_vendedor_nome` varchar(255) NOT NULL,
  `v1_vendedor_id` bigint(20) UNSIGNED NOT NULL,
  `v1_tipo_de_pagamento` enum('dinheiro','cartao_credito','cartao_debito','pix','transferencia','boleto') NOT NULL,
  `v1_desconto` decimal(10,2) DEFAULT 0.00,
  `v1_valor_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `v1_codigo_transacao` varchar(255) DEFAULT NULL,
  `v1_valor_a_ser_pago` decimal(10,2) NOT NULL DEFAULT 0.00,
  `v1_status` enum('Em Aberto','Faturado','Atrasado','Cancelado') NOT NULL DEFAULT 'Em Aberto',
  `v1_created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `v1_data_pagamento` date DEFAULT NULL,
  `v1_data_faturamento` date DEFAULT NULL,
  `v1_observacoes` text DEFAULT NULL,
  `v1_updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `v1_deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabela para registro de vendas do sistema PDV';

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `c1_categorias`
--
ALTER TABLE `c1_categorias`
  ADD PRIMARY KEY (`c1_id`) USING BTREE;

--
-- Índices de tabela `c2_clientes`
--
ALTER TABLE `c2_clientes`
  ADD PRIMARY KEY (`c2_id`) USING BTREE,
  ADD UNIQUE KEY `c2_cpf` (`c2_cpf`) USING BTREE;

--
-- Índices de tabela `c3_configuracoes`
--
ALTER TABLE `c3_configuracoes`
  ADD PRIMARY KEY (`c3_id`) USING BTREE;

--
-- Índices de tabela `f1_fornecedores`
--
ALTER TABLE `f1_fornecedores`
  ADD PRIMARY KEY (`f1_id`) USING BTREE,
  ADD UNIQUE KEY `f1_cnpj` (`f1_cnpj`) USING BTREE;

--
-- Índices de tabela `g1_garantias`
--
ALTER TABLE `g1_garantias`
  ADD PRIMARY KEY (`g1_id`) USING BTREE;

--
-- Índices de tabela `l2_logs`
--
ALTER TABLE `l2_logs`
  ADD PRIMARY KEY (`l2_id`) USING BTREE,
  ADD KEY `idx_l2_usuario` (`l2_id_usuario`) USING BTREE,
  ADD KEY `idx_l2_tipo_log` (`l2_tipo_log`) USING BTREE,
  ADD KEY `idx_l2_data_hora` (`l2_data_hora`) USING BTREE,
  ADD KEY `idx_l2_status` (`l2_status`) USING BTREE,
  ADD KEY `idx_l2_id_referencia` (`l2_id_referencia`) USING BTREE,
  ADD KEY `idx_l2_usuario_data` (`l2_id_usuario`,`l2_data_hora`) USING BTREE,
  ADD KEY `idx_l2_tipo_data` (`l2_tipo_log`,`l2_data_hora`) USING BTREE;

--
-- Índices de tabela `p1_produtos`
--
ALTER TABLE `p1_produtos`
  ADD PRIMARY KEY (`p1_id`) USING BTREE,
  ADD UNIQUE KEY `p1_codigo_produto` (`p1_codigo_produto`) USING BTREE,
  ADD KEY `p1_fornecedor_id` (`p1_fornecedor_id`) USING BTREE,
  ADD KEY `p1_garantia_id` (`p1_garantia_id`) USING BTREE;

--
-- Índices de tabela `p2_produtos_venda`
--
ALTER TABLE `p2_produtos_venda`
  ADD PRIMARY KEY (`p2_id`) USING BTREE,
  ADD KEY `p2_venda_id` (`p2_venda_id`) USING BTREE,
  ADD KEY `p2_produto_id` (`p2_produto_id`) USING BTREE;

--
-- Índices de tabela `u1_usuarios`
--
ALTER TABLE `u1_usuarios`
  ADD PRIMARY KEY (`u1_id`) USING BTREE,
  ADD UNIQUE KEY `u1_cpf` (`u1_cpf`) USING BTREE,
  ADD UNIQUE KEY `u1_usuario_acesso` (`u1_usuario_acesso`) USING BTREE,
  ADD UNIQUE KEY `u1_email` (`u1_email`) USING BTREE;

--
-- Índices de tabela `v1_vendas`
--
ALTER TABLE `v1_vendas`
  ADD PRIMARY KEY (`v1_id`) USING BTREE,
  ADD UNIQUE KEY `v1_numero_da_venda` (`v1_numero_da_venda`) USING BTREE,
  ADD KEY `v1_cliente_id` (`v1_cliente_id`) USING BTREE,
  ADD KEY `idx_v1_vendedor_id` (`v1_vendedor_id`) USING BTREE;

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `c1_categorias`
--
ALTER TABLE `c1_categorias`
  MODIFY `c1_id` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `c2_clientes`
--
ALTER TABLE `c2_clientes`
  MODIFY `c2_id` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=150;

--
-- AUTO_INCREMENT de tabela `c3_configuracoes`
--
ALTER TABLE `c3_configuracoes`
  MODIFY `c3_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `f1_fornecedores`
--
ALTER TABLE `f1_fornecedores`
  MODIFY `f1_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de tabela `g1_garantias`
--
ALTER TABLE `g1_garantias`
  MODIFY `g1_id` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `l2_logs`
--
ALTER TABLE `l2_logs`
  MODIFY `l2_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `p1_produtos`
--
ALTER TABLE `p1_produtos`
  MODIFY `p1_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `p2_produtos_venda`
--
ALTER TABLE `p2_produtos_venda`
  MODIFY `p2_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `u1_usuarios`
--
ALTER TABLE `u1_usuarios`
  MODIFY `u1_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `v1_vendas`
--
ALTER TABLE `v1_vendas`
  MODIFY `v1_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `l2_logs`
--
ALTER TABLE `l2_logs`
  ADD CONSTRAINT `fk_l2_logs_usuario` FOREIGN KEY (`l2_id_usuario`) REFERENCES `u1_usuarios` (`u1_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `p1_produtos`
--
ALTER TABLE `p1_produtos`
  ADD CONSTRAINT `p1_produtos_ibfk_1` FOREIGN KEY (`p1_fornecedor_id`) REFERENCES `f1_fornecedores` (`f1_id`),
  ADD CONSTRAINT `p1_produtos_ibfk_2` FOREIGN KEY (`p1_garantia_id`) REFERENCES `g1_garantias` (`g1_id`) ON UPDATE CASCADE;

--
-- Restrições para tabelas `p2_produtos_venda`
--
ALTER TABLE `p2_produtos_venda`
  ADD CONSTRAINT `fk_p2_produtos_venda_produto` FOREIGN KEY (`p2_produto_id`) REFERENCES `p1_produtos` (`p1_id`),
  ADD CONSTRAINT `fk_p2_produtos_venda_venda` FOREIGN KEY (`p2_venda_id`) REFERENCES `v1_vendas` (`v1_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `v1_vendas`
--
ALTER TABLE `v1_vendas`
  ADD CONSTRAINT `fk_v1_vendas_cliente` FOREIGN KEY (`v1_cliente_id`) REFERENCES `c2_clientes` (`c2_id`),
  ADD CONSTRAINT `fk_v1_vendas_vendedor` FOREIGN KEY (`v1_vendedor_id`) REFERENCES `u1_usuarios` (`u1_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
