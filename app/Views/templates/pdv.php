<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'PDV - Ponto de Venda' ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <style>
    /* Removed global dark theme variables so child views can control colors */
        
        * {
            box-sizing: border-box;
        }
        
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .pdv-fullscreen {
            height: 100vh;
            width: 100vw;
            display: flex;
            flex-direction: column;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        }
        
        .pdv-content {
            flex: 1;
            overflow: auto;
            padding: 0;
        }
        
        .animate-fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Scrollbar personalizada */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #2d2d2d;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #555;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #777;
        }
        
        /* Remover margens e paddings desnecessários */
        .container-fluid {
            padding-left: 0;
            padding-right: 0;
            margin: 0;
            max-width: 100%;
        }
        
        /* Ajustes para mobile */
        @media (max-width: 768px) {
            .pdv-fullscreen {
                height: 100vh;
                height: calc(var(--vh, 1vh) * 100);
            }
        }
    </style>
    
    <!-- Script para lidar com altura viewport no mobile -->
    <script>
        function setVH() {
            let vh = window.innerHeight * 0.01;
            document.documentElement.style.setProperty('--vh', `${vh}px`);
        }
        setVH();
        window.addEventListener('resize', setVH);
        window.addEventListener('orientationchange', setVH);
    </script>
</head>
<body>
    <div class="pdv-fullscreen">
        <div class="pdv-content">
            <?= $this->renderSection('content') ?>
        </div>
    </div>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>    
    <!-- jQuery Mask Plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

    <?= $this->renderSection('scripts') ?>
</body>
</html>
