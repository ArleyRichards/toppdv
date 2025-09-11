<?php require "../layouts/session.php";
require_once '../controllers/db_connection.php';
$connect = new DbConnection();
$connect = $connect->getConnection();

#carregar os clientes
$client_sql = "SELECT * FROM tb_clientes ORDER BY nome ASC";
$stmt = $connect->prepare($client_sql);
$stmt->execute();
$result_client = $stmt->fetchAll(PDO::FETCH_OBJ);

#carregar os produtos
$product_sql = "SELECT * FROM tb_produtos ORDER BY nome_produto ASC";
$stmt = $connect->prepare($product_sql);
$stmt->execute();
$result_product = $stmt->fetchAll(PDO::FETCH_OBJ);

// Converter produtos para JavaScript
$produtos_js = [];
foreach ($result_product as $product) {
    $produtos_js[] = [
        'id' => $product->id,
        'nome' => $product->nome_produto,
        'codigo' => $product->codigo_produto ?? '',
        'preco' => $product->preco_venda_produto,
        'imagem' => $product->imagem_produto,
        'estoque' => $product->quantidade_produto
    ];
}

// Converter clientes para JavaScript
$clientes_js = [];
foreach ($result_client as $client) {
    $clientes_js[] = [
        'id' => $client->id,
        'nome' => $client->nome,
        'cpf' => $client->cpf
    ];
}

// Verificar estado do caixa na sessão
$caixa_aberto = $_SESSION['caixa_aberto'] ?? false;
$valor_inicial_caixa = $_SESSION['caixa_valor_inicial'] ?? 0;
$data_abertura_caixa = $_SESSION['caixa_data_abertura'] ?? null;

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <?php require '../layouts/head.php' ?>
    <link rel="stylesheet" href="../css/sales.css">
    <link rel="stylesheet" href="../css/money-fields.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/i18n/pt-BR.js"></script>
    
    <!-- Estilos para o card do cliente no modal -->
    <style>
        #client_cupom_section .card {
            border: 2px solid #0d6efd;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15);
            background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
            transition: all 0.3s ease;
        }
        
        #client_cupom_section .card:hover {
            box-shadow: 0 6px 20px rgba(13, 110, 253, 0.25);
            transform: translateY(-2px);
        }
        
        #client_cupom_section .card-body {
            padding: 1.5rem;
        }
        
        #client_cupom_section .card-title {
            color: #0d6efd;
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }
        
        .client-details strong {
            color: #212529 !important;
            font-size: 1.2rem;
            font-weight: 600;
            display: block;
            margin-bottom: 0.5rem;
            text-shadow: none;
        }
        
        .client-details small {
            color: #6c757d !important;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        #client_cupom_section .form-check {
            background: rgba(13, 110, 253, 0.1);
            padding: 1rem;
            border-radius: 8px;
            border: 1px solid rgba(13, 110, 253, 0.2);
            transition: all 0.3s ease;
        }
        
        #client_cupom_section .form-check:hover {
            background: rgba(13, 110, 253, 0.15);
            border-color: rgba(13, 110, 253, 0.3);
        }
        
        #client_cupom_section .form-check-input {
            transform: scale(1.3);
            margin-right: 0.7rem;
        }
        
        #client_cupom_section .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        
        #client_cupom_section .form-check-label {
            font-weight: 600;
            color: #0d6efd;
            font-size: 1rem;
            cursor: pointer;
        }
        
        .client-info-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }
        
        .client-info-left {
            flex: 1;
        }
        
        .client-info-right {
            flex-shrink: 0;
        }
        
        /* Melhorar aparência geral do modal */
        #modal-close-order .modal-content {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        
        #modal-close-order .modal-header {
            border-radius: 15px 15px 0 0;
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
        }
        
        #modal-close-order .modal-body {
            padding: 2rem;
        }
        
        /* Estilos para dropdown de sugestões de produtos */
        .product-search-container {
            position: relative;
        }
        
        .product-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            max-height: 300px;
            overflow-y: auto;
        }
        
        .suggestions-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        
        .suggestion-item {
            padding: 12px 16px;
            cursor: pointer;
            border-bottom: 1px solid #f1f3f4;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .suggestion-item:last-child {
            border-bottom: none;
        }
        
        .suggestion-item:hover,
        .suggestion-item.highlighted {
            background-color: #e3f2fd;
            border-color: #2196f3;
        }
        
        .suggestion-item.highlighted {
            background-color: #1976d2;
            color: white;
        }
        
        .suggestion-product-image {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #dee2e6;
        }
        
        .suggestion-product-info {
            flex: 1;
        }
        
        .suggestion-product-name {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 2px;
            color: inherit;
        }
        
        .suggestion-product-details {
            font-size: 12px;
            color: #6c757d;
            display: flex;
            gap: 8px;
        }
        
        .suggestion-item.highlighted .suggestion-product-details {
            color: rgba(255, 255, 255, 0.8);
        }
        
        .suggestion-product-price {
            font-weight: 600;
            color: #28a745;
            font-size: 14px;
        }
        
        .suggestion-item.highlighted .suggestion-product-price {
            color: #c8e6c9;
        }

        /* Estilos para sugestões de cliente */
        .client-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            max-height: 300px;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .client-suggestions .suggestions-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .client-suggestion-item {
            padding: 12px;
            border-bottom: 1px solid #f0f0f0;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.2s ease;
        }

        .client-suggestion-item:last-child {
            border-bottom: none;
        }

        .client-suggestion-item:hover,
        .client-suggestion-item.highlighted {
            background-color: #e3f2fd;
            border-color: #2196f3;
        }

        .client-suggestion-item.highlighted {
            background-color: #1976d2;
            color: white;
        }

        .client-suggestion-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
            font-weight: bold;
        }

        .client-suggestion-info {
            flex: 1;
        }

        .client-suggestion-name {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 2px;
            color: inherit;
        }

        .client-suggestion-details {
            font-size: 12px;
            color: #6c757d;
            display: flex;
            gap: 8px;
        }

        .client-suggestion-item.highlighted .client-suggestion-details {
            color: rgba(255, 255, 255, 0.8);
        }

        .client-suggestion-status {
            font-weight: 600;
            color: #28a745;
            font-size: 12px;
        }

        .client-suggestion-item.highlighted .client-suggestion-status {
            color: #c8e6c9;
        }
        
        .no-suggestions {
            padding: 16px;
            text-align: center;
            color: #6c757d;
            font-style: italic;
        }

        /* Melhorias para os botões de atalho */
        .function-keys {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }

        .function-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 8px 6px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 11px;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
            min-height: 50px;
        }

        .function-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .function-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        .function-btn .key {
            font-size: 12px;
            font-weight: bold;
            background: rgba(255, 255, 255, 0.2);
            padding: 2px 6px;
            border-radius: 4px;
            margin-bottom: 4px;
        }

        .function-btn .action {
            font-size: 10px;
            text-align: center;
            line-height: 1.2;
        }

        /* Responsividade para botões de atalho */
        @media (max-width: 768px) {
            .function-keys {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* Feedback visual para atalhos de teclado */
        .keyboard-shortcut-feedback {
            animation: slideInRight 0.3s ease-out;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Estilos para dicas de atalho nos labels */
        .shortcut-hint {
            font-size: 11px;
            font-weight: 500;
            color: #6c757d;
            background: rgba(108, 117, 125, 0.1);
            padding: 2px 6px;
            border-radius: 4px;
            margin-left: 8px;
            border: 1px solid rgba(108, 117, 125, 0.2);
            transition: all 0.3s ease;
            display: inline-block;
        }

        .section-title .shortcut-hint {
            font-size: 10px;
            background: rgba(255, 255, 255, 0.8);
            color: #495057;
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        .info-label .shortcut-hint {
            font-size: 9px;
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
            border: 1px solid rgba(102, 126, 234, 0.2);
        }

        .shortcut-hint:hover {
            background: rgba(102, 126, 234, 0.2);
            color: #667eea;
            border-color: rgba(102, 126, 234, 0.3);
            transform: scale(1.05);
        }

        /* Animação pulse para chamar atenção */
        @keyframes pulseHint {
            0% { opacity: 1; }
            50% { opacity: 0.7; }
            100% { opacity: 1; }
        }

        .shortcut-hint.pulse {
            animation: pulseHint 1.5s ease-in-out infinite;
        }

        /* Responsividade das dicas */
        @media (max-width: 768px) {
            .shortcut-hint {
                font-size: 10px;
                padding: 1px 4px;
                margin-left: 4px;
            }
            
            .section-title .shortcut-hint {
                font-size: 9px;
            }
            
            .info-label .shortcut-hint {
                font-size: 8px;
            }
        }

        /* Estilos para informações de seleção de cliente e produto */
        .selected-client-info, .selected-product-info {
            margin-top: 8px;
            opacity: 0;
            transform: translateY(-10px);
            transition: all 0.3s ease;
        }

        .selected-client-info.show, .selected-product-info.show {
            opacity: 1;
            transform: translateY(0);
        }

        .selected-client-info .client-details,
        .selected-product-info .product-details {
            flex: 1;
        }

        .selected-client-info strong,
        .selected-product-info strong {
            color: #155724 !important;
            font-size: 14px;
            font-weight: 600;
            display: block;
            margin-bottom: 2px;
        }

        .selected-client-info small,
        .selected-product-info small {
            color: #6c757d !important;
            font-size: 12px;
            font-weight: 500;
        }

        /* Botão X para limpar seleção */
        .clear-selection-btn {
            background: none !important;
            border: none !important;
            color: #dc3545 !important;
            padding: 4px 8px !important;
            border-radius: 4px !important;
            cursor: pointer !important;
            transition: all 0.2s ease !important;
            margin-left: auto !important;
            font-size: 14px !important;
        }

        .clear-selection-btn:hover {
            background: rgba(220, 53, 69, 0.1) !important;
            color: #c82333 !important;
            transform: scale(1.1) !important;
        }

        .clear-selection-btn:active {
            transform: scale(0.95) !important;
        }

        /* Container da seleção */
        .selection-container {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border: 1px solid #c3e6cb;
            border-radius: 8px;
            margin-top: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .selection-container:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
            transform: translateY(-1px);
        }

        .selection-icon {
            color: #28a745;
            font-size: 16px;
            flex-shrink: 0;
        }

        .selection-info {
            flex: 1;
            min-width: 0;
        }

        .selection-name {
            font-weight: 600;
            color: #155724;
            font-size: 14px;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .selection-details {
            font-size: 12px;
            color: #6c757d;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>

<body class="pdv-body">
    <!-- HEADER DO PDV -->
    <header class="pdv-header">
        <div class="header-left">
            <div class="logo-section">
                <i class="fas fa-store text-primary"></i>
                <span class="store-name">TOP PDV</span>
            </div>
            <div class="terminal-info">
                <span class="terminal-number">Terminal 001</span>
                <span class="date-time" id="datetime"></span>
            </div>
        </div>
        
        <div class="header-center">
            <div class="operator-info">
                <span class="operator-name"><?php echo $_SESSION['name_user']; ?></span>
                <span class="operator-user">@<?php echo $_SESSION['access_user']; ?></span>
                <span class="cash-status">
                    <i class="fa-solid fa-circle-xmark text-danger me-1"></i>
                    Caixa Fechado
                </span>
            </div>
        </div>
        
        <div class="header-right">
            <div class="cash-controls">
                <button class="btn btn-success" id="btnOpenCash">
                    <i class="fa-solid fa-cash-register"></i>
                    Abrir Caixa
                    <span class="shortcut-hint">(F8)</span>
                </button>
                <button class="btn btn-warning" id="btnCloseCash" style="display: none;">
                    <i class="fa-solid fa-lock"></i>
                    Fechar Caixa
                    <span class="shortcut-hint">(F8)</span>
                </button>
                <button class="btn btn-secondary" id="btnExit" style="<?php echo $caixa_aberto ? 'display: none;' : ''; ?>">
                    <i class="fa-solid fa-home"></i>
                    Menu
                </button>
            </div>
        </div>
    </header>

    <!-- MAIN PDV INTERFACE -->
    <main class="pdv-main">
        <!-- PAINEL ESQUERDO - ENTRADA DE PRODUTOS -->
        <section class="left-panel">
            <!-- Cliente -->
            <div class="client-section">
                <h3 class="section-title">
                    <i class="fas fa-user"></i>
                    Cliente
                    <span class="shortcut-hint">(F5)</span>
                </h3>
                <div class="client-search-container" style="position: relative;">
                    <input type="text" class="form-control client-search" id="client_search" 
                           placeholder="Digite o CPF ou nome do cliente">
                    <div class="client-search-feedback">
                        <i class="fas fa-check-circle"></i>
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <input type="hidden" id="selected_client_id">
                    
                    <!-- Dropdown de sugestões de clientes -->
                    <div class="client-suggestions" id="client_suggestions" style="display: none;">
                        <ul class="suggestions-list" id="client_suggestions_list">
                            <!-- Sugestões serão inseridas aqui dinamicamente -->
                        </ul>
                    </div>
                </div>
                <div class="selected-client-info" id="selected_client_info" style="display: none;">
                    <div class="client-details">
                        <strong id="client_name"></strong>
                        <small id="client_cpf" class="text-muted"></small>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearClientSelection()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <!-- Busca de Produto -->
            <div class="product-input-section">
                <h3 class="section-title">
                    <i class="fas fa-barcode"></i>
                    Produto
                    <span class="shortcut-hint">(F6)</span>
                </h3>
                <div class="product-search-container" style="position: relative;">
                    <input type="text" class="form-control product-search" id="product_search" 
                           placeholder="Digite o código de barras ou nome do produto">
                    <div class="product-search-feedback">
                        <i class="fas fa-check-circle"></i>
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <input type="hidden" id="selected_product_id">
                    
                    <!-- Dropdown de sugestões de produtos -->
                    <div class="product-suggestions" id="product_suggestions" style="display: none;">
                        <ul class="suggestions-list" id="suggestions_list">
                            <!-- Sugestões serão inseridas aqui dinamicamente -->
                        </ul>
                    </div>
                </div>
                <div class="selected-product-info" id="selected_product_info" style="display: none;">
                    <div class="product-details">
                        <strong id="product_name"></strong>
                        <small id="product_code" class="text-muted"></small>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearProductSelection()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <!-- Informações do Produto -->
            <div class="product-details container_product">
                <div class="product-image-container">
                    <img id="preview_img" src="../assets/img/avatar/shopping-cart.webp" alt="Produto" class="product-image">
                </div>
                
                <div class="product-info-grid">
                    <div class="info-group">
                        <label class="info-label">
                            Qtd
                            <span class="shortcut-hint">(F7)</span>
                        </label>
                        <input type="number" class="form-control quantity-input" id="product_quantity" value="0" min="1">
                    </div>
                    
                    <div class="info-group">
                        <label class="info-label">Vlr. Unit.</label>
                        <input type="text" class="form-control money-mask" id="unit_price" disabled value="R$ 0,00">
                    </div>
                    
                    <div class="info-group">
                        <label class="info-label">Vlr. Total</label>
                        <input type="text" class="form-control money-mask" id="total_price" disabled value="R$ 0,00">
                    </div>
                </div>
            </div>

            <!-- Atalhos de Função -->
            <div class="function-keys">
                <button class="function-btn" onclick="window.location.href='home.php'" title="Pressione F2 para acessar o menu">
                    <span class="key">F2</span>
                    <span class="action">Menu</span>
                </button>
                <button class="function-btn" onclick="cancelSale()" title="Pressione F3 para cancelar a venda">
                    <span class="key">F3</span>
                    <span class="action">Cancelar</span>
                </button>
                <button class="function-btn" onclick="closeSale()" id="close_sale" disabled title="Pressione F4 para finalizar a venda">
                    <span class="key">F4</span>
                    <span class="action">Finalizar</span>
                </button>
                <button class="function-btn" onclick="document.getElementById('client_search').focus()" title="Pressione F5 para focar no campo de cliente">
                    <span class="key">F5</span>
                    <span class="action">Cliente</span>
                </button>
                <button class="function-btn" onclick="document.getElementById('product_search').focus()" title="Pressione F6 para focar no campo de produto">
                    <span class="key">F6</span>
                    <span class="action">Produto</span>
                </button>
                <button class="function-btn" onclick="document.getElementById('product_quantity').focus()" title="Pressione F7 para focar no campo de quantidade">
                    <span class="key">F7</span>
                    <span class="action">Quantidade</span>
                </button>
                <button class="function-btn" onclick="triggerF8Action()" title="Pressione F8 para abrir/fechar caixa ou confirmar em modal">
                    <span class="key">F8</span>
                    <span class="action">Caixa</span>
                </button>
            </div>
        </section>

        <!-- PAINEL DIREITO - CUPOM FISCAL -->
        <section class="right-panel">
            <div class="receipt-container container-cupom">
                <div class="receipt-header">
                    <h3>
                        <i class="fas fa-receipt"></i>
                        Cupom Fiscal
                    </h3>
                </div>
                
                <div class="receipt-content">
                    <div class="table-container">
                        <table class="receipt-table" id="table_product">
                            <thead>
                                <tr>
                                    <th>COD</th>
                                    <th>PRODUTO</th>
                                    <th>VL.UN</th>
                                    <th>QTD</th>
                                    <th>TOTAL</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Produtos serão inseridos aqui -->
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Total da Venda -->
                <div class="receipt-total">
                    <div class="total-section">
                        <div class="total-label">TOTAL GERAL</div>
                        <div class="total-amount">
                            <input type="text" class="total-display" id="subtotal" disabled value="R$ 0,00">
                        </div>
                    </div>
                    <div id="receipt-discount" style="font-size: 15px; color: #dc3545; font-weight: 600; margin-bottom: 4px; display: none;"></div>
                    <div id="receipt-total-value"></div>
                </div>
            </div>
        </section>
    </main>

    <!-- MODAL DE PAGAMENTO -->
    <div class="modal fade" id="modal-close-order" tabindex="-1" aria-labelledby="modalCloseOrderLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalCloseOrderLabel">
                        <i class="fas fa-credit-card me-2"></i>
                        Finalizar Venda
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formCloseOrder">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <input type="hidden" name="action" value="register_sale">
                        <input type="hidden" id="client_id_hidden" name="client_id" value="">
                        <input type="hidden" id="include_client_hidden" name="include_client_in_cupom" value="0">
                        <input type="hidden" id="include_warranty_hidden" name="include_warranty_in_cupom" value="1">
                        <input type="hidden" id="discount_hidden" name="discount" value="0">
                        
                        <!-- Informações do Cliente no Cupom -->
                        <div class="row mb-3" id="client_cupom_section" style="display: none;">
                            <div class="col-12">
                                <div class="card border-info">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-user text-info me-2"></i>
                                            Cliente Selecionado
                                        </h6>
                                        <div class="client-info-container">
                                            <div class="client-info-left">
                                                <div class="client-details">
                                                    <strong id="modal_client_name"></strong>
                                                    <small class="text-muted" id="modal_client_cpf"></small>
                                                </div>
                                            </div>
                                            <div class="client-info-right">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="include_client_in_cupom" checked>
                                                    <label class="form-check-label" for="include_client_in_cupom">
                                                        <i class="fas fa-receipt me-1"></i>
                                                        Incluir no cupom
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Opções do Cupom -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="card border-secondary">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-receipt text-secondary me-2"></i>
                                            Opções do Cupom
                                        </h6>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="include_warranty_in_cupom" checked>
                                            <label class="form-check-label" for="include_warranty_in_cupom">
                                                <i class="fas fa-shield-alt me-1"></i>
                                                Incluir informações de garantia no cupom
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="total_sale" placeholder="Total da Venda" disabled>
                                    <label for="total_sale">Total da Venda</label>
                                </div>
                                
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="payment_type" name="payment_type" required>
                                        <option value="dinheiro">Dinheiro</option>
                                        <option value="a_prazo">A Prazo</option>
                                        <option value="cartao_debito">Cartão de Débito</option>
                                        <option value="cartao_credito">Cartão de Crédito</option>
                                        <option value="pix">PIX</option>
                                    </select>
                                    <label for="payment_type">Forma de Pagamento</label>
                                </div>
                                <div class="form-floating mb-3" id="prazo-data-container" style="display:none;">
                                    <input type="date" class="form-control" id="data_pagamento" name="data_pagamento">
                                    <label for="data_pagamento">Data do Pagamento</label>
                                </div>
                                
                                <div class="form-floating mb-3">
                                    <input type="number" class="form-control" id="discount" placeholder="Desconto %" min="0" max="100" step="0.01">
                                    <label for="discount">Desconto (%)</label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="disconunted_price" placeholder="Valor com Desconto" disabled>
                                    <label for="disconunted_price">Valor com Desconto</label>
                                </div>
                                
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="amount_paid" placeholder="Valor a Pagar" disabled>
                                    <label for="amount_paid">Valor a Pagar</label>
                                </div>
                                
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="cd_transaction_pix" placeholder="Código da Transação" disabled>
                                    <label for="cd_transaction_pix">Código da Transação</label>
                                </div>
                                <!-- Campo de Observações -->
                                <div class="form-floating mb-3">
                                    <textarea class="form-control" id="observacoes" name="observacoes" placeholder="Observações da venda" style="height: 80px; resize: vertical;"></textarea>
                                    <label for="observacoes">Observações</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" onclick="backToSale()">
                                <i class="fas fa-arrow-left me-2"></i>Voltar
                            </button>
                            <button type="submit" class="btn btn-success" id="closeSaleModal">
                                <i class="fas fa-check me-2"></i>Finalizar Venda
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts JavaScript -->
    <script>
    // Exibir campo de data quando "A Prazo" for selecionado
    document.addEventListener('DOMContentLoaded', function() {
        // Sincronizar desconto do input para o hidden
        var discountInput = document.getElementById('discount');
        var discountHidden = document.getElementById('discount_hidden');
        if (discountInput && discountHidden) {
            discountInput.addEventListener('input', function() {
                discountHidden.value = discountInput.value;
            });
        }
        var paymentTypeSelect = document.getElementById('payment_type');
        var prazoDataContainer = document.getElementById('prazo-data-container');
        var dataPagamentoInput = document.getElementById('data_pagamento');
        if (paymentTypeSelect) {
            paymentTypeSelect.addEventListener('change', function() {
                if (this.value === 'a_prazo') {
                    prazoDataContainer.style.display = 'block';
                    dataPagamentoInput.required = true;
                } else {
                    prazoDataContainer.style.display = 'none';
                    dataPagamentoInput.required = false;
                    dataPagamentoInput.value = '';
                }
            });
        }

        // Atualizar desconto no cupom fiscal ao finalizar venda
        var closeSaleModalBtn = document.getElementById('closeSaleModal');
        if (closeSaleModalBtn) {
            closeSaleModalBtn.addEventListener('click', function(e) {
                // Pega o valor do desconto
                var discountInput = document.getElementById('discount');
                var discountedPriceInput = document.getElementById('disconunted_price');
                var totalValue = discountedPriceInput && discountedPriceInput.value ? discountedPriceInput.value : '';
                var discountPercent = discountInput && discountInput.value ? parseFloat(discountInput.value) : 0;
                var discountValue = '';
                if (discountPercent > 0 && totalValue) {
                    // Calcula valor do desconto
                    var total = parseFloat(totalValue.replace(/[^0-9\,\.]/g, '').replace(',', '.'));
                    discountValue = (total * (discountPercent / 100)).toFixed(2);
                }
                var receiptDiscount = document.getElementById('receipt-discount');
                var receiptTotalValue = document.getElementById('receipt-total-value');
                if (discountPercent > 0 && discountValue) {
                    receiptDiscount.style.display = 'block';
                    receiptDiscount.textContent = `Desconto: ${discountPercent}% (-R$ ${parseFloat(discountValue).toFixed(2).replace('.', ',')})`;
                } else {
                    receiptDiscount.style.display = 'none';
                    receiptDiscount.textContent = '';
                }
                // Atualiza o valor total
                if (receiptTotalValue && totalValue) {
                    receiptTotalValue.textContent = `Total a pagar: R$ ${totalValue}`;
                }
            });
        }
    });
    // Dados dos produtos disponíveis
    window.produtosDisponiveis = <?php echo json_encode($produtos_js); ?>;
    console.log('Produtos carregados:', window.produtosDisponiveis ? window.produtosDisponiveis.length : 'undefined');

    // Dados dos clientes disponíveis
    window.clientesDisponiveis = <?php echo json_encode($clientes_js); ?>;
    console.log('Clientes carregados:', window.clientesDisponiveis ? window.clientesDisponiveis.length : 'undefined');

    // Estado inicial do caixa a partir da sessão PHP
    window.estadoInicialCaixa = {
        caixaAberto: <?php echo json_encode($caixa_aberto); ?>,
        valorInicial: <?php echo json_encode($valor_inicial_caixa); ?>,
        dataAbertura: <?php echo json_encode($data_abertura_caixa); ?>
    };

    // Atualizar data/hora
    function updateDateTime() {
        const now = new Date();
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit', 
            minute: '2-digit'
        };
        document.getElementById('datetime').textContent = now.toLocaleDateString('pt-BR', options);
    }

    // Atualizar a cada minuto
    updateDateTime();
    setInterval(updateDateTime, 60000);

    // Função global para limpar seleção do cliente
    window.clearClientSelection = function() {
        // Chamar diretamente a implementação sem verificação recursiva
        const selectedInfo = document.getElementById('selected_client_info');
        if (selectedInfo) {
            selectedInfo.classList.remove('show');
            setTimeout(() => {
                selectedInfo.style.display = 'none';
            }, 300);
        }
        
        document.getElementById('selected_client_id').value = '';
        document.getElementById('client_search').value = '';
        document.getElementById('client_search').focus();
    };

    // Função global para limpar seleção do produto
    window.clearProductSelection = function() {
        // Chamar diretamente a implementação sem verificação recursiva
        const selectedInfo = document.getElementById('selected_product_info');
        if (selectedInfo) {
            selectedInfo.classList.remove('show');
            setTimeout(() => {
                selectedInfo.style.display = 'none';
            }, 300);
        }
        
        document.getElementById('selected_product_id').value = '';
        document.getElementById('product_search').value = '';
        document.getElementById('product_search').focus();
        
        // Limpar também as informações do produto exibidas
        const previewImg = document.getElementById('preview_img');
        if (previewImg) {
            previewImg.src = '../assets/img/avatar/shopping-cart.webp';
        }
        
        const quantityInput = document.getElementById('product_quantity');
        if (quantityInput) {
            quantityInput.value = '0';
        }
        
        // Chamar função de limpeza se existir (compatibilidade)
        if (typeof window.limparProduto === 'function') {
            window.limparProduto();
        } else if (typeof limparProduto === 'function') {
            limparProduto();
        }
    };

    // Função para atualizar as informações do cliente no modal de finalização
    window.updateClientInfoInModal = function(clientData) {
        const clientSection = document.getElementById('client_cupom_section');
        const modalClientName = document.getElementById('modal_client_name');
        const modalClientCpf = document.getElementById('modal_client_cpf');
        const clientIdHidden = document.getElementById('client_id_hidden');
        const includeClientCheckbox = document.getElementById('include_client_in_cupom');
        const includeClientHidden = document.getElementById('include_client_hidden');

        if (clientData && clientData.id) {
            // Mostrar seção do cliente
            clientSection.style.display = 'block';
            
            // Preencher informações do cliente
            modalClientName.textContent = clientData.nome || '';
            modalClientCpf.textContent = `CPF: ${clientData.cpf || ''}`;
            clientIdHidden.value = clientData.id;
            
            // Marcar checkbox como selecionado por padrão
            includeClientCheckbox.checked = true;
            includeClientHidden.value = '1';
            
            // Adicionar evento ao checkbox
            includeClientCheckbox.addEventListener('change', function() {
                includeClientHidden.value = this.checked ? '1' : '0';
                
                // Efeito visual no card quando checkbox é alterado
                const card = clientSection.querySelector('.card');
                if (this.checked) {
                    card.style.borderColor = '#0d6efd';
                    card.style.backgroundColor = '#f8f9ff';
                } else {
                    card.style.borderColor = '#dee2e6';
                    card.style.backgroundColor = '#f8f9fa';
                }
            });
        } else {
            // Esconder seção do cliente
            clientSection.style.display = 'none';
            clientIdHidden.value = '';
            includeClientHidden.value = '0';
        }
    };

    // Função para limpar informações do cliente no modal
    window.clearClientInfoInModal = function() {
        window.updateClientInfoInModal(null);
    };

    // Sistema de autocomplete para clientes
    let currentClientSuggestionIndex = -1;
    let clientSuggestions = [];
    let clientSuggestionTimeout = null;

    // Função para filtrar clientes
    function filterClients(searchTerm) {
        console.log('=== filterClients CHAMADA ===');
        console.log('Termo de busca:', searchTerm);
        console.log('window.clientesDisponiveis:', window.clientesDisponiveis);
        
        if (!searchTerm) {
            // Se não há termo de busca, mostrar os primeiros 8 clientes
            const result = window.clientesDisponiveis.slice(0, 8);
            console.log('Sem termo - retornando primeiros 8:', result.map(c => c.nome));
            return result;
        }
        
        if (searchTerm.length < 2) {
            console.log('Termo muito curto - retornando array vazio');
            return [];
        }

        const term = searchTerm.toLowerCase();
        const termNumbers = term.replace(/[^0-9]/g, '');
        
        const filtered = window.clientesDisponiveis.filter(cliente => {
            const nome = cliente.nome.toLowerCase();
            const cpf = cliente.cpf.replace(/[^0-9]/g, '');
            const telefone = cliente.telefone ? cliente.telefone.replace(/[^0-9]/g, '') : '';
            
            return nome.includes(term) ||
                   cpf.includes(termNumbers) ||
                   telefone.includes(termNumbers);
        }).slice(0, 8); // Limitar a 8 sugestões
        
        console.log('Clientes filtrados:', filtered.map(c => c.nome));
        return filtered;
    }

    // Função para mostrar sugestões de clientes
    function showClientSuggestions(suggestions) {
        console.log('=== showClientSuggestions CHAMADA ===');
        console.log('Número de sugestões:', suggestions.length);
        
        const suggestionsContainer = document.getElementById('client_suggestions');
        const suggestionsList = document.getElementById('client_suggestions_list');
        
        console.log('suggestionsContainer encontrado?', !!suggestionsContainer);
        console.log('suggestionsList encontrado?', !!suggestionsList);
        
        if (suggestions.length === 0) {
            console.log('Nenhuma sugestão - escondendo container');
            suggestionsContainer.style.display = 'none';
            return;
        }

        console.log('Limpando lista e criando sugestões...');
        suggestionsList.innerHTML = '';
        clientSuggestions = suggestions;
        currentClientSuggestionIndex = -1;

        suggestions.forEach((cliente, index) => {
            const li = document.createElement('li');
            li.className = 'client-suggestion-item';
            li.dataset.index = index;
            
            // Pegar iniciais do nome para o ícone
            const iniciais = cliente.nome.split(' ')
                .map(word => word.charAt(0))
                .slice(0, 2)
                .join('')
                .toUpperCase();

            li.innerHTML = `
                <div class="client-suggestion-icon">
                    ${iniciais}
                </div>
                <div class="client-suggestion-info">
                    <div class="client-suggestion-name">${cliente.nome}</div>
                    <div class="client-suggestion-details">
                        <span>CPF: ${cliente.cpf}</span>
                        ${cliente.telefone ? `<span>Tel: ${cliente.telefone}</span>` : ''}
                    </div>
                </div>
                <div class="client-suggestion-status">
                    <i class="fas fa-user-check"></i>
                </div>
            `;

            li.addEventListener('click', () => selectClient(cliente));
            suggestionsList.appendChild(li);
        });

        console.log('Exibindo container de sugestões...');
        suggestionsContainer.style.display = 'block';
        console.log('Container exibido - display:', suggestionsContainer.style.display);
    }

    // Função para esconder sugestões de clientes
    function hideClientSuggestions() {
        setTimeout(() => {
            document.getElementById('client_suggestions').style.display = 'none';
            currentClientSuggestionIndex = -1;
        }, 150);
    }

    // Função para destacar sugestão de cliente
    function highlightClientSuggestion(index) {
        const items = document.querySelectorAll('.client-suggestion-item');
        
        // Remove destaque anterior
        items.forEach(item => item.classList.remove('highlighted'));
        
        if (index >= 0 && index < items.length) {
            items[index].classList.add('highlighted');
            currentClientSuggestionIndex = index;
            
            // Scroll para o item visível
            items[index].scrollIntoView({ block: 'nearest' });
        }
    }

    // Função para selecionar cliente
    function selectClient(cliente) {
        console.log('Cliente selecionado:', cliente.nome);
        
        // Atualizar campo de busca
        document.getElementById('client_search').value = cliente.nome;
        document.getElementById('selected_client_id').value = cliente.id;
        
        // Mostrar informações do cliente selecionado
        const selectedInfo = document.getElementById('selected_client_info');
        if (selectedInfo) {
            selectedInfo.innerHTML = `
                <div class="selection-container">
                    <i class="fas fa-user-check selection-icon"></i>
                    <div class="selection-info">
                        <div class="selection-name">${cliente.nome}</div>
                        <div class="selection-details">CPF: ${cliente.cpf}</div>
                    </div>
                    <button type="button" class="clear-selection-btn" onclick="clearClientSelection()" title="Limpar seleção">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            selectedInfo.style.display = 'block';
            selectedInfo.classList.add('show');
        }
        
        // Esconder sugestões
        document.getElementById('client_suggestions').style.display = 'none';
        
        // Chamar função legada se existir (compatibilidade)
        if (typeof window.exibirCliente === 'function') {
            window.exibirCliente(cliente);
        } else if (typeof exibirCliente === 'function') {
            exibirCliente(cliente);
        }
    }

    // Sistema de autocomplete para produtos
    let currentSuggestionIndex = -1;
    let productSuggestions = [];
    let suggestionTimeout = null;

    // Função para filtrar produtos
    function filterProducts(searchTerm) {
        if (!searchTerm || searchTerm.length < 2) {
            return [];
        }

        // Verificar se produtos estão disponíveis
        if (!window.produtosDisponiveis || !Array.isArray(window.produtosDisponiveis)) {
            console.error('window.produtosDisponiveis não está definido ou não é um array');
            return [];
        }

        const term = searchTerm.toLowerCase();
        return window.produtosDisponiveis.filter(produto => {
            return produto.nome.toLowerCase().includes(term) ||
                   produto.codigo.toLowerCase().includes(term);
        }).slice(0, 8); // Limitar a 8 sugestões
    }

    // Função para mostrar sugestões
    function showProductSuggestions(suggestions) {
        const suggestionsContainer = document.getElementById('product_suggestions');
        const suggestionsList = document.getElementById('suggestions_list');
        
        if (suggestions.length === 0) {
            suggestionsContainer.style.display = 'none';
            return;
        }

        suggestionsList.innerHTML = '';
        productSuggestions = suggestions;
        currentSuggestionIndex = -1;

        suggestions.forEach((produto, index) => {
            const li = document.createElement('li');
            li.className = 'suggestion-item';
            li.dataset.index = index;
            
            const imagePath = produto.imagem && produto.imagem !== 'produto-sem-imagem.webp' 
                ? `../assets/img/products/${produto.imagem}`
                : '../assets/img/products/produto-sem-imagem.webp';

            li.innerHTML = `
                <img src="${imagePath}" alt="${produto.nome}" class="suggestion-product-image" 
                     onerror="this.src='../assets/img/products/produto-sem-imagem.webp'">
                <div class="suggestion-product-info">
                    <div class="suggestion-product-name">${produto.nome}</div>
                    <div class="suggestion-product-details">
                        <span>Cód: ${produto.codigo}</span>
                        <span>Estoque: ${produto.estoque}</span>
                    </div>
                </div>
                <div class="suggestion-product-price">R$ ${parseFloat(produto.preco || 0).toFixed(2).replace('.', ',')}</div>
            `;

            li.addEventListener('click', () => selectProduct(produto));
            suggestionsList.appendChild(li);
        });

        suggestionsContainer.style.display = 'block';
    }

    // Função para esconder sugestões
    function hideSuggestions() {
        setTimeout(() => {
            document.getElementById('product_suggestions').style.display = 'none';
            currentSuggestionIndex = -1;
        }, 150);
    }

    // Função para destacar sugestão
    function highlightSuggestion(index) {
        const items = document.querySelectorAll('.suggestion-item');
        
        // Remove destaque anterior
        items.forEach(item => item.classList.remove('highlighted'));
        
        if (index >= 0 && index < items.length) {
            items[index].classList.add('highlighted');
            currentSuggestionIndex = index;
            
            // Scroll para o item visível
            items[index].scrollIntoView({ block: 'nearest' });
        }
    }

    // Função para selecionar produto
    function selectProduct(produto) {
        console.log('Produto selecionado:', produto.nome);
        
        // Atualizar campo de busca
        document.getElementById('product_search').value = produto.nome;
        document.getElementById('selected_product_id').value = produto.id;
        
        // Mostrar informações do produto selecionado
        const selectedInfo = document.getElementById('selected_product_info');
        if (selectedInfo) {
            selectedInfo.innerHTML = `
                <div class="selection-container">
                    <i class="fas fa-check-circle selection-icon"></i>
                    <div class="selection-info">
                        <div class="selection-name">${produto.nome}</div>
                        <div class="selection-details">Código: ${produto.codigo}</div>
                    </div>
                    <button type="button" class="clear-selection-btn" onclick="clearProductSelection()" title="Limpar seleção">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            selectedInfo.style.display = 'block';
            selectedInfo.classList.add('show');
        }
        
        // Esconder sugestões
        document.getElementById('product_suggestions').style.display = 'none';
        currentSuggestionIndex = -1;
        
        // Chamar função legada se existir (compatibilidade)
        if (typeof window.exibirProduto === 'function') {
            window.exibirProduto(produto);
        } else if (typeof exibirProduto === 'function') {
            exibirProduto(produto);
        }
    }

    // Event listeners para o campo de busca de produto
    document.addEventListener('DOMContentLoaded', function() {
        const productSearchInput = document.getElementById('product_search');
        const clientSearchInput = document.getElementById('client_search');
        
        // Implementar teclas de atalho para navegação rápida
        document.addEventListener('keydown', function(e) {
            // Verificar se não estamos em um campo de input/textarea para evitar conflitos
            const activeElement = document.activeElement;
            const isInputActive = activeElement.tagName === 'INPUT' || activeElement.tagName === 'TEXTAREA' || activeElement.tagName === 'SELECT';
            
            // Função para destacar dica de atalho
            function highlightShortcutHint(key) {
                // Encontrar e destacar a dica correspondente
                const hints = document.querySelectorAll('.shortcut-hint');
                hints.forEach(hint => {
                    if (hint.textContent.includes(key)) {
                        hint.classList.add('pulse');
                        setTimeout(() => {
                            hint.classList.remove('pulse');
                        }, 1500);
                    }
                });
            }
            
            // F5 - Focus no campo de cliente
            if (e.key === 'F5') {
                e.preventDefault();
                const clientInput = document.getElementById('client_search');
                if (clientInput && !clientInput.disabled) {
                    clientInput.focus();
                    clientInput.select();
                    
                    // Mostrar feedback visual
                    showKeyboardShortcutFeedback('F5', 'Cliente');
                    highlightShortcutHint('F5');
                }
                return;
            }
            
            // F6 - Focus no campo de produto
            if (e.key === 'F6') {
                e.preventDefault();
                const productInput = document.getElementById('product_search');
                if (productInput && !productInput.disabled) {
                    productInput.focus();
                    productInput.select();
                    
                    // Mostrar feedback visual
                    showKeyboardShortcutFeedback('F6', 'Produto');
                    highlightShortcutHint('F6');
                }
                return;
            }
            
            // F7 - Focus no campo de quantidade
            if (e.key === 'F7') {
                e.preventDefault();
                const quantityInput = document.getElementById('product_quantity');
                if (quantityInput && !quantityInput.disabled) {
                    quantityInput.focus();
                    quantityInput.select();
                    
                    // Mostrar feedback visual
                    showKeyboardShortcutFeedback('F7', 'Quantidade');
                    highlightShortcutHint('F7');
                }
                return;
            }
            
            // F8 - Abrir/Fechar Caixa ou Confirmar em Modal
            if (e.key === 'F8') {
                e.preventDefault();
                
                // Verificar se há um modal aberto
                const openModal = document.querySelector('.modal.show');
                if (openModal) {
                    // Se há modal aberto, procurar botão de confirmação
                    const confirmBtn = openModal.querySelector('.btn-primary, .btn-success, [data-confirm], button[type="submit"]');
                    if (confirmBtn && !confirmBtn.disabled) {
                        confirmBtn.click();
                        showKeyboardShortcutFeedback('F8', 'Confirmado!');
                        return;
                    }
                }
                
                // Se não há modal aberto, controlar caixa
                const btnOpenCash = document.getElementById('btnOpenCash');
                const btnCloseCash = document.getElementById('btnCloseCash');
                
                if (btnOpenCash && btnOpenCash.style.display !== 'none') {
                    // Caixa está fechado, abrir
                    btnOpenCash.click();
                    showKeyboardShortcutFeedback('F8', 'Abrir Caixa');
                } else if (btnCloseCash && btnCloseCash.style.display !== 'none') {
                    // Caixa está aberto, fechar
                    btnCloseCash.click();
                    showKeyboardShortcutFeedback('F8', 'Fechar Caixa');
                }
                
                highlightShortcutHint('F8');
                return;
            }
            
            // TAB - Navegação sequencial entre campos principais
            if (e.key === 'Tab' && !e.shiftKey && !isInputActive) {
                e.preventDefault();
                navigateToNextField();
                return;
            }
            
            // SHIFT+TAB - Navegação reversa entre campos principais
            if (e.key === 'Tab' && e.shiftKey && !isInputActive) {
                e.preventDefault();
                navigateToPreviousField();
                return;
            }
        });
        
        // Função para mostrar feedback visual dos atalhos
        function showKeyboardShortcutFeedback(key, action) {
            // Remover feedback anterior se existir
            const existingFeedback = document.querySelector('.keyboard-shortcut-feedback');
            if (existingFeedback) {
                existingFeedback.remove();
            }
            
            // Criar elemento de feedback
            const feedback = document.createElement('div');
            feedback.className = 'keyboard-shortcut-feedback';
            feedback.innerHTML = `
                <div class="shortcut-key">${key}</div>
                <div class="shortcut-action">${action}</div>
            `;
            
            // Adicionar estilos inline para o feedback
            feedback.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 12px 20px;
                border-radius: 8px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.3);
                z-index: 9999;
                display: flex;
                align-items: center;
                gap: 10px;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                font-weight: 600;
                transform: translateX(100%);
                transition: all 0.3s ease;
            `;
            
            // Estilos para os elementos internos
            const keyElement = feedback.querySelector('.shortcut-key');
            keyElement.style.cssText = `
                background: rgba(255,255,255,0.2);
                padding: 4px 8px;
                border-radius: 4px;
                font-size: 14px;
                font-weight: bold;
            `;
            
            const actionElement = feedback.querySelector('.shortcut-action');
            actionElement.style.cssText = `
                font-size: 14px;
            `;
            
            document.body.appendChild(feedback);
            
            // Animar entrada
            setTimeout(() => {
                feedback.style.transform = 'translateX(0)';
            }, 10);
            
            // Remover após 2 segundos
            setTimeout(() => {
                feedback.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    if (feedback.parentNode) {
                        feedback.remove();
                    }
                }, 300);
            }, 2000);
        }
        
        // Navegação sequencial entre campos
        let currentFieldIndex = 0;
        const navigationFields = [
            'client_search',
            'product_search', 
            'product_quantity'
        ];
        
        function navigateToNextField() {
            currentFieldIndex = (currentFieldIndex + 1) % navigationFields.length;
            focusField(navigationFields[currentFieldIndex]);
        }
        
        function navigateToPreviousField() {
            currentFieldIndex = currentFieldIndex <= 0 ? navigationFields.length - 1 : currentFieldIndex - 1;
            focusField(navigationFields[currentFieldIndex]);
        }
        
        function focusField(fieldId) {
            const field = document.getElementById(fieldId);
            if (field && !field.disabled) {
                field.focus();
                field.select();
                
                // Atualizar índice baseado no campo atual
                currentFieldIndex = navigationFields.indexOf(fieldId);
            }
        }
        
        // Event listeners para busca de cliente
        if (clientSearchInput) {
            // SISTEMA SIMPLES E FUNCIONAL DE AUTOCOMPLETE
            let searchTimeout;
            let currentSuggestionIndex = -1;
            let suggestions = [];
            
            // Evento principal de input
            clientSearchInput.addEventListener('input', function(e) {
                const searchValue = e.target.value.trim();
                console.log('Digitando:', searchValue);
                
                // Limpar timeout anterior
                clearTimeout(searchTimeout);
                
                // Limpar seleção anterior
                document.getElementById('selected_client_id').value = '';
                const selectedInfo = document.getElementById('selected_client_info');
                if (selectedInfo) {
                    selectedInfo.classList.remove('show');
                    selectedInfo.style.display = 'none';
                }
                
                // Reset da navegação por teclado
                currentSuggestionIndex = -1;
                
                // Buscar com delay mínimo
                searchTimeout = setTimeout(() => {
                    updateClientSuggestions(searchValue);
                }, 100);
            });
            
            // Navegação por teclado
            clientSearchInput.addEventListener('keydown', function(e) {
                const container = document.getElementById('client_suggestions');
                const isVisible = container.style.display !== 'none';
                
                if (!isVisible || suggestions.length === 0) return;
                
                switch(e.key) {
                    case 'ArrowDown':
                        e.preventDefault();
                        currentSuggestionIndex = currentSuggestionIndex < suggestions.length - 1 
                            ? currentSuggestionIndex + 1 
                            : 0; // Volta para o primeiro
                        highlightSuggestion(currentSuggestionIndex);
                        break;
                        
                    case 'ArrowUp':
                        e.preventDefault();
                        currentSuggestionIndex = currentSuggestionIndex > 0 
                            ? currentSuggestionIndex - 1 
                            : suggestions.length - 1; // Vai para o último
                        highlightSuggestion(currentSuggestionIndex);
                        break;
                        
                    case 'Enter':
                        e.preventDefault();
                        if (currentSuggestionIndex >= 0 && currentSuggestionIndex < suggestions.length) {
                            selectClient(suggestions[currentSuggestionIndex]);
                        }
                        break;
                        
                    case 'Escape':
                        e.preventDefault();
                        container.style.display = 'none';
                        currentSuggestionIndex = -1;
                        break;
                }
            });
            
            // Função para destacar sugestão
            function highlightSuggestion(index) {
                const items = document.querySelectorAll('.client-suggestion-item');
                
                // Remove destaque anterior
                items.forEach((item, i) => {
                    if (i === index) {
                        item.style.backgroundColor = '#007bff';
                        item.style.color = 'white';
                        item.querySelector('div[style*="background"]').style.background = 'rgba(255,255,255,0.2)';
                        // Scroll para o item visível
                        item.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
                    } else {
                        item.style.backgroundColor = 'transparent';
                        item.style.color = '#333';
                        const avatar = item.querySelector('div[style*="background"]');
                        if (avatar) {
                            avatar.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
                        }
                    }
                });
            }
            
            // Função para atualizar sugestões
            function updateClientSuggestions(searchTerm) {
                console.log('Atualizando sugestões para:', searchTerm);
                
                const container = document.getElementById('client_suggestions');
                const list = document.getElementById('client_suggestions_list');
                
                if (!container || !list) {
                    console.error('Elementos de sugestão não encontrados');
                    return;
                }
                
                // Reset da navegação
                currentSuggestionIndex = -1;
                
                // Filtrar clientes
                if (!searchTerm || searchTerm.length === 0) {
                    // Mostrar todos os clientes se não há busca
                    suggestions = window.clientesDisponiveis.slice(0, 6);
                } else if (searchTerm.length >= 1) {
                    // Filtrar por nome a partir do primeiro caractere
                    const term = searchTerm.toLowerCase();
                    suggestions = window.clientesDisponiveis.filter(cliente => 
                        cliente.nome.toLowerCase().includes(term) ||
                        cliente.cpf.includes(term)
                    ).slice(0, 6);
                }
                
                console.log('Sugestões encontradas:', suggestions.length);
                
                // Limpar lista
                list.innerHTML = '';
                
                if (suggestions.length === 0) {
                    container.style.display = 'none';
                    return;
                }
                
                // Criar itens da lista
                suggestions.forEach((cliente, index) => {
                    const li = document.createElement('li');
                    li.className = 'client-suggestion-item';
                    li.style.cssText = `
                        padding: 12px;
                        border-bottom: 1px solid #eee;
                        cursor: pointer;
                        display: flex;
                        align-items: center;
                        gap: 10px;
                        transition: all 0.2s ease;
                    `;
                    
                    // Iniciais do cliente
                    const initials = cliente.nome.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
                    
                    li.innerHTML = `
                        <div style="
                            width: 40px;
                            height: 40px;
                            border-radius: 50%;
                            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            color: white;
                            font-weight: bold;
                            font-size: 14px;
                            transition: all 0.2s ease;
                        ">${initials}</div>
                        <div style="flex: 1;">
                            <div style="font-weight: 500; color: #333;">${cliente.nome}</div>
                            <div style="font-size: 12px; color: #666;">CPF: ${cliente.cpf}</div>
                        </div>
                        <div style="color: #28a745;">
                            <i class="fas fa-check"></i>
                        </div>
                    `;
                    
                    // Hover effect
                    li.addEventListener('mouseenter', () => {
                        // Só aplicar hover se não estiver selecionado por teclado
                        if (currentSuggestionIndex !== index) {
                            li.style.backgroundColor = '#f8f9fa';
                            currentSuggestionIndex = index;
                        }
                    });
                    
                    li.addEventListener('mouseleave', () => {
                        // Só remover hover se não estiver selecionado por teclado
                        if (currentSuggestionIndex === index) {
                            li.style.backgroundColor = 'transparent';
                            currentSuggestionIndex = -1;
                        }
                    });
                    
                    // Click para selecionar
                    li.addEventListener('click', () => {
                        selectClient(cliente);
                        container.style.display = 'none';
                    });
                    
                    list.appendChild(li);
                });
                
                // Mostrar container
                container.style.display = 'block';
                console.log('Container exibido com', suggestions.length, 'sugestões');
            }
            
            // Esconder ao clicar fora
            document.addEventListener('click', (e) => {
                if (!e.target.closest('.client-search-container')) {
                    document.getElementById('client_suggestions').style.display = 'none';
                    currentSuggestionIndex = -1;
                }
            });
            
            // Mostrar sugestões ao focar no campo
            clientSearchInput.addEventListener('focus', () => {
                updateClientSuggestions(clientSearchInput.value);
            });
            
            // Adicionar dica visual sobre navegação por teclado
            const clientContainer = document.querySelector('.client-search-container');
            if (clientContainer && !clientContainer.querySelector('.keyboard-hint')) {
                const hint = document.createElement('div');
                hint.className = 'keyboard-hint';
                hint.style.cssText = `
                    font-size: 11px;
                    color: #666;
                    margin-top: 4px;
                    display: none;
                `;
                hint.innerHTML = '<i class="fas fa-keyboard"></i> Use ↑↓ para navegar, Enter para selecionar, Esc para fechar';
                clientContainer.appendChild(hint);
                
                // Mostrar dica quando há sugestões
                const observer = new MutationObserver(() => {
                    const suggestionsVisible = document.getElementById('client_suggestions').style.display !== 'none';
                    hint.style.display = suggestionsVisible ? 'block' : 'none';
                });
                
                observer.observe(document.getElementById('client_suggestions'), {
                    attributes: true,
                    attributeFilter: ['style']
                });
            }
        }
        
        // Event listeners para busca de produto
        if (productSearchInput) {
            // SISTEMA FUNCIONAL DE AUTOCOMPLETE - IGUAL AO DE CLIENTES
            let productSearchTimeout;
            let currentProductIndex = -1;
            let productSuggestions = [];
            
            // Evento principal de input
            productSearchInput.addEventListener('input', function(e) {
                const searchValue = e.target.value.trim();
                console.log('Buscando produto:', searchValue);
                
                // Limpar timeout anterior
                clearTimeout(productSearchTimeout);
                
                // Limpar seleção anterior
                document.getElementById('selected_product_id').value = '';
                const selectedInfo = document.getElementById('selected_product_info');
                if (selectedInfo) {
                    selectedInfo.classList.remove('show');
                    selectedInfo.style.display = 'none';
                }
                
                // Reset da navegação por teclado
                currentProductIndex = -1;
                
                // Buscar com delay mínimo
                productSearchTimeout = setTimeout(() => {
                    updateProductSuggestions(searchValue);
                }, 100);
            });
            
            // Navegação por teclado
            productSearchInput.addEventListener('keydown', function(e) {
                const container = document.getElementById('product_suggestions');
                const isVisible = container.style.display !== 'none';
                
                if (!isVisible || productSuggestions.length === 0) return;
                
                switch(e.key) {
                    case 'ArrowDown':
                        e.preventDefault();
                        currentProductIndex = currentProductIndex < productSuggestions.length - 1 
                            ? currentProductIndex + 1 
                            : 0; // Volta para o primeiro
                        highlightProductSuggestion(currentProductIndex);
                        break;
                        
                    case 'ArrowUp':
                        e.preventDefault();
                        currentProductIndex = currentProductIndex > 0 
                            ? currentProductIndex - 1 
                            : productSuggestions.length - 1; // Vai para o último
                        highlightProductSuggestion(currentProductIndex);
                        break;
                        
                    case 'Enter':
                        e.preventDefault();
                        if (currentProductIndex >= 0 && currentProductIndex < productSuggestions.length) {
                            selectProduct(productSuggestions[currentProductIndex]);
                        }
                        break;
                        
                    case 'Escape':
                        e.preventDefault();
                        container.style.display = 'none';
                        currentProductIndex = -1;
                        break;
                }
            });
            
            // Função para destacar sugestão de produto
            function highlightProductSuggestion(index) {
                const items = document.querySelectorAll('.suggestion-item');
                
                // Remove destaque anterior
                items.forEach((item, i) => {
                    if (i === index) {
                        item.style.backgroundColor = '#007bff';
                        item.style.color = 'white';
                        // Scroll para o item visível
                        item.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
                    } else {
                        item.style.backgroundColor = 'transparent';
                        item.style.color = '#333';
                    }
                });
            }
            
            // Função para atualizar sugestões de produtos
            function updateProductSuggestions(searchTerm) {
                console.log('Atualizando sugestões de produtos para:', searchTerm);
                
                const container = document.getElementById('product_suggestions');
                const list = document.getElementById('suggestions_list');
                
                if (!container || !list) {
                    console.error('Elementos de sugestão de produtos não encontrados');
                    return;
                }
                
                // Reset da navegação
                currentProductIndex = -1;
                
                // DETECTAR TIPO DE BUSCA
                // Código de barras: apenas números, exatamente 8+ dígitos
                const isBarcode = /^\d{8,}$/.test(searchTerm) && searchTerm.length >= 8;
                // Busca por nome: contém letras, números mistos, ou menos de 8 dígitos
                const isNameSearch = !isBarcode && searchTerm.length > 0;
                
                console.log('Tipo de busca:', isBarcode ? 'Código de barras' : 'Nome do produto');
                console.log('Termo:', searchTerm, 'Tamanho:', searchTerm.length);
                
                if (isBarcode) {
                    // CÓDIGO DE BARRAS: Auto-selecionar quando encontrar produto (8+ dígitos)
                    console.log('Código de barras detectado (8+ dígitos):', searchTerm);
                    const exactProduct = window.produtosDisponiveis.find(produto => 
                        produto.codigo === searchTerm
                    );
                    
                    if (exactProduct) {
                        // Auto-selecionar produto por código de barras
                        console.log('Produto encontrado por código, auto-selecionando...');
                        selectProduct(exactProduct);
                        container.style.display = 'none';
                        return;
                    }
                    
                    // Se não encontrou produto exato por código, não mostrar sugestões
                    productSuggestions = [];
                } else if (isNameSearch) {
                    // BUSCA POR NOME: Mostrar sugestões a partir de 4 caracteres (NÃO auto-selecionar)
                    if (searchTerm.length >= 4) {
                        const term = searchTerm.toLowerCase();
                        productSuggestions = window.produtosDisponiveis.filter(produto => 
                            produto.nome.toLowerCase().includes(term) ||
                            produto.codigo.toLowerCase().includes(term)
                        ).slice(0, 6);
                        console.log('Busca por nome - mostrando', productSuggestions.length, 'sugestões');
                    } else {
                        // Menos de 4 caracteres para nome: não mostrar sugestões
                        productSuggestions = [];
                        console.log('Busca por nome - menos de 4 caracteres, não mostrando sugestões');
                    }
                } else {
                    // Campo vazio
                    productSuggestions = [];
                }
                
                // Limpar lista
                list.innerHTML = '';
                list.innerHTML = '';
                
                if (productSuggestions.length === 0) {
                    container.style.display = 'none';
                    return;
                }
                
                // Criar itens da lista
                productSuggestions.forEach((produto, index) => {
                    const li = document.createElement('li');
                    li.className = 'suggestion-item';
                    li.style.cssText = `
                        padding: 12px;
                        border-bottom: 1px solid #eee;
                        cursor: pointer;
                        display: flex;
                        align-items: center;
                        gap: 12px;
                        transition: all 0.2s ease;
                    `;
                    
                    // Imagem do produto
                    const imagePath = produto.imagem && produto.imagem !== 'produto-sem-imagem.webp' 
                        ? `../assets/img/products/${produto.imagem}`
                        : '../assets/img/products/produto-sem-imagem.webp';
                    
                    li.innerHTML = `
                        <img src="${imagePath}" alt="${produto.nome}" 
                             style="width: 45px; height: 45px; border-radius: 6px; object-fit: cover; border: 1px solid #ddd;"
                             onerror="this.src='../assets/img/products/produto-sem-imagem.webp'">
                        <div style="flex: 1;">
                            <div style="font-weight: 500; color: #333; margin-bottom: 2px;">${produto.nome}</div>
                            <div style="font-size: 12px; color: #666;">
                                <span>Cód: ${produto.codigo}</span> • 
                                <span>Estoque: ${produto.estoque || 0}</span>
                            </div>
                        </div>
                        <div style="font-weight: bold; color: #28a745; font-size: 14px;">
                            R$ ${parseFloat(produto.preco || 0).toFixed(2).replace('.', ',')}
                        </div>
                    `;
                    
                    // Hover effect
                    li.addEventListener('mouseenter', () => {
                        if (currentProductIndex !== index) {
                            li.style.backgroundColor = '#f8f9fa';
                            currentProductIndex = index;
                        }
                    });
                    
                    li.addEventListener('mouseleave', () => {
                        if (currentProductIndex === index) {
                            li.style.backgroundColor = 'transparent';
                            currentProductIndex = -1;
                        }
                    });
                    
                    // Click para selecionar
                    li.addEventListener('click', () => {
                        selectProduct(produto);
                        container.style.display = 'none';
                    });
                    
                    list.appendChild(li);
                });
                
                // Mostrar container
                container.style.display = 'block';
                console.log('Container de produtos exibido com', productSuggestions.length, 'sugestões');
            }
            
            // Esconder ao clicar fora
            document.addEventListener('click', (e) => {
                if (!e.target.closest('.product-search-container')) {
                    document.getElementById('product_suggestions').style.display = 'none';
                    currentProductIndex = -1;
                }
            });
            
            // Mostrar sugestões ao focar no campo
            productSearchInput.addEventListener('focus', () => {
                if (productSearchInput.value.length >= 2) {
                    updateProductSuggestions(productSearchInput.value);
                }
            });
            
            // Adicionar dica visual sobre navegação por teclado
            const productContainer = document.querySelector('.product-search-container');
            if (productContainer && !productContainer.querySelector('.keyboard-hint')) {
                const hint = document.createElement('div');
                hint.className = 'keyboard-hint';
                hint.style.cssText = `
                    font-size: 11px;
                    color: #666;
                    margin-top: 4px;
                    display: none;
                `;
                hint.innerHTML = '<i class="fas fa-keyboard"></i> Use ↑↓ para navegar, Enter para selecionar • Só códigos 8+ dígitos auto-selecionam';
                productContainer.appendChild(hint);
                
                // Mostrar dica quando há sugestões
                const observer = new MutationObserver(() => {
                    const suggestionsVisible = document.getElementById('product_suggestions').style.display !== 'none';
                    hint.style.display = suggestionsVisible ? 'block' : 'none';
                });
                
                observer.observe(document.getElementById('product_suggestions'), {
                    attributes: true,
                    attributeFilter: ['style']
                });
            }
        }
    });
    
    // Função para acionar ação do F8 via botão
    function triggerF8Action() {
        // Simular pressionar F8
        const event = new KeyboardEvent('keydown', { key: 'F8' });
        document.dispatchEvent(event);
    }
    
    // Função global para ocultar container de seleção de produto
    window.hideProductSelectionContainer = function() {
        const selectedProductInfo = document.getElementById('selected_product_info');
        if (selectedProductInfo) {
            selectedProductInfo.classList.remove('show');
            setTimeout(() => {
                selectedProductInfo.style.display = 'none';
            }, 300);
        }
    };
    
    // Função global para ocultar container de seleção de cliente
    window.hideClientSelectionContainer = function() {
        const selectedClientInfo = document.getElementById('selected_client_info');
        if (selectedClientInfo) {
            selectedClientInfo.classList.remove('show');
            setTimeout(() => {
                selectedClientInfo.style.display = 'none';
            }, 300);
        }
    };
    </script>

    <script src="../js/_component/validation.js"></script>
    <script src="../js/_component/mask.js"></script>
    <script src="../js/brazilian-money-mask.js"></script>
    <script src="../js/sales.js"></script>
    <script src="../js/atalhoteclado.js"></script>
</body>
</html>