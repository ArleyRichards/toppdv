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
    
    <style>
        :root {
            --bs-body-bg: #1a1a1a;
            --bs-body-color: #ffffff;
        }
        
        .card {
            background-color: #2d2d2d;
            border: 1px solid #404040;
            transition: transform 0.2s ease;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
        }
        
        .navbar-brand, .nav-link {
            color: #ffffff !important;
        }
        
        .nav-link:hover {
            color: #0dcaf0 !important;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="<?= base_url('home') ?>">
            <img src="<?= base_url(IMG_PATH . 'logo.png') ?>" alt="Logo Sistema PDV" class="logo me-2" style="height:32px;">
            <span class="fw-bold">
                <!-- <i class="bi bi-house-fill me-2"></i> -->
                <?= $appName ?? 'Meu Sistema' ?>
            </span>
        </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-1"></i>
                            <span class="d-none d-md-inline"><?= esc(session('user_nome') ?? session('user_usuario') ?? 'Usuário') ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="<?= base_url('configuracoes') ?>"><i class="bi bi-gear me-2"></i> Configurações</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('perfil') ?>"><i class="bi bi-person-circle me-2"></i> Perfil</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('licenca') ?>"><i class="bi bi-award me-2"></i> Licença</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?= base_url('logout') ?>"><i class="bi bi-box-arrow-right me-2"></i> Sair</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Conteúdo Principal -->
    <main class="container py-4">