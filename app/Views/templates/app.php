<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Meu Sistema' ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <style>
        /* Dark theme enhancements */
        /* Header / Footer highlight to stand out from page body */
        .site-header {
            background-color: rgba(255,255,255,0.03); /* subtle lighter bar */
            border-bottom: 1px solid rgba(255,255,255,0.04);
            backdrop-filter: blur(4px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.35);
        }

        .site-footer {
            background-color: rgba(255,255,255,0.02);
            border-top: 1px solid rgba(255,255,255,0.03);
            box-shadow: 0 -2px 6px rgba(0,0,0,0.25);
        }
        .card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
        }
        
        .animate-fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .btn-action {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.075);
        }
    </style>
</head>
<body>
    <!-- Navbar Superior -->
    <?= $this->include('templates/header') ?>

    <!-- Conteúdo Principal -->
    <?= $this->renderSection('content') ?>

    <!--Footer-->
    <?= $this->include('templates/footer') ?>
    
    <!-- jQuery (necessário para algumas funcionalidades) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    
    <!-- jQuery Mask Plugin (used for CPF/phone/CEP masks) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

    <!-- Bootstrap Initialization Script -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializa todos os componentes Bootstrap
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        const popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
        
        // Força a inicialização de todos os dropdowns
        const dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
        const dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
            return new bootstrap.Dropdown(dropdownToggleEl);
        });
        
        console.log('Bootstrap components initialized:', {
            dropdowns: dropdownList.length,
            tooltips: tooltipList.length,
            popovers: popoverList.length
        });
    });
    </script>

    <!-- Scripts específicos da página -->
    <?= $this->renderSection('pagescript') ?>
</body>
</html>