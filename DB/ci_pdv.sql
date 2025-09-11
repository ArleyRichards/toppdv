-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 08/09/2025 às 23:03
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

--
-- Despejando dados para a tabela `c1_categorias`
--

INSERT INTO `c1_categorias` (`c1_id`, `c1_categoria`, `c1_comissao`, `c1_created_at`, `c1_updated_at`, `c1_deleted_at`) VALUES
(1, 'Celulares kkk', 5.00, '2025-09-01 08:56:47', '2025-09-02 09:56:23', '2025-09-02 09:56:23'),
(2, 'Informática', 6.00, '2025-09-01 08:56:47', NULL, NULL),
(3, 'Acessórios', 8.00, '2025-09-01 08:56:47', NULL, NULL),
(4, 'Áudio e Multimídia', 7.50, '2025-09-01 08:56:47', NULL, NULL),
(5, 'Periféricos', 6.50, '2025-09-01 08:56:47', NULL, NULL),
(6, 'Teste 1234', 6.00, '2025-09-02 09:48:07', '2025-09-02 09:48:14', '2025-09-02 09:48:14');

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

--
-- Despejando dados para a tabela `c2_clientes`
--

INSERT INTO `c2_clientes` (`c2_id`, `c2_nome`, `c2_cpf`, `c2_rg`, `c2_data_nascimento`, `c2_idade`, `c2_cep`, `c2_cidade`, `c2_uf`, `c2_endereco`, `c2_bairro`, `c2_complemento`, `c2_numero`, `c2_ponto_referencia`, `c2_telefone`, `c2_celular`, `c2_email`, `c2_situacao`, `c2_created_at`, `c2_updated_at`, `c2_deleted_at`) VALUES
(150, 'João Silva', '123.456.789-00', 'MG1234567', '1985-03-15', 40, '30110-010', 'Belo Horizonte', 'MG', 'Rua das Flores', 'Centro', NULL, '100', 'Próx. ao Mercado Central', '(31) 3221-1234', '(31) 98888-1111', 'joao.silva@email.com', 'Ativo', '2025-08-31 14:43:26', '2025-08-31 17:34:10', '2025-08-31 17:34:10'),
(151, 'Maria Oliveira', '987.654.321-00', 'SP7654321', '1990-07-22', 35, '01001-000', 'São Paulo', 'SP', 'Av. Paulista', 'Bela Vista', 'Apto 101', '200', 'Próx. ao MASP', '(11) 3333-2222', '(11) 97777-2222', 'maria.oliveira@email.com', 'Ativo', '2025-08-31 14:43:26', '2025-08-31 14:43:26', NULL),
(152, 'Carlos Souza', '456.789.123-00', 'RJ4567891', '1978-12-05', 46, '22041-001', 'Rio de Janeiro', 'RJ', 'Rua Atlântica', 'Copacabana', NULL, '300', 'Em frente à praia', '(21) 3444-3333', '(21) 96666-3333', 'carlos.souza@email.com', 'Ativo', '2025-08-31 14:43:26', '2025-08-31 14:43:26', NULL),
(153, 'Ana Paula', '321.654.987-00', 'RS3216549', '1995-05-10', 30, '90010-320', 'Porto Alegre', 'RS', 'Av. Independência', 'Centro', 'Bloco B', '400', 'Próx. ao Hospital Moinhos', '(51) 3555-4444', '(51) 95555-4444', 'ana.paula@email.com', 'Ativo', '2025-08-31 14:43:26', '2025-08-31 14:43:26', NULL),
(154, 'Pedro Santos', '789.123.456-00', 'PR7891234', '1982-09-18', 42, '80010-150', 'Curitiba', 'PR', 'Rua XV de Novembro', 'Centro', NULL, '500', 'Próx. ao Teatro Guaíra', '(41) 3666-5555', '(41) 94444-5555', 'pedro.santos@email.com', 'Ativo', '2025-08-31 14:43:26', '2025-08-31 14:43:26', NULL),
(155, 'Juliana Costa', '654.321.987-00', 'BA6543219', '1988-11-30', 36, '40020-160', 'Salvador', 'BA', 'Av. Sete de Setembro', 'Barra', 'Casa 2', '600', 'Próx. ao Farol da Barra', '(71) 3777-6666', '(71) 93333-6666', 'juliana.costa@email.com', 'Ativo', '2025-08-31 14:43:26', '2025-08-31 14:43:26', NULL),
(156, 'Lucas Pereira', '159.753.486-00', 'PE1597534', '1992-02-14', 33, '50010-000', 'Recife', 'PE', 'Rua da Aurora', 'Boa Vista', NULL, '700', 'Próx. ao Parque 13 de Maio', '(81) 3888-7777', '(81) 92222-7777', 'lucas.pereira@email.com', 'Ativo', '2025-08-31 14:43:26', '2025-08-31 14:43:26', NULL),
(157, 'Fernanda Lima', '258.369.147-00', 'CE2583691', '1986-08-25', 39, '60110-000', 'Fortaleza', 'CE', 'Av. Beira Mar', 'Meireles', 'Cobertura', '800', 'Próx. ao Hotel', '(85) 3999-8888', '(85) 91111-8888', 'fernanda.lima@email.com', 'Ativo', '2025-08-31 14:43:26', '2025-08-31 14:43:26', NULL),
(158, 'Rafael Almeida', '369.258.147-00', 'DF3692581', '1980-04-02', 45, '70040-010', 'Brasília', 'DF', 'SQS 308', 'Asa Sul', NULL, '10', 'Próx. ao Supermercado', '(61) 4000-9999', '(61) 90000-9999', 'rafael.almeida@email.com', 'Ativo', '2025-08-31 14:43:26', '2025-08-31 14:43:26', NULL),
(159, 'Patrícia Mendes', '741.852.963-00', 'GO7418529', '1993-06-19', 32, '74000-010', 'Goiânia', 'GO', 'Rua 3', 'Setor Central', 'Sala 5', '1100', 'Próx. ao Shopping', '(62) 4111-0000', '(62) 98888-0000', 'patricia.mendes@email.com', 'Ativo', '2025-08-31 14:43:26', '2025-08-31 14:43:26', NULL),
(160, 'Ana Paula Souza', '123.456.789-01', 'MG123456', '1990-05-12', 35, '30140-071', 'Belo Horizonte', 'MG', 'Rua das Flores', 'Centro', NULL, '100', 'Próx. padaria', '(31) 3322-1100', '(31) 99999-0001', 'ana.souza@email.com', 'Ativo', '2025-08-31 17:37:35', NULL, NULL),
(161, 'Carlos Eduardo Lima', '234.567.890-12', 'SP234567', '1985-08-23', 40, '01001-000', 'São Paulo', 'SP', 'Av. Paulista', 'Bela Vista', 'Apto 101', '2000', 'Próx. metrô', '(11) 3322-2200', '(11) 98888-0002', 'carlos.lima@email.com', 'Inativo', '2025-08-31 17:37:35', NULL, NULL),
(162, 'Fernanda Oliveira', '345.678.901-23', 'RJ345678', '1992-11-30', 32, '22041-001', 'Rio de Janeiro', 'RJ', 'Rua do Catete', 'Catete', NULL, '50', NULL, '(21) 3322-3300', '(21) 97777-0003', 'fernanda.oliveira@email.com', 'Pendente', '2025-08-31 17:37:35', NULL, NULL),
(163, 'João Pedro Silva', '456.789.012-34', 'RS456789', '1988-03-15', 37, '90010-000', 'Porto Alegre', 'RS', 'Av. Independência', 'Centro', NULL, '500', 'Próx. hospital', '(51) 3322-4400', '(51) 96666-0004', 'joao.silva@email.com', 'Ativo', '2025-08-31 17:37:35', NULL, NULL),
(164, 'Mariana Costa', '567.890.123-45', 'BA567890', '1995-07-20', 30, '40010-000', 'Salvador', 'BA', 'Rua da Bahia', 'Barra', NULL, '120', NULL, '(71) 3322-5500', '(71) 95555-0005', 'mariana.costa@email.com', 'Bloqueado', '2025-08-31 17:37:35', NULL, NULL),
(165, 'Pedro Henrique Santos', '678.901.234-56', 'PE678901', '1983-12-05', 41, '50010-000', 'Recife', 'PE', 'Av. Recife', 'Boa Viagem', NULL, '300', 'Próx. shopping', '(81) 3322-6600', '(81) 94444-0006', 'pedro.santos@email.com', 'Ativo', '2025-08-31 17:37:35', NULL, NULL),
(166, 'Juliana Martins', '789.012.345-67', 'PR789012', '1998-02-28', 27, '80010-000', 'Curitiba', 'PR', 'Rua XV de Novembro', 'Centro', NULL, '80', NULL, '(41) 3322-7700', '(41) 93333-0007', 'juliana.martins@email.com', 'Inativo', '2025-08-31 17:37:35', NULL, NULL),
(167, 'Lucas Almeida', '890.123.456-78', 'SC890123', '1991-09-10', 33, '88010-000', 'Florianópolis', 'SC', 'Av. Beira Mar', 'Centro', NULL, '150', 'Próx. mercado', '(48) 3322-8800', '(48) 92222-0008', 'lucas.almeida@email.com', 'Ativo', '2025-08-31 17:37:35', NULL, NULL),
(168, 'Patrícia Ramos', '901.234.567-89', 'GO901234', '1986-06-18', 39, '74010-000', 'Goiânia', 'GO', 'Rua Goiás', 'Setor Central', NULL, '60', NULL, '(62) 3322-9900', '(62) 91111-0009', 'patricia.ramos@email.com', 'Pendente', '2025-08-31 17:37:35', NULL, NULL),
(169, 'Rafael Borges', '012.345.678-90', 'DF012345', '1993-04-25', 32, '70040-000', 'Brasília', 'DF', 'SQS 308', 'Asa Sul', NULL, '308', 'Próx. escola', '(61) 3323-0000', '(61) 90000-0010', 'rafael.borges@email.com', 'Ativo', '2025-08-31 17:37:35', NULL, NULL),
(170, 'Sofia Mendes', '112.233.445-56', 'ES112233', '1997-10-02', 27, '29010-000', 'Vitória', 'ES', 'Rua Sete', 'Praia do Canto', NULL, '77', NULL, '(27) 3323-1100', '(27) 98888-0011', 'sofia.mendes@email.com', 'Inativo', '2025-08-31 17:37:35', NULL, NULL),
(171, 'Gabriel Ferreira', '223.344.556-67', 'AM223344', '1989-01-19', 36, '69010-000', 'Manaus', 'AM', 'Av. Amazonas', 'Centro', NULL, '200', NULL, '(92) 3323-2200', '(92) 97777-0012', 'gabriel.ferreira@email.com', 'Ativo', '2025-08-31 17:37:35', NULL, NULL),
(172, 'Beatriz Rocha', '334.455.667-78', 'PA334455', '1994-12-11', 30, '66010-000', 'Belém', 'PA', 'Rua dos Caripunas', 'Nazaré', NULL, '45', NULL, '(91) 3323-3300', '(91) 96666-0013', 'beatriz.rocha@email.com', 'Pendente', '2025-08-31 17:37:35', NULL, NULL),
(173, 'Rodrigo Lima', '445.566.778-89', 'CE445566', '1987-07-07', 38, '60010-000', 'Fortaleza', 'CE', 'Av. Domingos Olímpio', 'Centro', NULL, '500', NULL, '(85) 3323-4400', '(85) 95555-0014', 'rodrigo.lima@email.com', 'Bloqueado', '2025-08-31 17:37:35', NULL, NULL),
(174, 'Camila Pires', '556.677.889-90', 'MT556677', '1996-03-22', 29, '78010-000', 'Cuiabá', 'MT', 'Rua das Palmeiras', 'Centro', NULL, '33', NULL, '(65) 3323-5500', '(65) 94444-0015', 'camila.pires@email.com', 'Ativo', '2025-08-31 17:37:35', NULL, NULL),
(175, 'Nicollas Carmoriz Bonimo', '39168671806', '11847652X', '2019-06-29', 6, '08766025', 'Mogi das Cruzes', 'SP', 'Rua Quatro', 'Conjunto Bom Pastor', '', '100', 'teste', '1998138531', '19981385316', 'nicollas.bonimo@geradornv.com.br', 'Pendente', '2025-08-31 17:48:02', '2025-08-31 17:48:02', NULL);

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
(1, 'Nome do Sistema 1234', '1.0.0', 'Sua Empresa', '', 'contato@empresa.com', '', '', '', 'logo.png', 'favicon.ico', 'America/Sao_Paulo', 'pt-BR', 'BRL', 'R$', 2, ',', '.', 'dark', 30, '', '', 0, '', '', 'ssl', 'aberta', '', '2025-08-30 19:11:02', '2025-09-04 11:41:49', NULL);

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

--
-- Despejando dados para a tabela `f1_fornecedores`
--

INSERT INTO `f1_fornecedores` (`f1_id`, `f1_razao_social`, `f1_nome_fantasia`, `f1_cnpj`, `f1_cep`, `f1_cidade`, `f1_uf`, `f1_endereco`, `f1_bairro`, `f1_complemento`, `f1_numero`, `f1_ponto_referencia`, `f1_telefone`, `f1_celular`, `f1_email`, `f1_created_at`, `f1_updated_at`, `f1_deleted_at`) VALUES
(1, 'Tech Distribuição Ltda', 'Tech', '12.345.678/0001-90', '01001000', 'São Paulo', 'SP', 'Av. Paulista, 1000', 'Bela Vista', 'Próximo ao metrô', '1000', 'Entrada lateral', '1131000000', '11999990001', 'contato@techdistrib.com.br', '2025-09-01 09:06:32', '2025-09-03 16:56:58', NULL),
(2, 'Global Eletrônicos S/A', 'GlobalEletron', '23.456.789/0001-81', '29010000', 'Vitória', 'ES', 'Rua do Comércio, 250', 'Centro', '', '250', 'Próximo ao fórum', '2732222222', '27999990002', 'vendas@globaleletron.com', '2025-09-01 09:06:32', '2025-09-03 16:57:01', NULL),
(3, 'Inova Components ME', 'InovaComp', '34.567.890/0001-70', '70040-010', 'Brasília', 'DF', 'Setor Comercial Norte, Bloco A, 45', 'Asa Norte', 'Sala 12', '45', 'Em frente ao estacionamento', '(61) 3300-3300', '(61) 99999-0003', 'comercial@inovacomp.com.br', '2025-09-01 09:06:32', NULL, NULL),
(4, 'Accs Solutions Ltda', 'AccsSol', '45.678.901/0001-60', '30110-012', 'Belo Horizonte', 'MG', 'Av. Augusto de Lima, 500', 'Centro', NULL, '500', 'Próximo à praça', '(31) 3333-3333', '(31) 99999-0004', 'suporte@accssol.com.br', '2025-09-01 09:06:32', NULL, NULL),
(5, 'MobileWare Comércio', 'MobileWare', '56.789.012/0001-50', '40020-000', 'Salvador', 'BA', 'Rua das Flores, 120', 'Barra', 'Loja 2', '120', 'Ao lado do café', '(71) 3456-3456', '(71) 99999-0005', 'contato@mobileware.com.br', '2025-09-01 09:06:32', NULL, NULL),
(6, 'NetPeriféricos Ltda', 'NetPerif', '67.890.123/0001-40', '80010-000', 'Curitiba', 'PR', 'Av. Batel, 2000', 'Batel', NULL, '2000', 'Próximo ao shopping', '(41) 4000-4000', '(41) 99999-0006', 'vendas@netperif.com.br', '2025-09-01 09:06:32', NULL, NULL),
(7, 'StorageTech Comércio', 'StorageTech', '78.901.234/0001-30', '30120-020', 'Belo Horizonte', 'MG', 'Rua dos Inconfidentes, 78', 'Savassi', NULL, '78', 'Próximo à faculdade', '(31) 3555-5555', '(31) 99999-0007', 'financeiro@storagetech.com.br', '2025-09-01 09:06:32', NULL, NULL),
(8, 'AudioMax Importadora', 'AudioMax', '89.012.345/0001-20', '90420-080', 'Porto Alegre', 'RS', 'Av. Ipiranga, 1500', 'Centro', 'Andar 3', '1500', 'Em frente ao teatro', '(51) 3333-4444', '(51) 99999-0008', 'contato@audiomax.com.br', '2025-09-01 09:06:32', NULL, NULL),
(20, 'Razão Teste ', 'Nome da espresa', '12345645698798', '68420000', 'Mocajuba', 'PA', 'Rua João Procópio', 'Cidade Nova', 'Teste 23', '500', 'Codica', '9819819819', '98498498198', 'razao@gmail.com', '2025-09-03 16:21:17', '2025-09-03 16:43:30', '2025-09-03 16:43:30');

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

--
-- Despejando dados para a tabela `g1_garantias`
--

INSERT INTO `g1_garantias` (`g1_id`, `g1_data_garantia`, `g1_nome`, `g1_observacao`, `g1_data`, `g1_descricao`, `g1_created_at`, `g1_updated_at`, `g1_deleted_at`) VALUES
(1, '2025-09-01 08:56:00', 'Garantia Padrão 1 ano 1234', 'Cobertura contra defeitos de fabricação', '2026-09-01', 'Garantia oficial do fabricante por 12 meses a partir da compra.', '2025-09-01 08:56:59', '2025-09-03 15:19:37', NULL),
(2, '2025-09-01 08:56:00', 'Garantia Estendida 2 anos klllllllll', 'Extensão opcional de garantia', '2027-09-01', 'Garantia estendida que cobre componentes e reparos por 24 meses.', '2025-09-01 08:56:59', '2025-09-03 15:19:45', NULL),
(3, '2025-09-01 08:56:59', 'Garantia Premium (Troca Rápida)', 'Troca rápida do equipamento', '2026-12-31', 'Serviço premium com troca por novo dentro do período contratado.', '2025-09-01 08:56:59', NULL, NULL),
(4, '2025-09-01 08:56:59', 'Garantia Acessórios 6 meses', 'Cobertura limitada para acessórios', '2026-03-01', 'Garantia para cabos, capas e periféricos por 6 meses.', '2025-09-01 08:56:59', NULL, NULL),
(5, '2025-09-01 08:56:59', 'Garantia Comercial 3 meses', 'Garantia curta para estoque/mostruário', '2026-01-01', 'Garantia comercial curta para produtos promocionais ou de demonstração.', '2025-09-01 08:56:59', NULL, NULL),
(6, '2025-09-03 12:45:00', 'teste123', 'teste ', '2025-09-03', 'twwerwer werwerwer', '2025-09-03 12:45:42', '2025-09-03 15:20:29', '2025-09-03 15:20:29');

-- --------------------------------------------------------

--
-- Estrutura para tabela `l2_licencas`
--

CREATE TABLE `l2_licencas` (
  `l2_id` bigint(20) UNSIGNED NOT NULL,
  `l2_user_id` bigint(20) UNSIGNED NOT NULL,
  `l2_data_ativacao_sistema` datetime DEFAULT NULL,
  `l2_data_ultima_renovacao` datetime DEFAULT NULL,
  `l2_data_proxima_renovacao` datetime DEFAULT NULL,
  `l2_chave_pix` varchar(255) DEFAULT NULL,
  `l2_created_at` datetime DEFAULT NULL,
  `l2_updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `l2_deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `l2_licencas`
--

INSERT INTO `l2_licencas` (`l2_id`, `l2_user_id`, `l2_data_ativacao_sistema`, `l2_data_ultima_renovacao`, `l2_data_proxima_renovacao`, `l2_chave_pix`, `l2_created_at`, `l2_updated_at`, `l2_deleted_at`) VALUES
(1, 1, '2024-09-01 10:00:00', '2025-08-30 12:00:00', '2026-08-30 12:00:00', 'PIX:00011122233', '2025-09-04 17:06:11', NULL, NULL),
(2, 2, '2025-01-15 09:30:00', '2025-07-01 15:00:00', '2026-07-01 15:00:00', 'PIX:99988877766', '2025-09-04 17:06:11', NULL, NULL);

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

--
-- Despejando dados para a tabela `l2_logs`
--

INSERT INTO `l2_logs` (`l2_id`, `l2_id_usuario`, `l2_tipo_log`, `l2_acao`, `l2_detalhes`, `l2_valor_envolvido`, `l2_id_referencia`, `l2_ip_address`, `l2_user_agent`, `l2_status`, `l2_data_hora`, `l2_sessao_id`, `l2_created_at`, `l2_updated_at`, `l2_deleted_at`) VALUES
(4, 7, 'login', 'Login realizado com sucesso', 'Usuário: admin', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:143.0) Gecko/20100101 Firefox/143.0', 'sucesso', '2025-09-03 14:24:44', '507b8f0c293468c92afc0120471d8d0c', '2025-09-03 11:24:44', '2025-09-03 11:24:44', NULL),
(5, 7, 'login', 'Login realizado com sucesso', 'Usuário: admin', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:143.0) Gecko/20100101 Firefox/143.0', 'sucesso', '2025-09-03 14:25:29', 'dcff209db0cb34b212ca0e486a213c47', '2025-09-03 11:25:29', '2025-09-03 11:25:29', NULL),
(6, 7, 'login', 'Login realizado com sucesso', 'Usuário: admin', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:143.0) Gecko/20100101 Firefox/143.0', 'sucesso', '2025-09-03 14:25:44', 'dcff209db0cb34b212ca0e486a213c47', '2025-09-03 11:25:44', '2025-09-03 11:25:44', NULL),
(7, 7, 'logout', 'Logout realizado', 'Usuário: admin', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:143.0) Gecko/20100101 Firefox/143.0', 'sucesso', '2025-09-03 14:25:47', 'dcff209db0cb34b212ca0e486a213c47', '2025-09-03 11:25:47', '2025-09-03 11:25:47', NULL),
(8, 7, 'login', 'Login realizado com sucesso', 'Usuário: admin', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:143.0) Gecko/20100101 Firefox/143.0', 'sucesso', '2025-09-03 14:25:50', '6e4108f4730d05141e5d49ba3aed46d1', '2025-09-03 11:25:50', '2025-09-03 11:25:50', NULL),
(9, 7, 'login', 'Login realizado com sucesso', 'Usuário: admin', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:143.0) Gecko/20100101 Firefox/143.0', 'sucesso', '2025-09-04 11:23:43', '4289974098b8ffc4c5a1fa72ac6b666d', '2025-09-04 08:23:43', '2025-09-04 08:23:43', NULL),
(10, 7, 'logout', 'Logout realizado', 'Usuário: admin', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:143.0) Gecko/20100101 Firefox/143.0', 'sucesso', '2025-09-04 11:47:12', '4d7f0e71203de77eaa73676a2ac236da', '2025-09-04 08:47:12', '2025-09-04 08:47:12', NULL),
(11, 7, 'login', 'Login realizado com sucesso', 'Usuário: admin', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:143.0) Gecko/20100101 Firefox/143.0', 'sucesso', '2025-09-04 11:48:40', 'a513c695b6ce29a63fb1c13fe2ab2f1b', '2025-09-04 08:48:40', '2025-09-04 08:48:40', NULL),
(12, 7, 'login', 'Login realizado com sucesso', 'Usuário: admin', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:143.0) Gecko/20100101 Firefox/143.0', 'sucesso', '2025-09-04 20:20:03', 'c14c71b374d6ecc9fdd29e38f3ad91bc', '2025-09-04 17:20:03', '2025-09-04 17:20:03', NULL),
(13, 7, 'login', 'Login realizado com sucesso', 'Usuário: admin', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:142.0) Gecko/20100101 Firefox/142.0', 'sucesso', '2025-09-05 10:30:14', '8454923dac465d8550b0c6b4ed13d5bb', '2025-09-05 07:30:14', '2025-09-05 07:30:14', NULL),
(14, 7, 'login', 'Login realizado com sucesso', 'Usuário: admin', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:143.0) Gecko/20100101 Firefox/143.0', 'sucesso', '2025-09-05 22:06:46', 'a73d0c57f9e31c2d38cfd65ab9e08b9a', '2025-09-05 19:06:46', '2025-09-05 19:06:46', NULL),
(15, 7, 'login', 'Login realizado com sucesso', 'Usuário: admin', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:143.0) Gecko/20100101 Firefox/143.0', 'sucesso', '2025-09-06 13:46:17', 'd635816dad60700e55b9a98a04d4d9a9', '2025-09-06 10:46:17', '2025-09-06 10:46:17', NULL),
(16, 7, 'login', 'Login realizado com sucesso', 'Usuário: admin', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:143.0) Gecko/20100101 Firefox/143.0', 'sucesso', '2025-09-07 10:24:16', 'a0159b1e767d32591ed971b2595d6748', '2025-09-07 07:24:16', '2025-09-07 07:24:16', NULL),
(17, 7, 'login', 'Login realizado com sucesso', 'Usuário: admin', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:143.0) Gecko/20100101 Firefox/143.0', 'sucesso', '2025-09-07 17:36:58', 'bc5e466641e3e7eefcd89200dd60938f', '2025-09-07 14:36:58', '2025-09-07 14:36:58', NULL),
(18, 7, 'login', 'Login realizado com sucesso', 'Usuário: admin', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:143.0) Gecko/20100101 Firefox/143.0', 'sucesso', '2025-09-08 11:26:20', '4b45f75ac1db9eeebb749a0cc253119d', '2025-09-08 08:26:20', '2025-09-08 08:26:20', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `o1_ordens`
--

CREATE TABLE `o1_ordens` (
  `o1_id` bigint(20) NOT NULL,
  `o1_numero_ordem` varchar(20) NOT NULL,
  `o1_cliente_id` bigint(20) NOT NULL,
  `o1_equipamento` varchar(255) NOT NULL,
  `o1_marca` varchar(100) DEFAULT NULL,
  `o1_modelo` varchar(100) DEFAULT NULL,
  `o1_numero_serie` varchar(100) DEFAULT NULL,
  `o1_defeito_relatado` text NOT NULL,
  `o1_observacoes_entrada` text DEFAULT NULL,
  `o1_acessorios_entrada` text DEFAULT NULL,
  `o1_estado_aparente` enum('Bom','Regular','Ruim') DEFAULT 'Bom',
  `o1_tecnico_id` bigint(20) UNSIGNED NOT NULL,
  `o1_status` enum('Aguardando','Em Andamento','Aguardando Peças','Concluído','Entregue','Cancelado') DEFAULT 'Aguardando',
  `o1_prioridade` enum('Baixa','Média','Alta','Urgente') DEFAULT 'Média',
  `o1_data_entrada` datetime NOT NULL,
  `o1_data_previsao` date DEFAULT NULL,
  `o1_data_conclusao` datetime DEFAULT NULL,
  `o1_data_entrega` datetime DEFAULT NULL,
  `o1_valor_servicos` decimal(10,2) DEFAULT 0.00,
  `o1_valor_produtos` decimal(10,2) DEFAULT 0.00,
  `o1_valor_total` decimal(10,2) DEFAULT 0.00,
  `o1_desconto` decimal(10,2) DEFAULT 0.00,
  `o1_valor_final` decimal(10,2) DEFAULT 0.00,
  `o1_laudo_tecnico` text DEFAULT NULL,
  `o1_observacoes_conclusao` text DEFAULT NULL,
  `o1_garantia_servico` int(11) DEFAULT 0 COMMENT 'Dias de garantia do serviço',
  `o1_created_at` datetime DEFAULT NULL,
  `o1_updated_at` datetime DEFAULT NULL,
  `o1_deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `o1_ordens`
--

INSERT INTO `o1_ordens` (`o1_id`, `o1_numero_ordem`, `o1_cliente_id`, `o1_equipamento`, `o1_marca`, `o1_modelo`, `o1_numero_serie`, `o1_defeito_relatado`, `o1_observacoes_entrada`, `o1_acessorios_entrada`, `o1_estado_aparente`, `o1_tecnico_id`, `o1_status`, `o1_prioridade`, `o1_data_entrada`, `o1_data_previsao`, `o1_data_conclusao`, `o1_data_entrega`, `o1_valor_servicos`, `o1_valor_produtos`, `o1_valor_total`, `o1_desconto`, `o1_valor_final`, `o1_laudo_tecnico`, `o1_observacoes_conclusao`, `o1_garantia_servico`, `o1_created_at`, `o1_updated_at`, `o1_deleted_at`) VALUES
(1, 'OS000001', 172, 'Notebook ', 'Dell', 'IInspiron', '123456789', 'Notebook apresenta aquecimento ao ligar', 'aparentemente produto sem avarias', 'teste tes tewerwerwer', '', 1, 'Aguardando', 'Média', '2025-09-06 00:00:00', '2025-09-06', NULL, NULL, 25.02, 1499.00, 1524.02, 0.00, 1524.02, NULL, NULL, 0, '2025-09-06 12:31:20', '2025-09-06 17:22:16', NULL),
(2, 'OS000002', 161, 'Notebook', 'Positivo', 'Gray', '123456789', 'Notebook esquenta muito', 'aparentemente nunhuma avaria', '32132132132132132132132132321321321321321321321321321321321321321322313213213', '', 1, 'Aguardando', 'Média', '2025-09-06 00:00:00', '2025-09-06', NULL, NULL, 60.00, 109.90, 169.90, 0.00, 169.90, NULL, NULL, 0, '2025-09-06 16:58:45', '2025-09-07 07:33:35', '2025-09-07 07:33:35');

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

--
-- Despejando dados para a tabela `p1_produtos`
--

INSERT INTO `p1_produtos` (`p1_id`, `p1_imagem_produto`, `p1_nome_produto`, `p1_codigo_produto`, `p1_fornecedor_id`, `p1_categoria_id`, `p1_garantia_id`, `p1_quantidade_produto`, `p1_preco_unitario_produto`, `p1_preco_compra_produto`, `p1_preco_venda_produto`, `p1_preco_total_em_produto`, `p1_created_at`, `p1_updated_at`, `p1_deleted_at`) VALUES
(6, '1756813654_892cf61b69dca7871e8b.webp', 'Smartphone X100 128GB Preto', '1110000000001', 1, 1, 1, 20, 125000.00, 95.00, 129.90, 2500000.00, '2025-09-01 08:54:54', '2025-09-02 08:47:34', NULL),
(7, '1756813884_a787c0f7755873aceadc.jpg', 'Smartphone X100 256GB Azul', '1000000000002', 1, 1, 1, 12, 1450.00, 1100.00, 1499.00, 17400.00, '2025-09-01 08:54:54', '2025-09-02 08:51:24', NULL),
(8, NULL, 'Carregador Turbo 20W USB-C', '1000000000003', 2, 3, 1, 50, 45.00, 30.00, 49.90, 2495.00, '2025-09-01 08:54:54', NULL, NULL),
(9, NULL, 'Cabo USB-C Nylon 1m', '1000000000004', 2, 3, 1, 80, 15.00, 6.00, 19.90, 1592.00, '2025-09-01 08:54:54', NULL, NULL),
(10, NULL, 'Fone Bluetooth True Wireless', '1000000000005', 3, 3, 2, 35, 180.00, 120.00, 199.90, 6996.50, '2025-09-01 08:54:54', NULL, NULL),
(11, NULL, 'Capinha Silicone Samsung S21', '1000000000006', 4, 3, 1, 100, 25.00, 10.00, 29.90, 2990.00, '2025-09-01 08:54:54', NULL, NULL),
(12, NULL, 'Película de Vidro Temperado', '1000000000007', 4, 3, 1, 150, 12.00, 3.50, 14.90, 2235.00, '2025-09-01 08:54:54', NULL, NULL),
(13, NULL, 'Power Bank 10000mAh', '1000000000008', 3, 3, 2, 40, 85.00, 60.00, 99.90, 3996.00, '2025-09-01 08:54:54', NULL, NULL),
(14, NULL, 'Cartão SD 128GB', '1000000000009', 2, 2, 1, 60, 55.00, 35.00, 59.90, 3594.00, '2025-09-01 08:54:54', NULL, NULL),
(15, NULL, 'Adaptador HDMI para USB-C', '1000000000010', 5, 2, 1, 30, 65.00, 40.00, 74.90, 2247.00, '2025-09-01 08:54:54', NULL, NULL),
(16, NULL, 'Suporte Veicular Magnético', '1000000000011', 4, 3, 1, 45, 39.00, 18.00, 44.90, 2020.50, '2025-09-01 08:54:54', NULL, NULL),
(17, NULL, 'Teclado Mecânico Compacto', '1000000000012', 3, 2, 2, 18, 320.00, 220.00, 349.90, 6298.20, '2025-09-01 08:54:54', NULL, NULL),
(18, NULL, 'Mouse Gamer Óptico', '1000000000013', 3, 2, 2, 25, 120.00, 80.00, 139.90, 3497.50, '2025-09-01 08:54:54', NULL, NULL),
(19, NULL, 'Webcam Full HD 30fps', '1000000000014', 5, 2, 1, 22, 150.00, 95.00, 169.90, 3737.80, '2025-09-01 08:54:54', NULL, NULL),
(20, NULL, 'Headset Gamer com Microfone', '1000000000015', 3, 2, 2, 16, 220.00, 150.00, 249.90, 3998.40, '2025-09-01 08:54:54', NULL, NULL),
(21, NULL, 'Base Refrigerada para Notebook', '1000000000016', 4, 2, 1, 20, 98.00, 60.00, 109.90, 2198.00, '2025-09-01 08:54:54', NULL, NULL),
(22, NULL, 'SSD NVMe 500GB', '1000000000017', 2, 2, 2, 14, 420.00, 300.00, 449.90, 6298.60, '2025-09-01 08:54:54', NULL, NULL),
(23, NULL, 'HD Externo 1TB', '1000000000018', 2, 2, 2, 10, 380.00, 260.00, 419.90, 4199.00, '2025-09-01 08:54:54', NULL, NULL),
(24, NULL, 'Roteador Wi‑Fi AC1200', '1000000000019', 5, 2, 1, 12, 210.00, 145.00, 229.90, 2758.80, '2025-09-01 08:54:54', NULL, NULL),
(25, NULL, 'Cartucho Impressora (preto)', '1000000000020', 4, 3, 1, 40, 55.00, 28.00, 64.90, 2596.00, '2025-09-01 08:54:54', NULL, NULL),
(26, NULL, 'Microfone USB Condensador', '1000000000021', 3, 2, 2, 15, 170.00, 95.00, 189.90, 2848.50, '2025-09-01 08:54:54', NULL, NULL),
(27, NULL, 'Cabo Lightning 1m', '1000000000022', 1, 3, 1, 70, 29.00, 10.00, 34.90, 2443.00, '2025-09-01 08:54:54', NULL, NULL),
(28, NULL, 'Carregador Veicular QC3.0', '1000000000023', 2, 3, 1, 38, 59.00, 32.00, 69.90, 2656.20, '2025-09-01 08:54:54', NULL, NULL),
(29, NULL, 'Case para AirPods (silicone)', '1000000000024', 4, 3, 1, 55, 22.00, 8.00, 24.90, 1369.50, '2025-09-01 08:54:54', NULL, NULL),
(30, NULL, 'Filtro de Linha 4 Tomadas', '1000000000025', 5, 3, 1, 26, 48.00, 20.00, 54.90, 1427.40, '2025-09-01 08:54:54', '2025-09-02 08:47:00', '2025-09-02 08:47:00'),
(31, '1756758624_b8872d52aecb90c883ec.webp', 'Fone de Ouvido', '010203040506', 7, 4, 5, 5, 50.00, 50.00, 185.00, 250.00, '2025-09-01 17:30:23', '2025-09-02 08:44:26', '2025-09-02 08:44:26');

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
-- Estrutura para tabela `p3_produtos_ordem`
--

CREATE TABLE `p3_produtos_ordem` (
  `p3_id` bigint(20) NOT NULL,
  `p3_ordem_id` bigint(20) NOT NULL,
  `p3_produto_id` bigint(20) NOT NULL,
  `p3_quantidade` int(11) NOT NULL,
  `p3_valor_unitario` decimal(10,2) NOT NULL,
  `p3_valor_total` decimal(10,2) NOT NULL,
  `p3_observacoes` text DEFAULT NULL,
  `p3_created_at` datetime DEFAULT NULL,
  `p3_updated_at` datetime DEFAULT NULL,
  `p3_deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `p3_produtos_ordem`
--

INSERT INTO `p3_produtos_ordem` (`p3_id`, `p3_ordem_id`, `p3_produto_id`, `p3_quantidade`, `p3_valor_unitario`, `p3_valor_total`, `p3_observacoes`, `p3_created_at`, `p3_updated_at`, `p3_deleted_at`) VALUES
(1, 1, 14, 1, 59.90, 59.90, NULL, '2025-09-06 15:37:00', '2025-09-06 16:05:38', '2025-09-06 16:05:38'),
(2, 1, 7, 1, 1499.00, 1499.00, NULL, '2025-09-06 16:05:38', '2025-09-06 16:05:38', NULL),
(3, 2, 21, 1, 109.90, 109.90, NULL, '2025-09-06 16:59:14', '2025-09-06 17:22:19', '2025-09-06 17:22:19'),
(4, 2, 21, 1, 109.90, 109.90, NULL, '2025-09-06 17:22:19', '2025-09-07 07:33:35', '2025-09-07 07:33:35');

-- --------------------------------------------------------

--
-- Estrutura para tabela `s1_servicos`
--

CREATE TABLE `s1_servicos` (
  `s1_id` bigint(20) NOT NULL,
  `s1_codigo_servico` varchar(50) NOT NULL,
  `s1_nome_servico` varchar(255) NOT NULL,
  `s1_descricao` text DEFAULT NULL,
  `s1_valor` decimal(10,2) NOT NULL DEFAULT 0.00,
  `s1_tempo_medio` int(11) DEFAULT NULL COMMENT 'Tempo médio em minutos',
  `s1_categoria` varchar(100) DEFAULT NULL,
  `s1_garantia` int(11) DEFAULT 0 COMMENT 'Dias de garantia padrão',
  `s1_status` enum('Ativo','Inativo') DEFAULT 'Ativo',
  `s1_created_at` datetime DEFAULT NULL,
  `s1_updated_at` datetime DEFAULT NULL,
  `s1_deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `s1_servicos`
--

INSERT INTO `s1_servicos` (`s1_id`, `s1_codigo_servico`, `s1_nome_servico`, `s1_descricao`, `s1_valor`, `s1_tempo_medio`, `s1_categoria`, `s1_garantia`, `s1_status`, `s1_created_at`, `s1_updated_at`, `s1_deleted_at`) VALUES
(1, 'SVC001', 'Troca de HD', 'teste de update', 25.02, 15, 'Informática', 7, 'Ativo', '2025-09-05 09:12:17', '2025-09-05 09:37:55', NULL),
(2, 'SVC002', 'Formatação de HD', 'Instalação de Windows 10', 60.00, 90, 'Informática', 15, 'Ativo', '2025-09-05 09:25:27', '2025-09-05 09:38:04', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `s2_servicos_ordem`
--

CREATE TABLE `s2_servicos_ordem` (
  `s2_id` bigint(20) NOT NULL,
  `s2_ordem_id` bigint(20) NOT NULL,
  `s2_servico_id` bigint(20) NOT NULL,
  `s2_quantidade` int(11) NOT NULL DEFAULT 1,
  `s2_valor_unitario` decimal(10,2) NOT NULL,
  `s2_valor_total` decimal(10,2) NOT NULL,
  `s2_observacoes` text DEFAULT NULL,
  `s2_status` enum('Pendente','Executando','Concluído','Cancelado') DEFAULT 'Pendente',
  `s2_tecnico_id` bigint(20) UNSIGNED DEFAULT NULL,
  `s2_data_inicio` datetime DEFAULT NULL,
  `s2_data_conclusao` datetime DEFAULT NULL,
  `s2_created_at` datetime DEFAULT NULL,
  `s2_updated_at` datetime DEFAULT NULL,
  `s2_deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `s2_servicos_ordem`
--

INSERT INTO `s2_servicos_ordem` (`s2_id`, `s2_ordem_id`, `s2_servico_id`, `s2_quantidade`, `s2_valor_unitario`, `s2_valor_total`, `s2_observacoes`, `s2_status`, `s2_tecnico_id`, `s2_data_inicio`, `s2_data_conclusao`, `s2_created_at`, `s2_updated_at`, `s2_deleted_at`) VALUES
(1, 1, 1, 1, 25.02, 25.02, NULL, 'Pendente', NULL, NULL, NULL, '2025-09-06 15:37:00', '2025-09-06 16:05:38', '2025-09-06 16:05:38'),
(2, 1, 1, 1, 25.02, 25.02, NULL, 'Pendente', NULL, NULL, NULL, '2025-09-06 16:05:38', '2025-09-06 16:05:38', NULL),
(3, 2, 2, 1, 60.00, 60.00, NULL, 'Pendente', NULL, NULL, NULL, '2025-09-06 16:59:14', '2025-09-06 17:22:19', '2025-09-06 17:22:19'),
(4, 2, 2, 1, 60.00, 60.00, NULL, 'Pendente', NULL, NULL, NULL, '2025-09-06 17:22:19', '2025-09-07 07:33:35', '2025-09-07 07:33:35');

-- --------------------------------------------------------

--
-- Estrutura para tabela `t1_tecnicos`
--

CREATE TABLE `t1_tecnicos` (
  `t1_id` bigint(20) NOT NULL,
  `t1_nome` varchar(255) NOT NULL,
  `t1_cpf` varchar(14) DEFAULT NULL,
  `t1_telefone` varchar(15) DEFAULT NULL,
  `t1_email` varchar(255) DEFAULT NULL,
  `t1_observacao` text DEFAULT NULL,
  `t1_created_at` datetime DEFAULT NULL,
  `t1_updated_at` datetime DEFAULT NULL,
  `t1_deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `t1_tecnicos`
--

INSERT INTO `t1_tecnicos` (`t1_id`, `t1_nome`, `t1_cpf`, `t1_telefone`, `t1_email`, `t1_observacao`, `t1_created_at`, `t1_updated_at`, `t1_deleted_at`) VALUES
(1, 'João Técnico', '111.111.111-11', '(31) 99999-1111', 'joao.tecnico@empresa.com', 'Técnico generalista', '2025-09-05 19:13:19', '2025-09-05 19:13:19', NULL),
(2, 'Maria Técnica', '222.222.222-22', '(11) 98888-2222', 'maria.tecnica@empresa.com', 'Especialista em celulares', '2025-09-05 19:13:19', '2025-09-05 19:13:19', NULL),
(3, 'Carlos Técnico', '333.333.333-33', '(21) 97777-3333', 'carlos.tecnico@empresa.com', 'Suporte avançado', '2025-09-05 19:13:19', '2025-09-05 19:13:19', NULL),
(4, 'Junio Rodrigues', '321.321.321-32', '(13) 2132-1321', 'juriorodrigues@gmail.com', 'teste 1234', '2025-09-06 11:21:40', '2025-09-06 12:19:23', '2025-09-06 12:19:23');

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

--
-- Despejando dados para a tabela `u1_usuarios`
--

INSERT INTO `u1_usuarios` (`u1_id`, `u1_cpf`, `u1_nome`, `u1_email`, `u1_usuario_acesso`, `u1_senha_usuario`, `u1_tipo_permissao`, `u1_data_ultimo_acesso`, `u1_horario_geracao_token`, `u1_token_reset_senha_acesso`, `u1_created_at`, `u1_updated_at`, `u1_deleted_at`) VALUES
(7, '123.456.789-00', 'Administrador Sistema', 'admin@sistema.com', 'admin', '$2y$10$bsuRIaYh/5aEbDe10ZeKcuAgmbWhcER02mjIBPjcYlA8DVBKIyN1S', 'administrador', '2025-09-08 11:26:20', NULL, NULL, '2025-09-03 11:22:57', '2025-09-08 08:26:20', NULL),
(8, '987.654.321-00', 'João Vendedor', 'joao.vendedor@sistema.com', 'joao.vendas', '$2y$10$bsuRIaYh/5aEbDe10ZeKcuAgmbWhcER02mjIBPjcYlA8DVBKIyN1S', 'venda', '2025-09-03 14:22:57', NULL, NULL, '2025-09-03 11:22:57', '2025-09-03 11:23:49', NULL),
(9, '456.789.123-00', 'Maria Cadastro', 'maria.cadastro@sistema.com', 'maria.cad', '$2y$10$bsuRIaYh/5aEbDe10ZeKcuAgmbWhcER02mjIBPjcYlA8DVBKIyN1S', 'cadastro', '2025-09-03 14:22:57', NULL, NULL, '2025-09-03 11:22:57', '2025-09-04 15:31:51', '2025-09-04 15:31:51'),
(10, '32132116544', 'Isaías Oliveira', 'visaotec@gmail.com', 'visaotec10', '$2y$10$aDb5PqIxXVj8uruevvyCwem29GDxwq81vaKi8yoUG68m0Uw62bNZO', 'administrador', '2025-09-04 18:38:00', NULL, NULL, '2025-09-04 15:38:00', '2025-09-04 15:50:59', NULL),
(11, '12345678900', 'Júnior Neves 10', 'juniorneves@gmail.com', 'juniorneves', '$2y$10$ox47HtkxbWx5pwhvAT1yqex/uxso74vivfYypR2cDFp9OIICxLYmC', 'venda', '2025-09-04 18:54:48', NULL, NULL, '2025-09-04 15:54:48', '2025-09-04 16:37:06', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `v1_vendas`
--

CREATE TABLE `v1_vendas` (
  `v1_id` bigint(20) NOT NULL,
  `v1_numero_venda` varchar(50) NOT NULL DEFAULT '',
  `v1_cliente_id` bigint(20) NOT NULL,
  `v1_vendedor_nome` varchar(255) NOT NULL,
  `v1_vendedor_id` bigint(20) UNSIGNED NOT NULL,
  `v1_tipo_de_pagamento` enum('dinheiro','cartao_credito','cartao_debito','pix','transferencia','boleto','a_prazo') NOT NULL,
  `v1_desconto` decimal(10,2) DEFAULT 0.00,
  `v1_valor_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `v1_codigo_transacao` varchar(255) DEFAULT NULL,
  `v1_valor_a_ser_pago` decimal(10,2) NOT NULL DEFAULT 0.00,
  `v1_status` enum('Em Aberto','Faturado','Atrasado','Cancelado') NOT NULL DEFAULT 'Em Aberto',
  `v1_created_at` datetime DEFAULT NULL,
  `v1_data_pagamento` date DEFAULT NULL,
  `v1_data_faturamento` date DEFAULT NULL,
  `v1_observacoes` text DEFAULT NULL,
  `v1_updated_at` datetime DEFAULT NULL,
  `v1_deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabela para registro de vendas do sistema PDV';

--
-- Despejando dados para a tabela `v1_vendas`
--

INSERT INTO `v1_vendas` (`v1_id`, `v1_numero_venda`, `v1_cliente_id`, `v1_vendedor_nome`, `v1_vendedor_id`, `v1_tipo_de_pagamento`, `v1_desconto`, `v1_valor_total`, `v1_codigo_transacao`, `v1_valor_a_ser_pago`, `v1_status`, `v1_created_at`, `v1_data_pagamento`, `v1_data_faturamento`, `v1_observacoes`, `v1_updated_at`, `v1_deleted_at`) VALUES
(2, 'VD0002', 172, 'Isaías Oliveira', 10, 'dinheiro', 0.00, 0.00, NULL, 0.00, 'Em Aberto', '2025-09-07 18:52:19', NULL, NULL, 'teste 1234', '2025-09-07 18:52:19', NULL),
(3, 'VD0003', 172, 'Isaías Oliveira', 10, 'dinheiro', 0.00, 0.00, NULL, 0.00, 'Em Aberto', '2025-09-07 18:56:59', NULL, NULL, 'teste 1234', '2025-09-07 18:56:59', NULL),
(4, 'VD0004', 160, 'João Vendedor', 8, 'dinheiro', 0.00, 0.00, NULL, 0.00, 'Em Aberto', '2025-09-07 19:04:41', NULL, NULL, 'teste 1234', '2025-09-07 19:04:41', NULL),
(5, 'VD0005', 172, 'Isaías Oliveira', 10, 'transferencia', 0.00, 0.00, NULL, 0.00, 'Em Aberto', '2025-09-07 19:07:42', NULL, NULL, 'teste 1234', '2025-09-07 19:07:42', NULL),
(6, 'VD0006', 160, 'Isaías Oliveira', 10, 'cartao_debito', 0.00, 0.00, NULL, 0.00, 'Em Aberto', '2025-09-07 19:09:54', NULL, NULL, 'teste', '2025-09-07 19:09:54', NULL),
(7, 'VD0007', 172, 'Isaías Oliveira', 10, 'cartao_credito', 0.00, 0.00, NULL, 0.00, 'Em Aberto', '2025-09-07 19:11:45', NULL, NULL, 'teste', '2025-09-07 19:11:45', NULL),
(8, 'VD0008', 160, 'João Vendedor', 8, 'cartao_credito', 0.00, 0.00, NULL, 0.00, 'Em Aberto', '2025-09-07 19:16:44', NULL, NULL, 'teste', '2025-09-07 19:16:44', NULL);

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
-- Índices de tabela `l2_licencas`
--
ALTER TABLE `l2_licencas`
  ADD PRIMARY KEY (`l2_id`),
  ADD KEY `idx_l2_user` (`l2_user_id`);

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
-- Índices de tabela `o1_ordens`
--
ALTER TABLE `o1_ordens`
  ADD PRIMARY KEY (`o1_id`),
  ADD UNIQUE KEY `o1_numero_ordem` (`o1_numero_ordem`),
  ADD KEY `o1_cliente_id` (`o1_cliente_id`),
  ADD KEY `o1_tecnico_id` (`o1_tecnico_id`),
  ADD KEY `idx_o1_status` (`o1_status`),
  ADD KEY `idx_o1_data_entrada` (`o1_data_entrada`);

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
-- Índices de tabela `p3_produtos_ordem`
--
ALTER TABLE `p3_produtos_ordem`
  ADD PRIMARY KEY (`p3_id`),
  ADD KEY `p3_ordem_id` (`p3_ordem_id`),
  ADD KEY `p3_produto_id` (`p3_produto_id`);

--
-- Índices de tabela `s1_servicos`
--
ALTER TABLE `s1_servicos`
  ADD PRIMARY KEY (`s1_id`),
  ADD UNIQUE KEY `s1_codigo_servico` (`s1_codigo_servico`),
  ADD KEY `idx_s1_status` (`s1_status`);

--
-- Índices de tabela `s2_servicos_ordem`
--
ALTER TABLE `s2_servicos_ordem`
  ADD PRIMARY KEY (`s2_id`),
  ADD KEY `s2_ordem_id` (`s2_ordem_id`),
  ADD KEY `s2_servico_id` (`s2_servico_id`),
  ADD KEY `s2_tecnico_id` (`s2_tecnico_id`);

--
-- Índices de tabela `t1_tecnicos`
--
ALTER TABLE `t1_tecnicos`
  ADD PRIMARY KEY (`t1_id`),
  ADD UNIQUE KEY `t1_cpf` (`t1_cpf`);

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
  ADD UNIQUE KEY `v1_numero_da_venda` (`v1_numero_venda`) USING BTREE,
  ADD KEY `v1_cliente_id` (`v1_cliente_id`) USING BTREE,
  ADD KEY `idx_v1_vendedor_id` (`v1_vendedor_id`) USING BTREE;

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `c1_categorias`
--
ALTER TABLE `c1_categorias`
  MODIFY `c1_id` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `c2_clientes`
--
ALTER TABLE `c2_clientes`
  MODIFY `c2_id` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=176;

--
-- AUTO_INCREMENT de tabela `c3_configuracoes`
--
ALTER TABLE `c3_configuracoes`
  MODIFY `c3_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `f1_fornecedores`
--
ALTER TABLE `f1_fornecedores`
  MODIFY `f1_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de tabela `g1_garantias`
--
ALTER TABLE `g1_garantias`
  MODIFY `g1_id` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `l2_licencas`
--
ALTER TABLE `l2_licencas`
  MODIFY `l2_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `l2_logs`
--
ALTER TABLE `l2_logs`
  MODIFY `l2_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de tabela `o1_ordens`
--
ALTER TABLE `o1_ordens`
  MODIFY `o1_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `p1_produtos`
--
ALTER TABLE `p1_produtos`
  MODIFY `p1_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de tabela `p2_produtos_venda`
--
ALTER TABLE `p2_produtos_venda`
  MODIFY `p2_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `p3_produtos_ordem`
--
ALTER TABLE `p3_produtos_ordem`
  MODIFY `p3_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `s1_servicos`
--
ALTER TABLE `s1_servicos`
  MODIFY `s1_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `s2_servicos_ordem`
--
ALTER TABLE `s2_servicos_ordem`
  MODIFY `s2_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `t1_tecnicos`
--
ALTER TABLE `t1_tecnicos`
  MODIFY `t1_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `u1_usuarios`
--
ALTER TABLE `u1_usuarios`
  MODIFY `u1_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `v1_vendas`
--
ALTER TABLE `v1_vendas`
  MODIFY `v1_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `l2_logs`
--
ALTER TABLE `l2_logs`
  ADD CONSTRAINT `fk_l2_logs_usuario` FOREIGN KEY (`l2_id_usuario`) REFERENCES `u1_usuarios` (`u1_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `o1_ordens`
--
ALTER TABLE `o1_ordens`
  ADD CONSTRAINT `fk_o1_ordens_cliente` FOREIGN KEY (`o1_cliente_id`) REFERENCES `c2_clientes` (`c2_id`);

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
-- Restrições para tabelas `p3_produtos_ordem`
--
ALTER TABLE `p3_produtos_ordem`
  ADD CONSTRAINT `fk_p3_produtos_ordem_ordem` FOREIGN KEY (`p3_ordem_id`) REFERENCES `o1_ordens` (`o1_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_p3_produtos_ordem_produto` FOREIGN KEY (`p3_produto_id`) REFERENCES `p1_produtos` (`p1_id`);

--
-- Restrições para tabelas `s2_servicos_ordem`
--
ALTER TABLE `s2_servicos_ordem`
  ADD CONSTRAINT `fk_s2_servicos_ordem_ordem` FOREIGN KEY (`s2_ordem_id`) REFERENCES `o1_ordens` (`o1_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_s2_servicos_ordem_servico` FOREIGN KEY (`s2_servico_id`) REFERENCES `s1_servicos` (`s1_id`),
  ADD CONSTRAINT `fk_s2_servicos_ordem_tecnico` FOREIGN KEY (`s2_tecnico_id`) REFERENCES `u1_usuarios` (`u1_id`);

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
