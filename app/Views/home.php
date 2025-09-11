<?= $this->include('templates/header_home') ?>

<div class="row mb-4">
    <div class="col-12">
        <h1 class="h2 mb-1"><?= $title ?? 'Menu Principal' ?></h1>
        <p class="text-muted">Bem-vindo ao painel de controle do sistema</p>
    </div>
</div>

<!-- Grid de 6 colunas e 2 linhas -->
<div class="row g-3 mb-4">
    <!-- Primeira Linha -->
    <div class="col-md-4 col-lg-2">
        <a href="<?= base_url('pdv') ?>" class="text-decoration-none">
            <div class="card h-100 text-center">
                <div class="card-body">
                    <i class="bi bi-display display-4 text-primary mb-3"></i>
                    <h5 class="card-title">PDV</h5>
                    <p class="card-text text-muted">Tela de Vendas</p>
                </div>
            </div>
        </a>
    </div>
    
    <div class="col-md-4 col-lg-2">
        <a href="<?= base_url('clientes') ?>" class="text-decoration-none">
            <div class="card h-100 text-center">
                <div class="card-body">
                    <i class="bi bi-person-lines-fill display-4 text-success mb-3"></i>
                    <h5 class="card-title">Clientes</h5>
                    <p class="card-text text-muted">Gestão de Clientes</p>
                </div>
            </div>
        </a>
    </div>
    
    <div class="col-md-4 col-lg-2">
        <a href="<?= base_url('produtos') ?>" class="text-decoration-none">
            <div class="card h-100 text-center">
                <div class="card-body">
                    <i class="bi bi-box-seam display-4 text-warning mb-3"></i>
                    <h5 class="card-title">Produtos</h5>
                    <p class="card-text text-muted">Controle de Produtos</p>
                </div>
            </div>
        </a>
    </div>
    
    <div class="col-md-4 col-lg-2">
        <a href="<?= base_url('categorias') ?>" class="text-decoration-none">
            <div class="card h-100 text-center">
                <div class="card-body">
                    <i class="bi bi-tags display-4 text-info mb-3"></i>
                    <h5 class="card-title">Categorias</h5>
                    <p class="card-text text-muted">Gerenciar Categorias</p>
                </div>
            </div>
        </a>
    </div>
    
    <div class="col-md-4 col-lg-2">
        <a href="<?= base_url('garantias') ?>" class="text-decoration-none">
            <div class="card h-100 text-center">
                <div class="card-body">
                    <i class="bi bi-shield-check display-4 text-danger mb-3"></i>
                    <h5 class="card-title">Garantias</h5>
                    <p class="card-text text-muted">Central de Garantias</p>
                </div>
            </div>
        </a>
    </div>
    
    <div class="col-md-4 col-lg-2">
        <a href="<?= base_url('fornecedores') ?>" class="text-decoration-none">
            <div class="card h-100 text-center">
                <div class="card-body">
                    <i class="bi bi-truck display-4 text-secondary mb-3"></i>
                    <h5 class="card-title">Fornecedores</h5>
                    <p class="card-text text-muted">Gerenciar Fornecedores</p>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Segunda Linha -->
<div class="row g-3 mb-4">
    <div class="col-md-4 col-lg-2">
        <a href="<?= base_url('relatorios') ?>" class="text-decoration-none">
            <div class="card h-100 text-center">
                <div class="card-body">
                    <i class="bi bi-file-earmark-bar-graph display-4 text-primary mb-3"></i>
                    <h5 class="card-title">Relatórios</h5>
                    <p class="card-text text-muted">Visualizar relatórios</p>
                </div>
            </div>
        </a>
    </div>
    
    <div class="col-md-4 col-lg-2">
        <a href="<?= base_url('usuarios') ?>" class="text-decoration-none">
            <div class="card h-100 text-center">
                <div class="card-body">
                    <i class="bi bi-people-fill display-4 text-success mb-3"></i>
                    <h5 class="card-title">Usuários</h5>
                    <p class="card-text text-muted">Gerenciar usuários</p>
                </div>
            </div>
        </a>
    </div>
    
    <div class="col-md-4 col-lg-2">
        <a href="<?= base_url('servico') ?>" class="text-decoration-none">
            <div class="card h-100 text-center">
                <div class="card-body">
                    <i class="bi bi-tools display-4 text-warning mb-3"></i>
                    <h5 class="card-title">Serviços</h5>
                    <p class="card-text text-muted">Relação dos Serviços</p>
                </div>
            </div>
        </a>
    </div>
    
    <div class="col-md-4 col-lg-2">
        <a href="<?= base_url('perfil') ?>" class="text-decoration-none">
            <div class="card h-100 text-center">
                <div class="card-body">
                    <i class="bi bi-person-circle display-4 text-info mb-3"></i>
                    <h5 class="card-title">Perfil</h5>
                    <p class="card-text text-muted">Gerenciar Perfil</p>
                </div>
            </div>
        </a>
    </div>
    
    <div class="col-md-4 col-lg-2">
        <a href="<?= base_url('ordens') ?>" class="text-decoration-none">
            <div class="card h-100 text-center">
                <div class="card-body">
                    <i class="bi bi-clipboard-check display-4 text-danger mb-3"></i>
                    <h5 class="card-title">Ordens</h5>
                    <p class="card-text text-muted">Gerenciar Ordens de Serviço</p>
                </div>
            </div>
        </a>
    </div>
    
    <div class="col-md-4 col-lg-2">
        <a href="<?= base_url('vendas') ?>" class="text-decoration-none">
            <div class="card h-100 text-center">
                <div class="card-body">
                    <i class="bi bi-cart-check display-4 text-secondary mb-3"></i>
                    <h5 class="card-title">Vendas</h5>
                    <p class="card-text text-muted">Gerenciar Vendas</p>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4 col-lg-2">
        <a href="<?= base_url('tecnicos') ?>" class="text-decoration-none">
            <div class="card h-100 text-center">
                <div class="card-body">
                    <i class="bi bi-tools display-4 text-primary mb-3"></i>
                    <h5 class="card-title">Técnicos</h5>
                    <p class="card-text text-muted">Visualizar técnicos</p>
                </div>
            </div>
        </a>
    </div>
</div>

<?= $this->include('templates/footer_home') ?>