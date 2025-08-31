<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema PDV</title>
    <!-- Bootstrap 5.2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-primary: #1a1a1a;
            --bg-secondary: #2d2d2d;
            --bg-card: #3d3d3d;
            --text-primary: #ffffff;
            --text-secondary: #cccccc;
            --accent-color: #0d6efd;
            --accent-hover: #0b5ed7;
            --danger-color: #dc3545;
            --border-color: #495057;
        }
        
        body {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-primary);
        }
        
        #login {
            width: 100%;
            padding: 20px;
        }
        
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            margin: 0 auto;
        }
        
        .center {
            /* display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center; */
        }
        
        .form-floating {
            margin-bottom: 1rem;
        }
        
        .form-control {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            border-radius: 8px;
        }
        
        .form-control:focus {
            background: var(--bg-secondary);
            border-color: var(--accent-color);
            color: var(--text-primary);
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        
        .form-control::placeholder {
            color: var(--text-secondary);
        }
        
        label {
            color: var(--text-secondary);
        }
        
        .btn-enter {
            background: var(--accent-color);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
        }
        
        .btn-enter:hover {
            background: var(--accent-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
        }
        
        .btn-enter:active {
            transform: translateY(0);
        }
        
        a {
            color: var(--accent-color);
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        a:hover {
            color: var(--accent-hover);
            text-decoration: underline;
        }
        
        .alert {
            background: rgba(220, 53, 69, 0.2);
            border: 1px solid var(--danger-color);
            color: #ff6b6b;
            border-radius: 8px;
        }
        
        .rodape-centralizado {
            text-align: center;
            margin-top: 2rem;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }
        
        .password-container {
            position: relative;
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--text-secondary);
            z-index: 10;
        }
        
        .password-toggle:hover {
            color: var(--accent-color);
        }
        
        .logo-container {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        
        .logo {
            max-width: 60px;
            height: auto;
            margin-bottom: 15px;
            filter: brightness(0) invert(1);
        }
        
        .system-title {
            color: var(--text-primary);
            font-weight: 300;
            margin-bottom: 2rem;
            font-size: 1.5rem;
        }
        
        .loader-container {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
        
        .loader-container::after {
            content: "";
            width: 50px;
            height: 50px;
            border: 5px solid var(--bg-secondary);
            border-top: 5px solid var(--accent-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Responsividade */
        @media (max-width: 576px) {
            .card {
                padding: 1.5rem;
                margin: 0 15px;
            }
            
            .system-title {
                font-size: 1.3rem;
            }
        }
    </style>
</head>
<body>
    <section id="login">
        <div class="container">
            <div class="row">
                <div class="col-md-12 center">
                    <div class="card">
                        <form action="<?= base_url('auth/login') ?>" method="post" id="formLogin">
                            <?= csrf_field() ?>
                            
                            <div class="logo-container">
                                <?php
                                    // Garante que a constante IMG_PATH está disponível
                                    if (!defined('IMG_PATH')) {
                                        require_once APPPATH . 'Config/Constants.php';
                                    }
                                ?>
                                <img src="<?= base_url(IMG_PATH . 'logo.png') ?>" alt="Logo Sistema PDV" class="logo">
                                <h5 class="system-title fw-bold"><?= $appName ?></h5>
                            </div>
                            
                            <div class="form-floating">
                                <input type="text" class="form-control" id="accessUser" name="access-user" 
                                       placeholder="Usuário de Acesso" title="Usuário de Acesso" required>
                                <label for="accessUser"><i class="bi bi-person me-1"></i>Usuário de Acesso</label>
                            </div>
                            
                            <div class="form-floating password-container">
                                <input type="password" class="form-control" id="accessPassword" name="access-password" 
                                       placeholder="Senha de Acesso" title="Senha de Acesso" required>
                                <label for="accessPassword"><i class="bi bi-lock me-1"></i>Senha</label>
                                <span class="password-toggle" id="togglePassword">
                                    <i class="fa-solid fa-eye-slash"></i>
                                </span>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
                                <label class="form-check-label" for="rememberMe">
                                    Lembrar-me
                                </label>
                            </div>
                            
                            <div class="mt-3">
                                <a href="<?= base_url('auth/recuperar-senha') ?>" title="Esqueceu a senha?">
                                    <i class="bi bi-question-circle me-1"></i>Esqueceu a senha?
                                </a>
                            </div>
                            
                            <div class="mt-4">
                                <button class="btn btn-enter" title="Entrar" id="btnLogin" type="submit">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Entrar
                                </button>
                            </div>
                        </form>
                        
                        <!-- Mensagens de alerta -->
                        <div class="mt-3">
                            <?php if (session('error')): ?>
                                <div class="alert alert-danger" role="alert">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    <?= session('error') ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (session('success')): ?>
                                <div class="alert alert-success" role="alert">
                                    <i class="bi bi-check-circle-fill me-2"></i>
                                    <?= session('success') ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (isset($_GET['return']) && $_GET['return'] == 'not_authenticated'): ?>
                                <div class="alert alert-danger" role="alert">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    Você não está autenticado. Faça o login para acessar o sistema.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="rodape-centralizado">
                        &copy; <?= $empresa ?> <?= date('Y') ?> - Todos os direitos reservados
                    </div>
                </div>
            </div>
        </div>
        
        <div class="loader-container" id="loader"></div>
    </section>

    <!-- Bootstrap 5.2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mostrar/ocultar senha
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('accessPassword');
            
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                // Alternar ícone
                const icon = this.querySelector('i');
                if (type === 'password') {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
            
            // Loader no submit do formulário
            const form = document.getElementById('formLogin');
            const loader = document.getElementById('loader');
            
            form.addEventListener('submit', function() {
                loader.style.display = 'flex';
            });
            
            // Focar no campo de usuário ao carregar a página
            document.getElementById('accessUser').focus();
            
            // Validação do formulário
            form.addEventListener('submit', function(e) {
                const user = document.getElementById('accessUser').value.trim();
                const password = document.getElementById('accessPassword').value.trim();
                
                if (!user || !password) {
                    e.preventDefault();
                    alert('Por favor, preencha todos os campos obrigatórios.');
                    loader.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>