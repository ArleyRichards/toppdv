<?= $this->extend('templates/pdv') ?>

<?= $this->section('content') ?>
<style>
    /* Estilos específicos do PDV - Interface Profissional */
    .pdv-container {
        height: 100vh;
        width: 100vw;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 1rem;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .pdv-header {
    background: rgba(255, 255, 255, 0.12);
    color: white;
    padding: 0.5rem 1rem; /* reduzir padding para menor altura */
    border-radius: 10px;
    margin-bottom: 0.75rem;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.15);
    flex-shrink: 0;
    min-height: 48px; /* altura reduzida e consistente */
    }

    .pdv-main-content {
        flex: 1;
        display: flex;
        gap: 1rem;
        overflow: hidden;
        min-height: 0; /* permitir que colunas encolham */
    }

    .pdv-left-panel {
        flex: 0 0 40%;
        display: flex;
        flex-direction: column;
        gap: 1rem;
        overflow: hidden;
        height: 100%;
        min-height: 0; /* permitir shrink */
    }

    /* Right panel: use grid so cart fills available space and total sits below without overlap */
    .pdv-right-panel {
        flex: 1;
        display: grid;
        grid-template-rows: 1fr auto;
        gap: 1rem;
        overflow: hidden; /* grid children will scroll individually */
        height: 100%;
        min-height: 0;
        /* padding: 1rem; */
    }

    /* Ajustar container principal para dar espaço ao total fixo */

    .pdv-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(20px);
        display: flex;
        flex-direction: column;
        overflow: hidden;
    transition: all 0.3s ease;
    /* let cards size according to their content by default; specific panels can fill when needed */
    height: auto;
    min-height: 0;
    }

    .pdv-card:hover {
        box-shadow: 0 12px 48px rgba(0,0,0,0.15);
        transform: translateY(-2px);
    }

    .pdv-card .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 16px 16px 0 0;
        font-weight: 600;
        padding: 1rem 1.5rem;
        font-size: 1rem;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .pdv-card .card-header i {
        font-size: 1.2rem;
    }

    .pdv-card .card-body {
        flex: 1;
        overflow: auto;
        padding: 1.5rem;
        min-height: 0;
    }

    .pdv-card.flex-content .card-body {
        display: flex;
        flex-direction: column;
        min-height: 120px;
    }

    /* Estilos específicos para o card de cliente */
    .pdv-card.flex-content .card-body .row {
        flex-shrink: 0;
    }

    .pdv-card.flex-content .card-body #clienteSelecionado {
        flex: 1;
        margin-top: 1rem;
    }

    .produto-item {
        border: 2px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .produto-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }

    .produto-item:hover::before {
        left: 100%;
    }

    .produto-item:hover {
        border-color: #28a745;
        box-shadow: 0 8px 32px rgba(40, 167, 69, 0.3);
        transform: translateY(-3px) scale(1.02);
        background: linear-gradient(135deg, rgba(40, 167, 69, 0.1), rgba(40, 167, 69, 0.05));
    }

    .produto-selecionado {
        border-color: #28a745 !important;
        background: linear-gradient(135deg, rgba(40, 167, 69, 0.2), rgba(40, 167, 69, 0.1)) !important;
        box-shadow: 0 8px 32px rgba(40, 167, 69, 0.4) !important;
    }

    .produto-item.selected {
        border-color: #667eea !important;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.2), rgba(118, 75, 162, 0.1)) !important;
        box-shadow: 0 8px 32px rgba(102, 126, 234, 0.4) !important;
        transform: translateY(-3px) scale(1.02);
    }

    .produto-info-container {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .produto-image {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        overflow: hidden;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .produto-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .produto-placeholder {
        background: linear-gradient(135deg, #667eea, #764ba2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
    }

    .produto-details {
        flex: 1;
        min-width: 0;
    }

    .produto-nome {
        font-weight: 600;
        font-size: 1.1rem;
        color: #2c3e50;
        margin-bottom: 0.25rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .produto-codigo {
        font-size: 0.85rem;
        color: #7f8c8d;
        margin-bottom: 0.5rem;
    }

    .produto-preco {
        font-weight: 700;
        font-size: 1.3rem;
        color: #27ae60;
        margin-bottom: 0.25rem;
    }

    .produto-estoque {
        font-size: 0.85rem;
        color: #95a5a6;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .estoque-badge {
        background: linear-gradient(135deg, #3498db, #2980b9);
        color: white;
        padding: 0.2rem 0.5rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .total-display {
        background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
        color: white;
        padding: 2rem;
        border-radius: 20px;
        text-align: center;
        margin-bottom: 1rem;
        box-shadow: 0 12px 40px rgba(39, 174, 96, 0.3);
        flex-shrink: 0;
        position: relative;
        overflow: hidden;
    }

    .total-display::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: conic-gradient(from 0deg, transparent, rgba(255,255,255,0.1), transparent);
        animation: rotate 3s linear infinite;
    }

    @keyframes rotate {
        to {
            transform: rotate(360deg);
        }
    }

    .total-label {
        font-size: 1.1rem;
        font-weight: 500;
        opacity: 0.9;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 1;
    }

    .total-value {
        font-size: 2.5rem;
        font-weight: 700;
        position: relative;
        z-index: 1;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .btn-pdv {
        border-radius: 12px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
        font-size: 1rem;
        border: none;
        position: relative;
        overflow: hidden;
    }

    .btn-pdv::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: width 0.3s, height 0.3s;
    }

    .btn-pdv:hover::before {
        width: 300px;
        height: 300px;
    }

    .btn-pdv:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.2);
    }

    .btn-pdv.btn-success {
        background: linear-gradient(135deg, #27ae60, #2ecc71);
        box-shadow: 0 4px 15px rgba(39, 174, 96, 0.4);
    }

    .btn-pdv.btn-warning {
        background: linear-gradient(135deg, #f39c12, #e67e22);
        box-shadow: 0 4px 15px rgba(243, 156, 18, 0.4);
    }

    .btn-pdv.btn-secondary {
        background: linear-gradient(135deg, #95a5a6, #7f8c8d);
        box-shadow: 0 4px 15px rgba(149, 165, 166, 0.4);
    }

    .btn-pdv.btn-outline-light {
        background: rgba(255,255,255,0.1);
        border: 2px solid rgba(255,255,255,0.3);
        backdrop-filter: blur(10px);
        color: white;
    }

    .btn-finalize {
        background: linear-gradient(135deg, #e74c3c, #c0392b);
        color: white;
        font-size: 1.2rem;
        padding: 1rem 2rem;
        border-radius: 15px;
        box-shadow: 0 6px 20px rgba(231, 76, 60, 0.4);
        transition: all 0.3s ease;
    }

    .btn-finalize:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(231, 76, 60, 0.6);
    }

    .autocomplete-suggestions {
        border: none;
        background: rgba(255, 255, 255, 0.95);
        max-height: 250px;
        overflow-y: auto;
        position: absolute;
        z-index: 9999;
        width: 100%;
        border-radius: 12px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.15);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .autocomplete-suggestion {
        padding: 1rem 1.5rem;
        cursor: pointer;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        font-size: 1rem;
        transition: all 0.2s ease;
        color: #2c3e50 !important;
    }

    .autocomplete-suggestion:hover {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
        transform: translateX(5px);
        color: #1a252f !important;
    }

    .autocomplete-suggestion.selected {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white !important;
        transform: translateX(5px);
    }

    .autocomplete-suggestion.selected strong {
        color: white !important;
    }

    .autocomplete-suggestion.selected small {
        color: rgba(255, 255, 255, 0.8) !important;
    }

    .autocomplete-suggestion strong {
        color: #1a252f !important;
        font-weight: 600;
    }

    .autocomplete-suggestion small {
        color: #5a6c7d !important;
        font-weight: 500;
    }

    .autocomplete-suggestion:hover strong {
        color: #0f1419 !important;
    }

    .autocomplete-suggestion:hover small {
        color: #37474f !important;
    }

    /* Caixa status removed: persistent toast removed to avoid overlaying navbar buttons */

    .carrinho-item {
        border: 2px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
        backdrop-filter: blur(10px);
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .carrinho-item:hover {
        border-color: rgba(102, 126, 234, 0.3);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .carrinho-item .produto-nome {
        font-weight: 600;
        color: #2c3e50;
        font-size: 1rem;
        margin-bottom: 0.5rem;
    }

    .carrinho-item .produto-info {
        font-size: 0.85rem;
        color: #7f8c8d;
        margin-bottom: 0.75rem;
    }

    .carrinho-controls {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
    }

    .carrinho-controls .btn-group {
        border-radius: 8px;
        overflow: hidden;
    }

    .carrinho-controls .btn {
        border: none;
        padding: 0.4rem 0.6rem;
        font-size: 0.8rem;
    }

    .quantidade-display {
        background: linear-gradient(135deg, #3498db, #2980b9);
        color: white;
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        font-weight: 600;
        min-width: 40px;
        text-align: center;
    }

    .loading-spinner {
        display: none;
        text-align: center;
        padding: 15px;
        font-size: 0.9rem;
    }

    .carrinho-content {
        flex: 1;
        overflow: auto;
        min-height: 0;
    }

    /* Limitar a altura do card do carrinho para não empurrar o total para fora da tela */
    .carrinho-card {
        /* occupy the grid row and scroll internally; avoid absolute limits so total stays visible */
        height: 100%;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .carrinho-card .carrinho-content {
    /* reservar espaço para o header do card (aprox 56px) */
    max-height: calc(55vh - 56px);
    overflow-y: auto;
    min-height: 0; /* allow flex/grid children to shrink correctly */
    }

    @media (max-width: 768px) {
        .carrinho-card { max-height: 50vh; }
        .carrinho-card .carrinho-content { max-height: calc(50vh - 56px); }
    }

    /* Total card sticky dentro da coluna direita */
    .total-card {
    position: static; /* flow normally below the cart */
    z-index: auto;
    background: transparent;
    padding-top: 0.25rem;
    display: block;
    /* keep total compact */
    height: auto;
    min-height: 0;
    align-self: end;
    }

    .total-card .alert {
        margin-bottom: 0;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    }

    /* Estilos específicos para a coluna direita reformulada */
    .pdv-right-panel .pdv-card {
        margin-bottom: 0;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .pdv-right-panel .pdv-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.15);
    }

    .carrinho-item {
        background: rgba(248, 249, 250, 0.8);
        border-radius: 8px;
        padding: 0.75rem;
        margin-bottom: 0.5rem;
        border-left: 3px solid #007bff;
        transition: all 0.2s ease;
    }

    .carrinho-item:hover {
        background: rgba(248, 249, 250, 1);
        transform: translateX(2px);
    }

    .produto-nome {
        font-weight: 600;
        color: #2c3e50;
        font-size: 0.95rem;
    }

    .produto-info {
        font-size: 0.8rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }

    .action-buttons {
        flex-shrink: 0;
        padding-top: 0.75rem;
    }

    .total-inline {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    }

    .total-inline .total-label {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2c3e50;
    }

    .total-inline .total-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #27ae60;
    }

    /* Ajuste: reduzir visual do card de total quando exibido abaixo do carrinho */
    .pdv-right-panel .pdv-card + .mt-3 .total-value {
        font-size: 1.6rem;
        font-weight: 800;
    }

    /* Estilo simples para o total integrado */
    .alert-success {
        animation: pulse-glow 3s infinite;
    }

    @keyframes pulse-glow {
        0%, 100% {
            box-shadow: 0 4px 12px rgba(39, 174, 96, 0.3);
        }
        50% {
            box-shadow: 0 6px 20px rgba(39, 174, 96, 0.5);
        }
    }

    /* Ajustes responsivos para fullscreen */
    @media (max-width: 1200px) {
        .pdv-right-panel {
            flex: 0 0 320px;
        }
    }

    @media (max-width: 992px) {
        .pdv-main-content {
            flex-direction: column;
        }
        
        .pdv-right-panel {
            flex: 0 0 350px;
        }
        
        .pdv-left-panel {
            flex: 0 0 40%;
        }
    }

    @media (max-width: 768px) {
        .pdv-container {
            padding: 0.5rem;
        }
        
        .pdv-header {
            padding: 0.75rem;
            flex-direction: column;
            text-align: center;
        }

        .pdv-header h1 {
            margin: 0;
            font-weight: 600;
            font-size: 0.9rem; /* fonte menor */
        

        /* Botões/controles dentro do header */
        .pdv-header .btn {
            padding: 0.35rem 0.6rem; /* menores */
            font-size: 0.85rem;
            border-radius: 8px;
        }

        .pdv-header .badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.45rem;
        }

        /* Ajustes responsivos para telas pequenas */
        @media (max-width: 768px) {
            .pdv-header {
                padding: 0.4rem 0.8rem;
                min-height: 44px;
            }

            .pdv-header h1 { font-size: 1rem; }
            .pdv-header h3 { font-size: 0.85rem; }
            .pdv-header .btn { padding: 0.3rem 0.5rem; font-size: 0.8rem; }
        }
        .pdv-header h3 {
            font-size: 1.1rem;
        }

        .pdv-main-content {
            flex-direction: column;
            padding: 0.5rem;
        }
        
        .produtos-panel, .carrinho-panel {
            max-width: 100%;
            margin-bottom: 1rem;
        }

        .produto-item {
            padding: 0.75rem;
        }

        .produto-item .produto-info-container {
            flex-direction: column;
            text-align: center;
        }

        .produto-item .produto-details {
            margin-left: 0;
            margin-top: 0.5rem;
        }

        .btn-pdv {
            padding: 0.5rem 0.75rem;
            font-size: 0.85rem;
        }
        
        .total-display {
            font-size: 1.8rem;
            padding: 1rem;
        }
        
    /* caixa-status removed */

        .carrinho-item {
            padding: 0.75rem;
        }

        .carrinho-controls {
            flex-direction: column;
            gap: 0.5rem;
        }

        .quantidade-display {
            order: -1;
            margin-bottom: 0.5rem;
        }

        .action-buttons .d-flex {
            flex-direction: column;
            gap: 1rem;
        }

        .total-inline {
            justify-content: center;
        }

        .total-inline .total-value {
            font-size: 1.8rem;
        }
    }

    /* Ajustes responsivos para o card de cliente */
    @media (max-width: 768px) {
        .pdv-card.flex-content .card-body .d-flex {
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .pdv-card.flex-content .card-body .d-flex .flex-shrink-0 {
            align-self: stretch;
        }
        
        .pdv-card.flex-content .card-body .d-flex .flex-shrink-0 .btn {
            width: 100%;
        }
    }

    /* Estilos responsivos para a coluna direita */
    @media (max-width: 768px) {
        .pdv-right-panel {
            padding: 0.5rem;
            gap: 0.5rem;
        }

        .pdv-right-panel .pdv-card {
            border-radius: 12px;
        }

        .pdv-right-panel .card-body {
            padding: 1rem;
        }

        .alert-success h3 {
            font-size: 1.8rem !important;
        }

        .btn-lg {
            padding: 0.75rem 1rem;
            font-size: 1rem;
        }
    }

    @media (max-width: 576px) {
        .pdv-right-panel {
            padding: 0.25rem;
        }

        .alert-success h3 {
            font-size: 1.5rem !important;
        }

        .d-flex.justify-content-between.align-items-center {
            flex-direction: column;
            align-items: flex-start !important;
        }

        .text-end {
            text-align: left !important;
            margin-top: 0.5rem;
        }
    }

    /* Estilos específicos para o card unificado */
    .pdv-card.flex-content .card-body {
        display: flex;
        flex-direction: column;
        min-height: 200px;
        padding: 2rem;
        flex: 1;
        min-height: 0;
    }

    .pdv-card.flex-content .card-body .form-label {
        font-size: 1.1rem;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .pdv-card.flex-content .card-body hr {
        border: none;
        height: 2px;
        background: linear-gradient(90deg, rgba(102, 126, 234, 0.3), rgba(40, 167, 69, 0.3));
        margin: 2rem 0;
    }

    /* Ajustes para o input de produtos no card unificado */
    .pdv-card.flex-content .card-body #produtoSearch {
        margin-bottom: 0.5rem;
    }

    /* Ajustes responsivos para o card unificado */
    @media (max-width: 768px) {
        .pdv-card.flex-content .card-body {
            padding: 1.5rem;
        }

        .pdv-card.flex-content .card-body .form-label {
            font-size: 1rem;
        }

        .pdv-card.flex-content .card-body hr {
            margin: 1.5rem 0;
        }
    }

    /* Animações e efeitos visuais */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes pulseGlow {
        0%, 100% {
            box-shadow: 0 0 5px rgba(52, 152, 219, 0.3);
        }
        50% {
            box-shadow: 0 0 20px rgba(52, 152, 219, 0.6);
        }
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .animate-fadeInUp {
        animation: fadeInUp 0.5s ease-out;
    }

    .animate-slideInRight {
        animation: slideInRight 0.4s ease-out;
    }

    .pulse-glow {
        animation: pulseGlow 2s infinite;
    }

    /* Loading states */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.7);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .loading-spinner {
        width: 60px;
        height: 60px;
        border: 4px solid rgba(255,255,255,0.3);
        border-top: 4px solid #3498db;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    /* Estilos para elementos do formulário */
    .form-control {
        background: rgba(255, 255, 255, 0.9);
        border: 2px solid rgba(52, 152, 219, 0.2);
        border-radius: 10px;
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .form-control:focus {
        border-color: #3498db;
        background: rgba(255, 255, 255, 1);
        box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25), 0 4px 12px rgba(0,0,0,0.1);
        transform: translateY(-1px);
        outline: none;
    }
    
    .form-control-lg {
        padding: 0.85rem 1.2rem;
        font-size: 1rem;
        border-radius: 12px;
    }
    
    .form-select {
        background: rgba(255, 255, 255, 0.9);
        border: 2px solid rgba(52, 152, 219, 0.2);
        border-radius: 10px;
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .form-select:focus {
        border-color: #3498db;
        background: rgba(255, 255, 255, 1);
        box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25), 0 4px 12px rgba(0,0,0,0.1);
        outline: none;
    }

    .form-label {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }
    
    /* Estilos específicos para campos do modal de abertura de caixa */
    #modalAbrirCaixa .form-control,
    #modalFecharCaixa .form-control {
        background: rgba(255, 255, 255, 0.9);
        border: 2px solid rgba(52, 152, 219, 0.2);
        border-radius: 10px;
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        color: #2c3e50 !important;
    }

    #modalAbrirCaixa .form-control:focus,
    #modalFecharCaixa .form-control:focus {
        border-color: #3498db;
        background: rgba(255, 255, 255, 1);
        box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25), 0 4px 12px rgba(0,0,0,0.1);
        transform: translateY(-1px);
        outline: none;
        color: #2c3e50 !important;
    }

    #modalAbrirCaixa .form-control::placeholder,
    #modalFecharCaixa .form-control::placeholder {
        color: #6c757d !important;
        opacity: 0.7;
    }
    
    /* Ajustes para modais em fullscreen */
    .modal-content {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(240, 248, 255, 0.9));
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 16px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }

    .modal-header {
        background: linear-gradient(135deg, #3498db, #2980b9);
        color: white;
        border-radius: 16px 16px 0 0;
        border-bottom: none;
        padding: 1.5rem;
    }

    .modal-header .modal-title {
        font-weight: 700;
        font-size: 1.2rem;
    }

    .modal-header .btn-close {
        filter: brightness(0) invert(1);
        opacity: 0.8;
    }

    .modal-body {
        padding: 2rem;
    }

    .modal-footer {
        border-top: 1px solid rgba(0,0,0,0.1);
        padding: 1.5rem 2rem;
        border-radius: 0 0 16px 16px;
        background: rgba(248, 249, 250, 0.8);
    }

    /* Estilos específicos para campos do modal de abertura de caixa */
    #modalAbrirCaixa .form-control,
    #modalFecharCaixa .form-control {
        background: rgba(255, 255, 255, 0.9);
        border: 2px solid rgba(52, 152, 219, 0.2);
        border-radius: 10px;
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        color: #2c3e50 !important;
    }

    #modalAbrirCaixa .form-control:focus,
    #modalFecharCaixa .form-control:focus {
        border-color: #3498db;
        background: rgba(255, 255, 255, 1);
        box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25), 0 4px 12px rgba(0,0,0,0.1);
        transform: translateY(-1px);
        outline: none;
        color: #2c3e50 !important;
    }

    #modalAbrirCaixa .form-control::placeholder,
    #modalFecharCaixa .form-control::placeholder {
        color: #6c757d !important;
        opacity: 0.7;
    }

    #modalAbrirCaixa .form-control:focus::placeholder,
    #modalFecharCaixa .form-control:focus::placeholder {
        color: #6c757d !important;
        opacity: 0.5;
    }

    #modalAbrirCaixa .form-label,
    #modalFecharCaixa .form-label {
        font-weight: 600;
        color: #2c3e50 !important;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    /* Estilos específicos para inputs de busca (forçar tema claro) */
    /* alta especificidade e importantes para sobrescrever temas escuros de outros arquivos */
    input#clienteSearch.form-control,
    input#produtoSearch.form-control,
    #clienteSearch, #produtoSearch,
    .pdv-card.flex-content .card-body #produtoSearch {
        background-color: #ffffff !important;
        background-image: none !important;
        -webkit-appearance: none !important;
        -moz-appearance: none !important;
        appearance: none !important;
        color: #0f1419 !important;
        -webkit-text-fill-color: #0f1419 !important;
        caret-color: #0f1419 !important;
        font-weight: 500;
        border: 2px solid rgba(52, 152, 219, 0.18) !important;
        border-radius: 10px !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.04) !important;
        mix-blend-mode: normal !important;
        filter: none !important;
        opacity: 1 !important;
    }

    /* placeholders */
    #clienteSearch::placeholder, #produtoSearch::placeholder {
        color: #6c757d !important;
        opacity: 0.85 !important;
        font-weight: 400;
    }

    /* foco: fundo totalmente branco e borda destacada */
    input#clienteSearch:focus, input#produtoSearch:focus,
    #clienteSearch:focus, #produtoSearch:focus {
        color: #0f1419 !important;
        background-color: #ffffff !important;
        background-image: none !important;
        border-color: #3498db !important;
        box-shadow: 0 0 0 0.18rem rgba(52, 152, 219, 0.18) !important;
        outline: none !important;
    }

    #clienteSearch:focus::placeholder, #produtoSearch:focus::placeholder {
        color: #6c757d !important;
        opacity: 0.6 !important;
    }
</style>
<!-- Caixa status removed: persistent toast was overlaying navbar buttons -->

<div class="pdv-container">
    <!-- Cabeçalho PDV -->
    <div class="pdv-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="d-flex align-items-center">
                    <img src="<?= IMG_PATH . $logo ?>" alt="<?= $empresa ?>" height="40" class="me-3">
                    <div class="d-flex flex-column justify-content-center">
                        <h4 class="mb-0 fw-bold">
                            <?= $empresa ?>
                        </h4>
                        <small class="text-muted">Ponto de Vendas</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 text-end">
                <div class="btn-group" role="group">
                    <?php if (!$caixa_status['aberto']): ?>
                        <button type="button" class="btn btn-success btn-sm btn-pdv" id="btnAbrirCaixa">
                            <i class="fas fa-unlock"></i> Abrir Caixa <small class="text-muted ms-2"><kbd>F4</kbd></small>
                        </button>
                    <?php else: ?>
                        <button type="button" class="btn btn-warning btn-sm btn-pdv" id="btnFecharCaixa">
                            <i class="fas fa-lock"></i> Fechar Caixa <small class="text-muted ms-2"><kbd>F4</kbd></small>
                        </button>
                    <?php endif; ?>
                    <button type="button" class="btn btn-secondary btn-sm btn-pdv" id="btnCancelarVenda">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="button" class="btn btn-outline-light btn-sm btn-pdv" onclick="window.location.href='<?= base_url('home') ?>'">
                        <i class="fas fa-home"></i> Menu
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="pdv-main-content">
        <!-- Painel Esquerdo: Busca de Cliente e Produtos -->
        <div class="pdv-left-panel">
            <!-- Card Unificado de Cliente e Produtos -->
            <div class="card pdv-card flex-content" style="flex: 1;">
                <div class="card-body">
                    <!-- Seção Cliente -->
                    <div class="mb-4">
                        <label for="clienteSearch" class="form-label fw-bold text-primary mb-2">
                            <i class="fas fa-user me-1"></i> Cliente <small class="text-muted ms-2"><kbd>F3</kbd></small>
                        </label>
                        <div class="d-flex gap-2 align-items-start">
                            <div class="flex-grow-1">
                                <div class="form-group position-relative">
                                    <input type="text" class="form-control" id="clienteSearch"
                                           placeholder="Buscar cliente (Nome, CPF ou Telefone)...">
                                    <div id="clienteAutocomplete" class="autocomplete-suggestions"></div>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <button type="button" class="btn btn-outline-primary btn-sm" id="btnClienteVazio">
                                    <i class="fas fa-user-slash"></i> Sem Cliente <small class="text-muted ms-2"><kbd>F8</kbd></small>
                                </button>
                            </div>
                        </div>
                        <div id="clienteSelecionado" class="mt-2" style="display: none;">
                            <div class="alert alert-success alert-sm">
                                <strong>Cliente:</strong>
                                <span id="clienteNome"></span>
                                <span id="clienteCpf" class="text-muted"></span>
                                <button type="button" class="btn btn-sm btn-outline-danger float-end" id="btnRemoverCliente">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Separador -->
                    <hr class="my-4">

                    <!-- Seção Produtos -->
                    <div style="flex: 1; display: flex; flex-direction: column; min-height: 0;">
                        <label for="produtoSearch" class="form-label fw-bold text-success mb-2">
                            <i class="fas fa-search me-1"></i> Buscar Produtos <small class="text-muted ms-2"><kbd>F2</kbd> <span class="text-muted">/</span> <kbd>F9</kbd></small>
                        </label>
                        <div class="form-group position-relative mb-2">
                            <input type="text" class="form-control form-control-lg" id="produtoSearch"
                                   placeholder="Digite nome, código ou código de barras...">
                            <div id="produtoAutocomplete" class="autocomplete-suggestions"></div>
                        </div>
                        <div class="loading-spinner" id="loadingProdutos">
                            <i class="fas fa-spinner fa-spin"></i> Buscando...
                        </div>

                        <!-- Lista de Produtos Encontrados -->
                        <div id="produtosEncontrados" style="display: none; flex: 1; overflow: auto;">
                            <hr class="mt-3">
                            <div id="listaProdutos" class="row g-2"></div>
                        </div>
                    </div>

                    <!-- Botões de Ação - Movidos para o card esquerdo -->
                    <hr class="my-4">
                    <div class="action-buttons-section">
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-success btn-lg" id="btnFinalizarVenda" style="border-radius: 12px; padding: 1rem; font-weight: 600; font-size: 1.1rem;">
                                <i class="fas fa-check me-2"></i> Finalizar Venda <small class="text-white-50 ms-2"><kbd>F10</kbd> <span class="text-white-50">/</span> <kbd>Ctrl+Enter</kbd></small>
                            </button>
                            <div class="row g-2">
                                <div class="col-6">
                                    <button type="button" class="btn btn-outline-secondary w-100" id="btnLimparCarrinho" style="border-radius: 10px; padding: 0.75rem;">
                                        <i class="fas fa-trash me-1"></i> Limpar <small class="text-muted ms-2"><kbd>F6</kbd></small>
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn btn-outline-info w-100" id="btnSalvarOrcamento" style="border-radius: 10px; padding: 0.75rem;">
                                        <i class="fas fa-save me-1"></i> Salvar <small class="text-muted ms-2"><kbd>F7</kbd></small>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Painel Direito: Carrinho e Total -->
        <div class="pdv-right-panel">
            <!-- Carrinho de Compras -->
            <div class="card pdv-card carrinho-card" style="flex: 1; overflow: hidden;">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-shopping-cart"></i> Carrinho</span>
                    <span class="badge bg-light text-dark" id="contadorItens">0 itens</span>
                </div>
                <div class="card-body carrinho-content" style="overflow-y: auto; height: 100%;">
                    <div id="carrinhoItens">
                        <div class="text-center text-muted">
                            <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                            <p class="mb-0">Nenhum produto</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total da Venda - Abaixo do carrinho -->
            <div class="card pdv-card total-card" style="flex: 0 0 auto;">
                <div class="card-body p-0">
                    <div class="alert alert-success mb-0" style="background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%); border: none; color: white; padding: 1.2rem; border-radius: 16px; box-shadow: 0 4px 12px rgba(39, 174, 96, 0.4); margin: 0;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1" style="color: white; font-weight: 600; font-size: 1.1rem;">
                                    <i class="fas fa-calculator me-2"></i>TOTAL DA VENDA
                                </h5>
                                <small style="color: rgba(255,255,255,0.8); font-size: 0.9rem;" id="totalItensInfo">0 itens</small>
                            </div>
                            <div class="text-end">
                                <h3 class="mb-0" style="color: white; font-weight: 800; font-size: 2.2rem; text-shadow: 0 2px 4px rgba(0,0,0,0.2);" id="totalVenda">R$ 0,00</h3>
                            </div>
                        </div>
                        <div class="mt-2 text-end">
                            <small class="text-white-50">Atalhos: <kbd>F2</kbd> Produtos • <kbd>F3</kbd> Cliente • <kbd>F4</kbd> Caixa • <kbd>F6</kbd> Limpar • <kbd>F7</kbd> Salvar • <kbd>F8</kbd> Sem Cliente • <kbd>F10</kbd>/Ctrl+Enter Finalizar</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Abrir Caixa -->
<div class="modal fade" id="modalAbrirCaixa" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Abrir Caixa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formAbrirCaixa">
                    <div class="form-group mb-3">
                        <label for="valorInicialCaixa" class="text-dark">Valor Inicial</label>
                        <input type="text" class="form-control valor-monetary" id="valorInicialCaixa" 
                               step="0.01" min="0" required placeholder="0,00">
                    </div>
                    <div class="form-group mb-3">
                        <label for="observacoesCaixa" class="text-dark">Observações</label>
                        <textarea class="form-control" id="observacoesCaixa" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btnConfirmarAbrirCaixa">
                    <i class="fas fa-unlock"></i> Abrir Caixa
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Fechar Caixa -->
<div class="modal fade" id="modalFecharCaixa" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Fechar Caixa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formFecharCaixa">
                    <div class="form-group mb-3">
                        <label for="valorFinalCaixa">Valor Final</label>
                        <input type="text" class="form-control valor-monetary" id="valorFinalCaixa" 
                               step="0.01" min="0" required placeholder="0,00">
                    </div>
                    <div class="form-group mb-3">
                        <label for="observacoesFechamento">Observações</label>
                        <textarea class="form-control" id="observacoesFechamento" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-warning" id="btnConfirmarFecharCaixa">
                    <i class="fas fa-lock"></i> Fechar Caixa
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Finalização da Venda -->
<div class="modal fade" id="modalFinalizarVenda" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Finalizar Venda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Resumo da Venda</h6>
                        <div id="resumoVenda"></div>
                    </div>
                    <div class="col-md-6">
                        <h6>Informações de Pagamento</h6>
                        <div id="infoPagamento"></div>
                        
                        <div class="form-group mt-3">
                            <label for="observacoesVenda">Observações</label>
                            <textarea class="form-control" id="observacoesVenda" rows="3" 
                                      placeholder="Observações sobre a venda..."></textarea>
                        </div>
                        <div class="form-group form-check form-switch mt-3">
                            <input class="form-check-input" type="checkbox" id="switchImprimirNomeCliente" checked>
                            <label class="form-check-label" for="switchImprimirNomeCliente">Imprimir nome do cliente no cupom fiscal</label>
                        </div>
                        <div class="form-group form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" id="switchImprimirGarantias" checked>
                            <label class="form-check-label" for="switchImprimirGarantias">Imprimir garantias dos produtos no cupom fiscal</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btnConfirmarVenda">
                    <i class="fas fa-check"></i> Confirmar Venda
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Scripts específicos do PDV -->
<script>
(function() {
    'use strict';
    
    // ====================================
    // VARIÁVEIS GLOBAIS
    // ====================================
    let carrinho = [];
    let clienteSelecionado = null;
    let produtos = <?= json_encode($produtos) ?>;
    let produtoTimeout;
    let clienteTimeout;
    
    // ====================================
    // INICIALIZAÇÃO
    // ====================================
    document.addEventListener('DOMContentLoaded', function() {
        initializePDV();
        setupEventListeners();
        atualizarInterface();
    // Debug disabled: no visual highlights
    });

    // debugOverlap helper removed
    
    function initializePDV() {
        console.log('Inicializando PDV...');
        console.log('Total de produtos carregados:', produtos.length);
        console.log('Produtos:', produtos);
        
        // Verificar se o caixa está aberto
        const caixaAberto = <?= json_encode($caixa_status['aberto']) ?>;
        if (!caixaAberto) {
            Swal.fire({
                title: 'Caixa Fechado',
                text: 'O caixa precisa estar aberto para realizar vendas.',
                icon: 'warning',
                confirmButtonText: 'Abrir Caixa',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#modalAbrirCaixa').modal('show');
                }
            });
        }
        
        // Configurar máscaras e validações
        configurarMascaras();
        
        // Focar no campo de busca de produtos
        $('#produtoSearch').focus();
    }
    
    function setupEventListeners() {
        // ====================================
        // EVENTOS DE BUSCA
        // ====================================
        
        // Busca de produtos
        $('#produtoSearch').on('input', function() {
            const termo = $(this).val();
            clearTimeout(produtoTimeout);
            
            // Limpar seleções anteriores
            $('.autocomplete-suggestion').removeClass('selected');
            $('.produto-item').removeClass('selected');
            
            if (termo.length >= 2) {
                produtoTimeout = setTimeout(() => {
                    buscarProdutos(termo);
                }, 300);
            } else {
                $('#produtoAutocomplete').hide();
                $('#produtosEncontrados').hide();
            }
        });
        
        // Navegação por setas para produtos
        $('#produtoSearch').on('keydown', function(e) {
            handleKeyboardNavigation(e, '#produtoAutocomplete', '#produtosEncontrados');
        });
        
        // Busca de clientes
        $('#clienteSearch').on('input', function() {
            const termo = $(this).val();
            clearTimeout(clienteTimeout);
            
            // Limpar seleções anteriores
            $('.autocomplete-suggestion').removeClass('selected');
            
            if (termo.length >= 2) {
                clienteTimeout = setTimeout(() => {
                    buscarClientes(termo);
                }, 300);
            } else {
                $('#clienteAutocomplete').hide();
            }
        });
        
        // Navegação por setas para clientes
        $('#clienteSearch').on('keydown', function(e) {
            handleKeyboardNavigation(e, '#clienteAutocomplete');
        });
        
        // ====================================
        // EVENTOS DE CLIENTE
        // ====================================
        
        $('#btnClienteVazio').on('click', function() {
            clienteSelecionado = null;
            $('#clienteSelecionado').hide();
            $('#clienteSearch').val('');
        });
        
        $('#btnRemoverCliente').on('click', function() {
            clienteSelecionado = null;
            $('#clienteSelecionado').hide();
            $('#clienteSearch').val('');
        });
        
        // ====================================
        // EVENTOS DE CARRINHO
        // ====================================
        
        $('#btnLimparCarrinho').on('click', function() {
            Swal.fire({
                title: 'Limpar Carrinho?',
                text: 'Todos os produtos serão removidos do carrinho.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sim, limpar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    limparCarrinho();
                }
            });
        });
        
        // ====================================
        // EVENTOS DE CAIXA
        // ====================================
        
        $('#btnAbrirCaixa').on('click', function() {
            $('#modalAbrirCaixa').modal('show');
        });
        
        $('#btnFecharCaixa').on('click', function() {
            $('#modalFecharCaixa').modal('show');
        });
        
        // Reaplicar máscaras quando modais são mostrados
        $('#modalAbrirCaixa, #modalFecharCaixa').on('shown.bs.modal', function() {
            configurarMascaras();
        });
        
        $('#btnConfirmarAbrirCaixa').on('click', function() {
            abrirCaixa();
        });
        
        $('#btnConfirmarFecharCaixa').on('click', function() {
            fecharCaixa();
        });
        
        // ====================================
        // EVENTOS DE VENDA
        // ====================================
        
        $('#btnFinalizarVenda').on('click', function() {
            if (carrinho.length === 0) {
                Swal.fire('Erro', 'Adicione produtos ao carrinho antes de finalizar a venda.', 'error');
                return;
            }
            
            prepararFinalizacaoVenda();
        });
        
        $('#btnConfirmarVenda').on('click', function() {
            processarVenda();
        });
        
        $('#btnCancelarVenda').on('click', function() {
            cancelarVenda();
        });
        
        // ====================================
        // OUTROS EVENTOS
        // ====================================
        
        // Fechar autocomplete ao clicar fora
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.position-relative').length) {
                $('.autocomplete-suggestions').hide();
                $('.autocomplete-suggestion').removeClass('selected');
                $('.produto-item').removeClass('selected');
            }
        });
        
        // Enter para buscar
        $('#produtoSearch').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                const selectedItem = $('.autocomplete-suggestion.selected, .produto-item.selected');
                if (selectedItem.length > 0) {
                    selectedItem.click();
                } else {
                    const termo = $(this).val();
                    if (termo.length >= 2) {
                        buscarProdutos(termo);
                    }
                }
            }
        });
        
        $('#clienteSearch').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                const selectedItem = $('.autocomplete-suggestion.selected');
                if (selectedItem.length > 0) {
                    selectedItem.click();
                }
            }
        });
        
        // Teclas de atalho
        $(document).on('keydown', function(e) {
            // Ignorar F5 (reload) e quando um input/textarea está em edição
            const targetTag = (e.target && e.target.tagName) ? e.target.tagName.toLowerCase() : '';
            const isEditing = ['input', 'textarea', 'select'].includes(targetTag) || $(e.target).is('[contenteditable]');

            // F2 para focar na busca de produtos
            if (e.key === 'F2') {
                e.preventDefault();
                $('#produtoSearch').focus().select();
            }

            // F3 para focar na busca de clientes
            if (e.key === 'F3') {
                e.preventDefault();
                $('#clienteSearch').focus().select();
            }

            // F4: abrir/fechar caixa dependendo do estado
            if (e.key === 'F4') {
                e.preventDefault();
                // Prioriza botão existente
                if ($('#btnAbrirCaixa').length && $('#btnAbrirCaixa').is(':visible')) {
                    $('#btnAbrirCaixa').click();
                } else if ($('#btnFecharCaixa').length && $('#btnFecharCaixa').is(':visible')) {
                    $('#btnFecharCaixa').click();
                }
            }

            // F6: limpar carrinho
            if (e.key === 'F6') {
                e.preventDefault();
                // Evitar disparar quando editando campos de formulário
                if (!isEditing) {
                    $('#btnLimparCarrinho').click();
                }
            }

            // F7: salvar orçamento
            if (e.key === 'F7') {
                e.preventDefault();
                if (!isEditing) {
                    $('#btnSalvarOrcamento').click();
                }
            }

            // F8: sem cliente (assignar cliente vazio)
            if (e.key === 'F8') {
                e.preventDefault();
                if (!isEditing) {
                    $('#btnClienteVazio').click();
                }
            }

            // F9: foco na busca de produtos (atalho alternativo)
            if (e.key === 'F9') {
                e.preventDefault();
                $('#produtoSearch').focus().select();
            }

            // F10 para finalizar venda
            if (e.key === 'F10') {
                e.preventDefault();
                $('#btnFinalizarVenda').click();
            }

            // Ctrl+Enter (ou Cmd+Enter em Mac) para confirmar venda rapidamente
            if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                // se estiver em um campo de texto multlinha, só processa se não for textarea
                if (!$(e.target).is('textarea')) {
                    e.preventDefault();
                    // Só permitir se houver itens no carrinho
                    if (carrinho.length > 0) {
                        $('#btnFinalizarVenda').click();
                    }
                }
            }
        });
    }
    
    // ====================================
    // FUNÇÕES DE NAVEGAÇÃO POR TECLADO
    // ====================================
    
    function handleKeyboardNavigation(e, autocompleteSelector, produtosSelector = null) {
        const autocompleteContainer = $(autocompleteSelector);
        const isAutocompleteVisible = autocompleteContainer.is(':visible') && autocompleteContainer.children().length > 0;
        
        // Se há autocomplete visível, navegar nele
        if (isAutocompleteVisible) {
            handleAutocompleteNavigation(e, autocompleteSelector);
            return;
        }
        
        // Se não há autocomplete mas há produtos visíveis (para busca de produtos)
        if (produtosSelector) {
            const produtosContainer = $(produtosSelector);
            const isProdutosVisible = produtosContainer.is(':visible');
            
            if (isProdutosVisible) {
                handleProdutosNavigation(e, produtosSelector);
                return;
            }
        }
    }
    
    function handleAutocompleteNavigation(e, containerSelector) {
        const suggestions = $(containerSelector + ' .autocomplete-suggestion');
        if (suggestions.length === 0) return;
        
        const currentSelected = suggestions.filter('.selected');
        let newIndex = -1;
        
        switch(e.keyCode) {
            case 38: // Seta para cima
                e.preventDefault();
                if (currentSelected.length === 0) {
                    newIndex = suggestions.length - 1;
                } else {
                    const currentIndex = suggestions.index(currentSelected);
                    newIndex = currentIndex > 0 ? currentIndex - 1 : suggestions.length - 1;
                }
                break;
                
            case 40: // Seta para baixo
                e.preventDefault();
                if (currentSelected.length === 0) {
                    newIndex = 0;
                } else {
                    const currentIndex = suggestions.index(currentSelected);
                    newIndex = currentIndex < suggestions.length - 1 ? currentIndex + 1 : 0;
                }
                break;
                
            case 27: // Escape
                e.preventDefault();
                $(containerSelector).hide();
                return;
        }
        
        if (newIndex >= 0) {
            suggestions.removeClass('selected');
            suggestions.eq(newIndex).addClass('selected');
            
            // Scroll para o item selecionado se necessário
            const container = $(containerSelector);
            const selectedItem = suggestions.eq(newIndex);
            const containerHeight = container.height();
            const itemTop = selectedItem.position().top;
            const itemHeight = selectedItem.outerHeight();
            
            if (itemTop < 0) {
                container.scrollTop(container.scrollTop() + itemTop);
            } else if (itemTop + itemHeight > containerHeight) {
                container.scrollTop(container.scrollTop() + itemTop + itemHeight - containerHeight);
            }
        }
    }
    
    function handleProdutosNavigation(e, containerSelector) {
        const produtos = $(containerSelector + ' .produto-item');
        if (produtos.length === 0) return;
        
        const currentSelected = produtos.filter('.selected');
        let newIndex = -1;
        
        switch(e.keyCode) {
            case 38: // Seta para cima
                e.preventDefault();
                if (currentSelected.length === 0) {
                    newIndex = produtos.length - 1;
                } else {
                    const currentIndex = produtos.index(currentSelected);
                    newIndex = currentIndex > 0 ? currentIndex - 1 : produtos.length - 1;
                }
                break;
                
            case 40: // Seta para baixo
                e.preventDefault();
                if (currentSelected.length === 0) {
                    newIndex = 0;
                } else {
                    const currentIndex = produtos.index(currentSelected);
                    newIndex = currentIndex < produtos.length - 1 ? currentIndex + 1 : 0;
                }
                break;
                
            case 27: // Escape
                e.preventDefault();
                $(containerSelector).hide();
                return;
        }
        
        if (newIndex >= 0) {
            produtos.removeClass('selected');
            produtos.eq(newIndex).addClass('selected');
            
            // Scroll para o item selecionado se necessário
            const container = $(containerSelector);
            const selectedItem = produtos.eq(newIndex);
            const containerHeight = container.height();
            const itemTop = selectedItem.position().top;
            const itemHeight = selectedItem.outerHeight();
            
            if (itemTop < 0) {
                container.scrollTop(container.scrollTop() + itemTop);
            } else if (itemTop + itemHeight > containerHeight) {
                container.scrollTop(container.scrollTop() + itemTop + itemHeight - containerHeight);
            }
        }
    }

    // ====================================
    // FUNÇÕES DE BUSCA
    // ====================================
    
    function buscarProdutos(termo) {
        console.log('Buscando produtos com termo:', termo);
        $('#loadingProdutos').show();
        
        $.ajax({
            url: '<?= base_url('pdv/buscarProdutos') ?>',
            method: 'GET',
            data: { termo: termo },
            dataType: 'json',
            success: function(response) {
                console.log('Resposta da busca de produtos:', response);
                $('#loadingProdutos').hide();
                
                if (response.success) {
                    // Popular o dropdown de autocomplete (lista)
                    exibirProdutosAutocomplete(response.produtos);
                    // Esconder grade lateral para evitar duplicidade visual
                    $('#produtosEncontrados').hide();
                } else {
                    console.error('Erro na busca:', response.message);
                    Swal.fire('Erro', response.message, 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('Erro AJAX na busca de produtos:', error);
                console.error('Status:', status);
                console.error('Response:', xhr.responseText);
                $('#loadingProdutos').hide();
                Swal.fire('Erro', 'Erro ao buscar produtos.', 'error');
            }
        });
    }
    
    function buscarClientes(termo) {
        console.log('Buscando clientes com termo:', termo);
        
        $.ajax({
            url: '<?= base_url('pdv/buscarClientes') ?>',
            method: 'GET',
            data: { termo: termo },
            dataType: 'json',
            success: function(response) {
                console.log('Resposta da busca de clientes:', response);
                if (response.success) {
                    exibirClientesAutocomplete(response.clientes);
                }
            },
            error: function(xhr, status, error) {
                console.error('Erro AJAX na busca de clientes:', error);
                console.error('Status:', status);
                console.error('Response:', xhr.responseText);
            }
        });
    }
    
    // ====================================
    // FUNÇÕES DE EXIBIÇÃO
    // ====================================
    
    function exibirProdutos(produtos) {
        const container = $('#listaProdutos');
        container.empty();
        
        if (produtos.length === 0) {
            container.html('<div class="col-12 text-center text-muted">Nenhum produto encontrado</div>');
            $('#produtosEncontrados').show();
            return;
        }
        
        produtos.forEach((produto, index) => {
            // Tratar imagem do produto
            let imagemHtml;
            if (produto.imagem && produto.imagem !== 'produto-sem-imagem.webp' && produto.imagem !== '') {
                imagemHtml = `<img src="<?= base_url('uploads/produtos/') ?>${produto.imagem}" 
                              class="rounded" width="40" height="40" style="object-fit: cover;">`;
            } else {
                imagemHtml = '<div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="fas fa-box text-white"></i></div>';
            }
            
            const produtoHtml = `
                <div class="col-md-6 col-xl-4 mb-2">
                    <div class="produto-item" onclick="adicionarProdutoCarrinho(${produto.id})" data-index="${index}">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                ${imagemHtml}
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <h6 class="mb-1 fs-6">${produto.nome}</h6>
                                <small class="text-muted">Cód: ${produto.codigo}</small><br>
                                <strong class="text-success">R$ ${parseFloat(produto.preco || 0).toLocaleString('pt-BR', {minimumFractionDigits: 2})}</strong>
                                <small class="text-muted ms-1">Est: ${produto.estoque || 0}</small>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            container.append(produtoHtml);
        });
        
        $('#produtosEncontrados').show();
        
        // Remover seleção anterior
        container.find('.produto-item').removeClass('selected');
    }
    
    function exibirClientesAutocomplete(clientes) {
        const container = $('#clienteAutocomplete');
        container.empty();
        
        if (clientes.length === 0) {
            container.hide();
            return;
        }
        
        clientes.forEach((cliente, index) => {
            const telefone = cliente.telefone || cliente.celular || 'Não informado';
            const clienteHtml = `
                <div class="autocomplete-suggestion" onclick="selecionarCliente(${cliente.id}, '${cliente.nome}', '${cliente.cpf}')" data-index="${index}">
                    <strong>${cliente.nome}</strong><br>
                    <small class="text-muted">CPF: ${cliente.cpf} | Tel: ${telefone}</small>
                </div>
            `;
            container.append(clienteHtml);
        });
        
        container.show();
        
        // Remover seleção anterior
        container.find('.autocomplete-suggestion').removeClass('selected');
    }

    function exibirProdutosAutocomplete(produtos) {
        const container = $('#produtoAutocomplete');
        container.empty();

        if (produtos.length === 0) {
            container.hide();
            return;
        }

        produtos.forEach((produto, index) => {
            const telefone = produto.codigo || '';
            const imagemHtml = produto.imagem && produto.imagem !== 'produto-sem-imagem.webp' && produto.imagem !== ''
                ? `<img src="<?= base_url('uploads/produtos/') ?>${produto.imagem}" style="width:36px;height:36px;object-fit:cover;border-radius:6px;margin-right:8px;">`
                : `<div style="width:36px;height:36px;background:#6c757d;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;color:white;margin-right:8px;"><i class="fas fa-box"></i></div>`;

            const produtoHtml = `
                <div class="autocomplete-suggestion d-flex align-items-center" data-index="${index}" onclick="adicionarProdutoCarrinho(${produto.id})">
                    ${imagemHtml}
                    <div>
                        <strong style="display:block">${produto.nome}</strong>
                        <small class="text-muted">Cód: ${produto.codigo} • R$ ${parseFloat(produto.preco || 0).toLocaleString('pt-BR', {minimumFractionDigits: 2})}</small>
                    </div>
                </div>
            `;

            container.append(produtoHtml);
        });

        container.show();

        // Remover seleção anterior
        container.find('.autocomplete-suggestion').removeClass('selected');
    }
    
    // ====================================
    // FUNÇÕES DE CARRINHO
    // ====================================
    
    window.adicionarProdutoCarrinho = function(produtoId) {
        const produto = produtos.find(p => p.id == produtoId);
        if (!produto) {
            Swal.fire('Erro', 'Produto não encontrado.', 'error');
            return;
        }
        
        const estoque = parseInt(produto.estoque || 0);
        if (estoque <= 0) {
            Swal.fire('Erro', 'Produto sem estoque disponível.', 'error');
            return;
        }
        
        // Verificar se já existe no carrinho
        const itemExistente = carrinho.find(item => item.produto_id == produtoId);
        
        if (itemExistente) {
            if (itemExistente.quantidade >= estoque) {
                Swal.fire('Erro', 'Quantidade máxima atingida para este produto.', 'error');
                return;
            }
            itemExistente.quantidade++;
        } else {
            carrinho.push({
                produto_id: produtoId,
                nome: produto.nome,
                codigo: produto.codigo,
                preco_unitario: parseFloat(produto.preco || 0),
                quantidade: 1,
                estoque: estoque
            });
        }
        
        atualizarCarrinho();
        
        // Limpar campo de busca e dropdown de produtos + remover seleções
        $('#produtoSearch').val('').focus();
        $('#produtoAutocomplete').hide().empty();
        $('#produtosEncontrados').hide();
        $('#listaProdutos').empty();
        $('.autocomplete-suggestion').removeClass('selected');
        $('.produto-item').removeClass('selected');

        // Mantém foco na busca após adicionar (sem alerta)
        $('#produtoSearch').focus().select();
    };
    
    function atualizarCarrinho() {
        const container = $('#carrinhoItens');
        container.empty();
        
        if (carrinho.length === 0) {
            container.html(`
                <div class="text-center text-muted">
                    <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                    <p>Nenhum produto adicionado</p>
                </div>
            `);
            $('#contadorItens').text('0 itens');
            $('#totalVenda').text('R$ 0,00');
            $('#totalItensInfo').text('0 itens');
            return;
        }
        
        let total = 0;
        let totalItens = 0;
        
        carrinho.forEach((item, index) => {
            const subtotal = item.quantidade * item.preco_unitario;
            total += subtotal;
            totalItens += item.quantidade;
            
            const itemHtml = `
                <div class="carrinho-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="produto-nome">${item.nome}</div>
                            <div class="produto-info">
                                Código: ${item.codigo}<br>
                                R$ ${item.preco_unitario.toLocaleString('pt-BR', {minimumFractionDigits: 2})} x ${item.quantidade}
                            </div>
                        </div>
                        <div class="text-end">
                            <strong>R$ ${subtotal.toLocaleString('pt-BR', {minimumFractionDigits: 2})}</strong>
                            <br>
                            <div class="btn-group btn-group-sm mt-1">
                                <button class="btn btn-outline-secondary" onclick="alterarQuantidade(${index}, -1)">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button class="btn btn-outline-primary" onclick="editarQuantidade(${index})">
                                    ${item.quantidade}
                                </button>
                                <button class="btn btn-outline-secondary" onclick="alterarQuantidade(${index}, 1)">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button class="btn btn-outline-danger" onclick="removerItem(${index})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            container.append(itemHtml);
        });
        
        $('#contadorItens').text(`${totalItens} ${totalItens === 1 ? 'item' : 'itens'}`);
        $('#totalVenda').text(`R$ ${total.toLocaleString('pt-BR', {minimumFractionDigits: 2})}`);
        $('#totalItensInfo').text(`${totalItens} ${totalItens === 1 ? 'item' : 'itens'}`);
    }
    
    window.alterarQuantidade = function(index, delta) {
        const item = carrinho[index];
        const novaQuantidade = item.quantidade + delta;
        
        if (novaQuantidade <= 0) {
            removerItem(index);
            return;
        }
        
        if (novaQuantidade > item.estoque) {
            Swal.fire('Erro', 'Quantidade não pode ser maior que o estoque disponível.', 'error');
            return;
        }
        
        item.quantidade = novaQuantidade;
        atualizarCarrinho();
    };
    
    window.editarQuantidade = function(index) {
        const item = carrinho[index];
        
        Swal.fire({
            title: 'Editar Quantidade',
            input: 'number',
            inputValue: item.quantidade,
            inputAttributes: {
                min: 1,
                max: item.estoque,
                step: 1
            },
            showCancelButton: true,
            confirmButtonText: 'Alterar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                const novaQuantidade = parseInt(result.value);
                
                if (novaQuantidade > 0 && novaQuantidade <= item.estoque) {
                    item.quantidade = novaQuantidade;
                    atualizarCarrinho();
                } else {
                    Swal.fire('Erro', 'Quantidade inválida.', 'error');
                }
            }
        });
    };
    
    window.removerItem = function(index) {
        carrinho.splice(index, 1);
        atualizarCarrinho();
    };
    
    function limparCarrinho() {
        carrinho = [];
        atualizarCarrinho();
    }
    
    // ====================================
    // FUNÇÕES DE CLIENTE
    // ====================================
    
    window.selecionarCliente = function(id, nome, cpf) {
        clienteSelecionado = { id, nome, cpf };
        
        $('#clienteNome').text(nome);
        $('#clienteCpf').text(`(${cpf})`);
        $('#clienteSelecionado').show();
        $('#clienteAutocomplete').hide();
        $('#clienteSearch').val(nome);
    };
    
    // ====================================
    // FUNÇÕES DE CAIXA
    // ====================================
    
    function abrirCaixa() {
        const valorInicialStr = $('#valorInicialCaixa').val();
        const observacoes = $('#observacoesCaixa').val();
        
        // Converter valor mascarado para float
        const valorInicial = parseValorMonetario(valorInicialStr);
        
        if (valorInicial < 0) {
            Swal.fire('Erro', 'Valor inicial não pode ser negativo.', 'error');
            return;
        }
        
        if (valorInicialStr === '' || valorInicial === 0) {
            Swal.fire('Erro', 'Valor inicial é obrigatório.', 'error');
            return;
        }
        
        $.ajax({
            url: '<?= base_url('pdv/abrirCaixa') ?>',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                valor_inicial: valorInicial,
                observacoes: observacoes
            }),
            success: function(response) {
                if (response.success) {
                    Swal.fire('Sucesso', response.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Erro', response.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Erro', 'Erro ao abrir caixa.', 'error');
            }
        });
    }
    
    function fecharCaixa() {
        const valorFinalStr = $('#valorFinalCaixa').val();
        const observacoes = $('#observacoesFechamento').val();
        
        // Converter valor mascarado para float
        const valorFinal = parseValorMonetario(valorFinalStr);
        
        if (valorFinal < 0) {
            Swal.fire('Erro', 'Valor final não pode ser negativo.', 'error');
            return;
        }
        
        if (valorFinalStr === '' || valorFinal === 0) {
            Swal.fire('Erro', 'Valor final é obrigatório.', 'error');
            return;
        }
        
        $.ajax({
            url: '<?= base_url('pdv/fecharCaixa') ?>',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                valor_final: valorFinal,
                observacoes: observacoes
            }),
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Caixa Fechado!',
                        html: `
                            <p><strong>Resumo do Caixa:</strong></p>
                            <p>Valor Inicial: R$ ${response.resumo.valor_inicial.toLocaleString('pt-BR', {minimumFractionDigits: 2})}</p>
                            <p>Valor Final: R$ ${response.resumo.valor_final.toLocaleString('pt-BR', {minimumFractionDigits: 2})}</p>
                            <p>Diferença: R$ ${response.resumo.diferenca.toLocaleString('pt-BR', {minimumFractionDigits: 2})}</p>
                        `,
                        icon: 'success'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Erro', response.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Erro', 'Erro ao fechar caixa.', 'error');
            }
        });
    }
    
    // ====================================
    // FUNÇÕES DE VENDA
    // ====================================
    
    function prepararFinalizacaoVenda() {
        let total = carrinho.reduce((sum, item) => sum + (item.quantidade * item.preco_unitario), 0);
        
        // Resumo da venda
        let resumoHtml = `
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Qtd</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        carrinho.forEach(item => {
            resumoHtml += `
                <tr>
                    <td>${item.nome}</td>
                    <td>${item.quantidade}</td>
                    <td>R$ ${(item.quantidade * item.preco_unitario).toLocaleString('pt-BR', {minimumFractionDigits: 2})}</td>
                </tr>
            `;
        });
        
        resumoHtml += `
                    </tbody>
                    <tfoot>
                        <tr class="table-primary">
                            <th colspan="2">Total</th>
                            <th>R$ ${total.toLocaleString('pt-BR', {minimumFractionDigits: 2})}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        `;
        
        $('#resumoVenda').html(resumoHtml);
        
        // Informações de pagamento - usar dinheiro como padrão
        $('#infoPagamento').html(`
            <p><strong>Forma de Pagamento:</strong> 💵 Dinheiro</p>
            <p><strong>Valor Total:</strong> R$ ${total.toLocaleString('pt-BR', {minimumFractionDigits: 2})}</p>
            ${clienteSelecionado ? `<p><strong>Cliente:</strong> ${clienteSelecionado.nome}</p>` : '<p><em>Venda sem cliente</em></p>'}
        `);
        
        $('#modalFinalizarVenda').modal('show');
    }
    
    function processarVenda() {
        const observacoes = $('#observacoesVenda').val();
        
        let total = carrinho.reduce((sum, item) => sum + (item.quantidade * item.preco_unitario), 0);
        
        const dadosVenda = {
            cliente_id: clienteSelecionado ? clienteSelecionado.id : null,
            produtos: carrinho.map(item => ({
                produto_id: item.produto_id,
                quantidade: item.quantidade,
                preco_unitario: item.preco_unitario
            })),
            tipo_pagamento: 'dinheiro', // Usar dinheiro como padrão
            valor_total: total,
            valor_pago: total,
            desconto: 0,
            observacoes: observacoes,
            imprimir_nome_cliente: $('#switchImprimirNomeCliente').is(':checked'),
            imprimir_garantias: $('#switchImprimirGarantias').is(':checked')
        };
        
        // Mostrar loading
        Swal.fire({
            title: 'Processando Venda...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        $.ajax({
            url: '<?= base_url('pdv/processarVenda') ?>',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(dadosVenda),
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Venda Realizada!',
                        html: `
                            <p><strong>Número da Venda:</strong> ${response.numero_venda}</p>
                            <p><strong>Total:</strong> R$ ${total.toLocaleString('pt-BR', {minimumFractionDigits: 2})}</p>
                            ${response.cupom ? '<p><em>Cupom gerado automaticamente!</em></p>' : ''}
                        `,
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonText: 'Nova Venda',
                        cancelButtonText: 'Imprimir Cupom',
                        showDenyButton: true,
                        denyButtonText: 'Ver Cupom'
                    }).then((result) => {
                        $('#modalFinalizarVenda').modal('hide');
                        limparCarrinho();
                        clienteSelecionado = null;
                        $('#clienteSelecionado').hide();
                        $('#clienteSearch').val('');
                        $('#produtoSearch').val('').focus();
                        $('#produtosEncontrados').hide();
                        
                        if (result.isConfirmed) {
                            // Nova venda - não fazer nada adicional
                        } else if (result.isDismissed && result.dismiss === Swal.DismissReason.cancel) {
                            // Imprimir cupom diretamente
                            imprimirCupom(response.venda_id);
                        } else if (result.isDenied) {
                            // Visualizar cupom no navegador
                            visualizarCupom(response.venda_id);
                        }
                    });
                } else {
                    Swal.fire('Erro', response.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Erro', 'Erro ao processar venda.', 'error');
            }
        });
    }
    
    function cancelarVenda() {
        if (carrinho.length === 0) {
            Swal.fire('Aviso', 'Não há venda em andamento para cancelar.', 'warning');
            return;
        }
        
        Swal.fire({
            title: 'Cancelar Venda?',
            text: 'Todos os produtos serão removidos do carrinho.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sim, cancelar',
            cancelButtonText: 'Não'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('pdv/cancelarVenda') ?>',
                    method: 'POST',
                    success: function(response) {
                        if (response.success) {
                            limparCarrinho();
                            clienteSelecionado = null;
                            $('#clienteSelecionado').hide();
                            $('#clienteSearch').val('');
                            $('#produtoSearch').val('').focus();
                            $('#produtosEncontrados').hide();
                            
                            Swal.fire('Cancelado', response.message, 'success');
                        }
                    }
                });
            }
        });
    }
    
    // ====================================
    // FUNÇÕES DE CUPOM
    // ====================================
    
    function imprimirCupom(vendaId) {
        // Para impressão direta em impressora térmica
        // Abre o PDF do cupom em uma nova janela e aciona o print automaticamente
        const cupomUrl = `<?= base_url('pdv/downloadCupom/') ?>${vendaId}`;
        
        // Criar janela para impressão
        const printWindow = window.open(cupomUrl, 'cupom', 'width=300,height=600,scrollbars=yes');
        
        // Aguardar carregamento e imprimir automaticamente
        printWindow.onload = function() {
            setTimeout(() => {
                printWindow.print();
                // Fechar janela após impressão (opcional)
                // printWindow.close();
            }, 1000);
        };
    }
    
    function visualizarCupom(vendaId) {
        // Abre o cupom em uma nova aba para visualização
        const cupomUrl = `<?= base_url('pdv/downloadCupom/') ?>${vendaId}`;
        window.open(cupomUrl, '_blank');
    }
    
    // ====================================
    // FUNÇÕES AUXILIARES
    // ====================================
    
    function configurarMascaras() {
        // Aplicar máscara de moeda nos campos de valor
        // Para adicionar máscara a novos campos monetários, adicione a classe 'valor-monetary' ao input
        // ou inclua o ID do campo no seletor abaixo
        $('#valorInicialCaixa, #valorFinalCaixa, .valor-monetary').mask('000.000.000.000.000,00', {
            reverse: true,
            placeholder: "0,00"
        });
    }
    
    // ====================================
    // FUNÇÕES UTILITÁRIAS
    // ====================================
    
    function parseValorMonetario(valorStr) {
        if (!valorStr || valorStr === '') return 0;
        return parseFloat(valorStr.replace(/\./g, '').replace(',', '.')) || 0;
    }
    
    function formatarValorMonetario(valor) {
        return valor.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }
    
    function atualizarInterface() {
        const caixaAberto = <?= json_encode($caixa_status['aberto']) ?>;
        
        if (!caixaAberto) {
            $('#btnFinalizarVenda').prop('disabled', true);
            $('#btnFinalizarVenda').html('<i class="fas fa-lock"></i> Caixa Fechado');
        }
        
        // Inicializar o carrinho para mostrar o total
        atualizarCarrinho();
    }
    
})();
</script>

<?= $this->endSection() ?>
