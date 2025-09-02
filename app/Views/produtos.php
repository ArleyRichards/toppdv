<?= $this->extend('templates/app') ?>

<?= $this->section('content') ?>
<div class="container-fluid" style="margin-top: 10px; padding: 15px;">
    <!-- Cabeçalho -->
    <div class="row mb-3 animate-fade-in">
        <div class="col-md-6">
            <h2><i class="fa-solid fa-users text-primary me-2"></i> Lista de Produtos</h2>
            <p class="text-muted" style="font-size: 14px;">Gerencie todos os produtos cadastrados no sistema</p>
        </div>
        <div class="col-md-6 text-end">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="toggleFilters()">
                    <i class="fa-solid fa-filter me-1"></i> Filtros
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#addProductModal">
                    <i class="fa-solid fa-plus me-1"></i> Novo
                </button>
                <a href="<?= site_url('cadastro-cliente') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fa-solid fa-external-link me-1"></i> Cadastro Antigo
                </a>
                <a href="<?= site_url('home') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fa-solid fa-home me-1"></i> Menu
                </a>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4 animate-fade-in" id="filtersContainer" style="display: none;">
        <div class="card-header">
            <h5 class="mb-0"><i class="fa-solid fa-filter me-2"></i> Filtros de Pesquisa</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-lg-3 col-md-4">
                    <label for="filterName" class="form-label">Nome do Produto</label>
                    <input type="text" class="form-control" id="filterName" placeholder="Digite o nome do produto...">
                </div>
                <div class="col-lg-2 col-md-3">
                    <label for="filterCode" class="form-label">Código</label>
                    <input type="text" class="form-control" id="filterCode" placeholder="Código...">
                </div>
                <div class="col-lg-2 col-md-3">
                    <label for="filterCategory" class="form-label">Categoria</label>
                    <select class="form-select" id="filterCategory">
                        <option value="">Todas as categorias</option>
                        <?php if (!empty($categorias)): ?>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?= esc($cat->c1_id) ?>"><?= esc($cat->c1_categoria) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-lg-2 col-md-3">
                    <label for="filterProvider" class="form-label">Fornecedor</label>
                    <select class="form-select" id="filterProvider">
                        <option value="">Todos os fornecedores</option>
                        <?php if (!empty($fornecedores)): ?>
                            <?php foreach ($fornecedores as $f): ?>
                                <option value="<?= esc($f->f1_id) ?>"><?= esc($f->f1_nome_fantasia) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-lg-2 col-md-3">
                    <label for="filterWarranty" class="form-label">Garantia</label>
                    <select class="form-select" id="filterWarranty">
                        <option value="">Todas as garantias</option>
                        <?php if (!empty($garantias)): ?>
                            <?php foreach ($garantias as $g): ?>
                                <option value="<?= esc($g->g1_id) ?>"><?= esc($g->g1_nome) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-lg-1 col-md-2 d-flex align-items-end">
                    <button class="btn btn-outline-danger btn-sm w-100" id="clearFilters">
                        <i class="fa-solid fa-eraser"></i>
                    </button>
                </div>
            </div>
            <div class="row g-3 mt-1">
                <div class="col-md-3">
                    <label for="filterStock" class="form-label">Status do Estoque</label>
                    <select class="form-select" id="filterStock">
                        <option value="">Todos</option>
                        <option value="low">Estoque baixo (≤ 5)</option>
                        <option value="medium">Estoque médio (6-20)</option>
                        <option value="high">Estoque alto (> 20)</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de produtos -->
    <div class="card animate-fade-in">
        <div class="card-header">
            <h5 class="mb-0"><i class="fa-solid fa-table me-2"></i> Produtos Cadastrados</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th width="60">Imagem</th>
                            <th>Nome</th>
                            <th>Código</th>
                            <th>Categoria</th>
                            <th>Fornecedor</th>
                            <th width="80">Estoque</th>
                            <th width="120">Preço Venda</th>
                            <th width="120">Garantia</th>
                            <th width="150">Ações</th>
                        </tr>
                    </thead>
                    <tbody id="productsTableBody">
                        <!-- Será preenchido via JavaScript -->
                    </tbody>
                </table>
            </div>

            <!-- Controles de Paginação -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="d-flex align-items-center">
                    <label for="itemsPerPage" class="form-label me-2">Itens por página:</label>
                    <select class="form-select form-select-sm" id="itemsPerPage" style="width: auto;">
                        <option value="10">10</option>
                        <option value="25" selected>25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <div id="paginationInfo" class="text-muted"></div>
            </div>

            <!-- Paginação -->
            <nav aria-label="Navegação da página" class="mt-3">
                <ul class="pagination justify-content-center" id="pagination">
                    <!-- Será preenchido via JavaScript -->
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Modal de visualização -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">
                    <i class="fa-solid fa-eye text-primary me-2"></i>Detalhes do Produto
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
            </div>
            <div class="modal-body" id="productModalBody">
                <!-- Conteúdo será preenchido via JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa-solid fa-times me-1"></i>Fechar
                </button>
                <button type="button" class="btn btn-primary" id="editProductBtn">
                    <i class="fa-solid fa-edit me-1"></i>Editar Produto
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de cadastro de produto -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">
                    <i class="fa-solid fa-plus text-success me-2"></i>Cadastrar Novo Produto
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
            </div>
            <div class="modal-body">
                <form id="addProductForm" class="needs-validation" novalidate>
                    <?= csrf_field() ?>

                    <div class="row g-3">
                        <!-- Imagem do produto -->
                        <div class="col-md-12">
                            <div class="text-center mb-3">
                                <div class="border border-2 border-dashed rounded p-3" id="imageUploadArea">
                                    <img id="imagePreview" src="<?= IMG_PATH . 'default-product.webp' ?>" alt="Preview" class="img-thumbnail mb-2" style="max-width: 200px; max-height: 200px;">
                                    <div>
                                        <label for="img-product" class="btn btn-outline-primary">
                                            <i class="fa-solid fa-upload"></i> Selecionar Imagem
                                        </label>
                                        <input type="file" class="form-control d-none" id="img-product" name="img-product" accept="image/*">
                                    </div>
                                    <small class="text-muted d-block mt-2">Formatos aceitos: JPG, PNG, WEBP (máx. 5MB)</small>
                                </div>
                            </div>
                        </div>

                        <!-- Nome do produto -->
                        <div class="col-md-6">
                            <label for="product-name" class="form-label">Nome do Produto <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="product-name" name="product-name" required>
                        </div>

                        <!-- Código do produto -->
                        <div class="col-md-6">
                            <label for="product-code" class="form-label">Código do Produto <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="product-code" name="product-code" required>
                        </div>

                        <!-- Categoria -->
                        <div class="col-md-4">
                            <label for="categoria_id" class="form-label">Categoria <span class="text-danger">*</span></label>
                            <select class="form-select" id="categoria_id" name="categoria_id" required>
                                <option value="">Selecione uma categoria</option>
                                <?php if (!empty($categorias)): ?>
                                    <?php foreach ($categorias as $cat): ?>
                                        <option value="<?= esc($cat->c1_id) ?>"><?= esc($cat->c1_categoria) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- Fornecedor -->
                        <div class="col-md-4">
                            <label for="product-supplier" class="form-label">Fornecedor <span class="text-danger">*</span></label>
                            <select class="form-select" id="product-supplier" name="product-supplier" required>
                                <option value="">Selecione um fornecedor</option>
                                <?php if (!empty($fornecedores)): ?>
                                    <?php foreach ($fornecedores as $f): ?>
                                        <option value="<?= esc($f->f1_id) ?>"><?= esc($f->f1_nome_fantasia) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- Garantia -->
                        <div class="col-md-4">
                            <label for="garantia_id" class="form-label">Garantia <span class="text-danger">*</span></label>
                            <select class="form-select" id="garantia_id" name="garantia_id" required>
                                <option value="">Selecione uma garantia</option>
                                <?php if (!empty($garantias)): ?>
                                    <?php foreach ($garantias as $g): ?>
                                        <option value="<?= esc($g->g1_id) ?>"><?= esc($g->g1_nome) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- Quantidade -->
                        <div class="col-md-3">
                            <label for="product-qnt" class="form-label">Quantidade <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="product-qnt" name="product-qnt" min="0" required>
                        </div>

                        <!-- Preço unitário -->
                        <div class="col-md-3">
                            <label for="product-unit-price" class="form-label">Preço Unitário</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="text" class="form-control" id="product-unit-price" name="product-unit-price" placeholder="0,00">
                            </div>
                        </div>

                        <!-- Preço de compra -->
                        <div class="col-md-3">
                            <label for="product-purchase-price" class="form-label">Preço de Compra</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="text" class="form-control" id="product-purchase-price" name="product-purchase-price" placeholder="0,00">
                            </div>
                        </div>

                        <!-- Preço de venda -->
                        <div class="col-md-3">
                            <label for="product-sale-price" class="form-label">Preço de Venda <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="text" class="form-control" id="product-sale-price" name="product-sale-price" placeholder="0,00" required>
                            </div>
                        </div>
                    </div>

                    <!-- Campo oculto para total-price-on-product -->
                    <input type="hidden" id="total-price-on-product" name="total-price-on-product" value="0,00">

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa-solid fa-times me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-success" id="saveProductBtn">
                    <i class="fa-solid fa-save me-1"></i>Salvar Produto
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Edição de Produto -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">
                    <i class="fa-solid fa-edit text-warning me-2"></i>Editar Produto
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
            </div>
            <div class="modal-body">
                <form id="editProductForm" class="needs-validation" novalidate>
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" id="edit-product-id">

                    <div class="row g-3">
                        <!-- Imagem do produto -->
                        <div class="col-md-12">
                            <div class="text-center mb-3">
                                <div class="border border-2 border-dashed rounded p-3" id="editImageUploadArea">
                                    <img id="editImagePreview" src="<?= IMG_PATH . 'default-product.webp' ?>" alt="Preview" class="img-thumbnail mb-2" style="max-width: 200px; max-height: 200px;">
                                    <div>
                                        <label for="edit-img-product" class="btn btn-outline-primary">
                                            <i class="fa-solid fa-upload"></i> Alterar Imagem
                                        </label>
                                        <input type="file" class="form-control d-none" id="edit-img-product" name="img-product" accept="image/*">
                                    </div>
                                    <small class="text-muted d-block mt-2">Formatos aceitos: JPG, PNG, WEBP (máx. 5MB)</small>
                                </div>
                            </div>
                        </div>

                        <!-- Nome do Produto -->
                        <div class="col-md-6">
                            <label for="edit-product-name" class="form-label">Nome do Produto <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit-product-name" name="product-name" required>
                        </div>

                        <!-- Código do Produto -->
                        <div class="col-md-6">
                            <label for="edit-product-code" class="form-label">Código do Produto <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit-product-code" name="product-code" required>
                        </div>

                        <!-- Categoria -->
                        <div class="col-md-4">
                            <label for="edit-categoria-id" class="form-label">Categoria <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit-categoria-id" name="categoria_id" required>
                                <option value="">Selecione uma categoria</option>
                                <?php if (!empty($categorias)): ?>
                                    <?php foreach ($categorias as $cat): ?>
                                        <option value="<?= esc($cat->c1_id) ?>"><?= esc($cat->c1_categoria) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- Fornecedor -->
                        <div class="col-md-4">
                            <label for="edit-product-supplier" class="form-label">Fornecedor <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit-product-supplier" name="product-supplier" required>
                                <option value="">Selecione um fornecedor</option>
                                <?php if (!empty($fornecedores)): ?>
                                    <?php foreach ($fornecedores as $f): ?>
                                        <option value="<?= esc($f->f1_id) ?>"><?= esc($f->f1_nome_fantasia) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- Garantia -->
                        <div class="col-md-4">
                            <label for="edit-garantia-id" class="form-label">Garantia <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit-garantia-id" name="garantia_id" required>
                                <option value="">Selecione uma garantia</option>
                                <?php if (!empty($garantias)): ?>
                                    <?php foreach ($garantias as $g): ?>
                                        <option value="<?= esc($g->g1_id) ?>"><?= esc($g->g1_nome) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- Quantidade -->
                        <div class="col-md-3">
                            <label for="edit-product-qnt" class="form-label">Quantidade <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="edit-product-qnt" name="product-qnt" min="0" required>
                        </div>

                        <!-- Preço Unitário -->
                        <div class="col-md-3">
                            <label for="edit-product-unit-price" class="form-label">Preço Unitário</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="text" class="form-control" id="edit-product-unit-price" name="product-unit-price" placeholder="0,00">
                            </div>
                        </div>

                        <!-- Preço de Compra -->
                        <div class="col-md-3">
                            <label for="edit-product-purchase-price" class="form-label">Preço de Compra</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="text" class="form-control" id="edit-product-purchase-price" name="product-purchase-price" placeholder="0,00">
                            </div>
                        </div>

                        <!-- Preço de Venda -->
                        <div class="col-md-3">
                            <label for="edit-product-sale-price" class="form-label">Preço de Venda <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="text" class="form-control" id="edit-product-sale-price" name="product-sale-price" placeholder="0,00" required>
                            </div>
                        </div>
                    </div>

                    <!-- Campo oculto para total-price-on-product -->
                    <input type="hidden" id="edit-total-price-on-product" name="total-price-on-product" value="0,00">

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa-solid fa-times me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-primary" id="updateProductBtn">
                    <i class="fa-solid fa-save me-1"></i>Atualizar Produto
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('pagescript') ?>

<script src="<?= JS_PATH.'jquery.mask.min.js' ?>"></script>
<script>
    // Variáveis globais
    let productsData = [];
    let filteredProducts = [];
    let currentPage = 1; //PÁGINA ATUAL
    let itemsPerPage = 25; //ITENS POR PÁGINA

    // Configurações de máscaras
    const masks = {
        cpf: '000.000.000-00',
        phone: '(00) 0000-0000',
        mobile: '(00) 00000-0000',
        cep: '00000-000'
    };

    // Retorna caminho absoluto da imagem do produto ou a imagem default em /assets/img/default-product.webp
    // Normaliza valores que já contenham caminho (ex: 'public/assets/img/default-product.webp')
    function productImagePath(filename) {
    const defaultImg = '<?= IMG_PATH . "default-product.webp" ?>';

        if (!filename || filename === null || filename === 'null' || filename === 'produto-sem-imagem.webp') {
            return defaultImg;
        }

        // Se já for uma URL absoluta (começa com / ou http), apenas retorne
        if (typeof filename === 'string' && (filename.startsWith('/') || filename.startsWith('http://') || filename.startsWith('https://'))) {
            return filename;
        }

        // Se o valor armazenado vier como 'public/assets/img/...' normalize para '/assets/img/...'
        if (typeof filename === 'string' && filename.indexOf('public/assets/img') !== -1) {
            // remover possível 'public/' prefix
            return '/' + filename.replace(/^public\//, '');
        }

        // por fim, assumimos que é apenas o filename salvo em /assets/img/products/
        return  '<?= IMG_PATH ?>/products/' + filename;
    }

    // Inicialização quando o DOM estiver carregado
    $(document).ready(function() {
        loadProducts();
        setupEventListeners();
        setupMasks();
    });

    // Toggle dos Filtros com jQuery
    function toggleFilters() {
        const $container = $('#filtersContainer');
        const $btn = $('button').filter(function() {
            return $(this).attr('onclick') === 'toggleFilters()';
        });

        if ($container.length && $btn.length) {
            if ($container.css('display') === 'none' || $container.css('display') === '') {
                $container.show();
                $btn.html('<i class="fa-solid fa-filter me-1"></i>Ocultar Filtros');
                $btn.removeClass('btn-outline-secondary').addClass('btn-warning');
            } else {
                $container.hide();
                $btn.html('<i class="fa-solid fa-filter me-1"></i>Filtros');
                $btn.removeClass('btn-warning').addClass('btn-outline-secondary');
            }
        }
    }
    
    /**
     * Carrega os dados dos produtos via AJAX GET.
     * @author Arley Richards <arleyrichards@gmail.com>
     */
    function setupEventListeners() {
        // Filtros de produtos
        $('#filterName, #filterCode').on('input', function() {
            currentPage = 1;
            applyFilters();
        });
        $('#filterCategory, #filterProvider, #filterWarranty, #filterStock').on('change', function() {
            currentPage = 1;
            applyFilters();
        });
        $('#clearFilters').on('click', function() {
            clearFilters();
        });

        // Paginação
        $('#itemsPerPage').on('change', function() {
            itemsPerPage = parseInt($(this).val()) || 25;
            currentPage = 1;
            renderTable();
        });

        // Botões de ação
        $('#saveProductBtn').on('click', typeof saveProduct === 'function' ? saveProduct : function() {});
        $('#updateProductBtn').on('click', typeof updateProduct === 'function' ? updateProduct : function() {});
        $('#editProductBtn').on('click', function() {
            const productId = $(this).data('product-id');
            if (productId) {
                openEditModal(productId);
            }
        });

        // Preview de imagem ao selecionar arquivo (cadastro e edição)
        $('#img-product').on('change', function() {
            const file = this.files && this.files[0];
            if (!file) return;
            // validar tipo e tamanho (<= 5MB)
            if (!file.type.startsWith('image/')) {
                showAlert('error', 'Por favor selecione um arquivo de imagem.');
                this.value = '';
                return;
            }
            if (file.size > 5 * 1024 * 1024) {
                showAlert('error', 'Imagem muito grande. Máx. 5MB.');
                this.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
        });

        $('#edit-img-product').on('change', function() {
            const file = this.files && this.files[0];
            if (!file) return;
            if (!file.type.startsWith('image/')) {
                showAlert('error', 'Por favor selecione um arquivo de imagem.');
                this.value = '';
                return;
            }
            if (file.size > 5 * 1024 * 1024) {
                showAlert('error', 'Imagem muito grande. Máx. 5MB.');
                this.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#editImagePreview').attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
        });

    // Client-related auxiliary listeners removed (not used on products page)
    }

    // Configurar máscaras nos campos
    function setupMasks() {

        /**
         * Visualiza os detalhes de um produto.
         * @param {number} id
         * @author Arley Richards <arleyrichards@gmail.com>
         */
    // Client-specific masks removed; keeping only product masks below
        // Money masks for product price fields using jQuery Mask (PT-BR)
        try {
            const moneyPattern = '000.000.000.000.000,00';
            $('#product-unit-price, #product-purchase-price, #product-sale-price, #edit-product-unit-price, #edit-product-purchase-price, #edit-product-sale-price').mask(moneyPattern, { reverse: true });
        } catch (e) {
            // plugin not available or error
            console.warn('jQuery Mask not applied:', e);
        }
    }

    /**
     * Aplicar máscara a um campo
     * @author Arley Richards <arleyrichards@gmail.com>
     */
    // ...using jQuery Mask plugin instead of custom applyMask/maskValue

    // Client-related helper functions (age, CEP lookup) removed

    // Carregar dados dos produtos (consome ProdutoController::list)
    async function loadProducts() {
        try {
            const response = await $.get('<?= site_url('/produtos/list') ?>');

            if (Array.isArray(response)) {
                productsData = response;
                filteredProducts = [...productsData];
                renderTable();
            } else {
                showAlert('error', 'Erro ao carregar produtos');
            }
        } catch (error) {
            console.error('Erro:', error);
            showAlert('error', 'Erro ao carregar dados dos produtos');
        }
    }

    // Aplicar filtros
    function applyFilters() {
        const filters = {
            name: ($('#filterName').val() || '').toLowerCase(),
            code: ($('#filterCode').val() || '').toLowerCase(),
            category: $('#filterCategory').val() || '',
            provider: $('#filterProvider').val() || '',
            warranty: $('#filterWarranty').val() || '',
            stock: $('#filterStock').val() || ''
        };

        filteredProducts = productsData.filter(product => {
            // Filtros principais
            const nameMatch = !filters.name || (product.p1_nome_produto && product.p1_nome_produto.toLowerCase().includes(filters.name));
            const codeMatch = !filters.code || (product.p1_codigo_produto && product.p1_codigo_produto.toLowerCase().includes(filters.code));

            // Categoria, Fornecedor, Garantia: aceitar vazio, '-' ou valor legível
            // Tentar corresponder pelo id (quando select usa id) ou pelo nome (quando produto tem nome legível)
            const prodCategoryId = (product.c1_id ?? product.p1_categoria_id ?? product.c1_categoria_id ?? '') + '';
            const prodCategoryName = (product.c1_categoria ?? product.p1_categoria ?? '') + '';

            const prodProviderId = (product.f1_id ?? product.p1_fornecedor_id ?? product.f1_fornecedor_id ?? '') + '';
            const prodProviderName = (product.f1_nome_fantasia ?? product.f1_nome ?? product.p1_fornecedor ?? '') + '';

            const prodWarrantyId = (product.g1_id ?? product.p1_garantia_id ?? '') + '';
            const prodWarrantyName = (product.g1_nome ?? product.p1_garantia ?? '') + '';

            const categoryMatch = !filters.category || filters.category === prodCategoryId || filters.category === prodCategoryName || filters.category.toLowerCase() === prodCategoryName.toLowerCase();
            const providerMatch = !filters.provider || filters.provider === prodProviderId || filters.provider === prodProviderName || filters.provider.toLowerCase() === prodProviderName.toLowerCase();
            const warrantyMatch = !filters.warranty || filters.warranty === prodWarrantyId || filters.warranty === prodWarrantyName || filters.warranty.toLowerCase() === prodWarrantyName.toLowerCase();

            // Filtro de estoque
            let stockMatch = true;
            if (filters.stock) {
                const qty = Number(product.p1_quantidade_produto);
                if (filters.stock === 'low') stockMatch = qty <= 5;
                else if (filters.stock === 'medium') stockMatch = qty >= 6 && qty <= 20;
                else if (filters.stock === 'high') stockMatch = qty > 20;
            }

            return nameMatch && codeMatch && categoryMatch && providerMatch && warrantyMatch && stockMatch;
        });

        currentPage = 1;
        renderTable();
    }

    // Limpar filtros
    function clearFilters() {
        $('#filterName, #filterCode').val('');
        $('#filterCategory, #filterProvider, #filterWarranty, #filterStock').val('');

        filteredProducts = [...productsData];
        currentPage = 1;
        renderTable();
    }

    // Renderizar tabela
    function renderTable() {
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const pageData = filteredProducts.slice(startIndex, endIndex);

        const $tbody = $('#productsTableBody');
        $tbody.empty();

        if (pageData.length === 0) {
            $tbody.html(`
                <tr>
                    <td colspan="9" class="text-center text-muted">
                        <i class="fa-solid fa-inbox me-2"></i>
                        Nenhum produto encontrado
                    </td>
                </tr>
            `);
        } else {
            pageData.forEach(product => {
                const img = productImagePath(product.p1_imagem_produto);
                const nome = product.p1_nome_produto || '-';
                const codigo = product.p1_codigo_produto || '-';
                const categoria = product.c1_categoria || '-';
                const fornecedor = product.f1_nome_fantasia || '-';
                const estoque = product.p1_quantidade_produto != null ? product.p1_quantidade_produto : '-';
                const precoVenda = product.p1_preco_venda_produto != null ? Number(product.p1_preco_venda_produto).toFixed(2) : '0.00';
                const garantia = product.g1_nome || '-';

                const row = `
                    <tr>
                        <td><img src="${img}" alt="${nome}" class="img-fluid" style="max-width:50px;"></td>
                        <td>${nome}</td>
                        <td>${codigo}</td>
                        <td>${categoria}</td>
                        <td>${fornecedor}</td>
                        <td class="text-center">${estoque}</td>
                        <td>R$ ${precoVenda}</td>
                        <td>${garantia}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-primary btn-action" onclick="viewProduct(${product.p1_id})" title="Visualizar"><i class="fa-solid fa-eye"></i></button>
                                <button type="button" class="btn btn-warning btn-action" onclick="openEditModal(${product.p1_id})" title="Editar"><i class="fa-solid fa-edit"></i></button>
                                <button type="button" class="btn btn-danger btn-action" onclick="deleteProduct(${product.p1_id}, '${(nome+'').replace(/'/g, "\\'")}')" title="Excluir"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                `;
                $tbody.append(row);
            });
        }

        updatePaginationInfo();
        renderPagination();
    }

    // Client-specific table row renderer removed

    // formatarCpf removed (client-specific)

    // Currency masking is handled by jQuery Mask plugin (initialized in setupMasks)

    // formatarTelefone removed (client-specific)

    // Retorna badge HTML para situação
    function getBadgeSituacao(situacao) {
        const classes = {
            'Ativo': 'bg-success',
            'Inativo': 'bg-secondary',
            'Pendente': 'bg-warning',
            'Bloqueado': 'bg-danger',
            'Adimplente': 'bg-success',
            'Inadimplente': 'bg-danger',
            'Atrasado': 'bg-warning',
            'Negociado': 'bg-info',
            'Ajuizado': 'bg-dark',
            'Quitado': 'bg-success'
        };

        const classe = classes[situacao] || 'bg-secondary';
        return `<span class="badge ${classe}">${situacao}</span>`;
    }

    // Atualizar informações da paginação
    function updatePaginationInfo() {
        const start = Math.min((currentPage - 1) * itemsPerPage + 1, filteredProducts.length);
        const end = Math.min(currentPage * itemsPerPage, filteredProducts.length);
        const total = filteredProducts.length;

        $('#paginationInfo').text(`Mostrando ${start} a ${end} de ${total} registros`);
    }

    // Renderizar paginação
    function renderPagination() {
        const totalPages = Math.ceil(filteredProducts.length / itemsPerPage);
        const $pagination = $('#pagination');
        $pagination.empty();

        if (totalPages <= 1) return;

        // Botão anterior
        const prevDisabled = currentPage === 1 ? 'disabled' : '';
        $pagination.append(`
            <li class="page-item ${prevDisabled}">
                <a class="page-link" href="#" onclick="changePage(${currentPage - 1}); return false;">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
            </li>
        `);

        // Páginas
        const startPage = Math.max(1, currentPage - 2);
        const endPage = Math.min(totalPages, currentPage + 2);

        for (let i = startPage; i <= endPage; i++) {
            const active = i === currentPage ? 'active' : '';
            $pagination.append(`
                <li class="page-item ${active}">
                    <a class="page-link" href="#" onclick="changePage(${i}); return false;">${i}</a>
                </li>
            `);
        }

        // Botão próximo
        const nextDisabled = currentPage === totalPages ? 'disabled' : '';
        $pagination.append(`
            <li class="page-item ${nextDisabled}">
                <a class="page-link" href="#" onclick="changePage(${currentPage + 1}); return false;">
                    <i class="fa-solid fa-chevron-right"></i>
                </a>
            </li>
        `);
    }

    // Mudar página
    function changePage(page) {
        const totalPages = Math.ceil(filteredProducts.length / itemsPerPage);
        if (page >= 1 && page <= totalPages) {
            currentPage = page;
            renderTable();
        }
        return false;
    }

    // Visualizar produto
    function viewProduct(id) {
        const product = productsData.find(p => Number(p.p1_id) === Number(id));
        if (product) {
            showProductDetails(product);
            return;
        }

        // fallback: buscar do servidor
        $.ajax({
            url: `<?= site_url('/produtos/') ?>${id}`,
            method: 'GET',
            dataType: 'json'
        }).done(function(resp) {
            if (resp) showProductDetails(resp);
            else showAlert('error', 'Produto não encontrado');
        }).fail(function() {
            showAlert('error', 'Erro ao buscar produto');
        });
    }

    // Mostrar detalhes do produto no modal
    function showProductDetails(product) {
        const dataFabricacao = product.p1_data_fabricacao ?
            new Date(product.p1_data_fabricacao).toLocaleDateString('pt-BR') : '-';
        const dataCadastro = product.p1_created_at ?
            new Date(product.p1_created_at).toLocaleDateString('pt-BR') : '-';

    const img = productImagePath(product.p1_imagem_produto);
        const nome = product.p1_nome_produto || '-';
        const codigo = product.p1_codigo_produto || '-';
        const categoria = product.c1_categoria || product.p1_categoria || '-';
        const fornecedor = product.f1_nome_fantasia || product.f1_nome || '-';
        const garantia = product.g1_nome || '-';
        const estoque = product.p1_quantidade_produto != null ? product.p1_quantidade_produto : '-';
        const precoUnit = product.p1_preco_unitario_produto != null ? Number(product.p1_preco_unitario_produto).toFixed(2) : '0.00';
        const precoCompra = product.p1_preco_compra_produto != null ? Number(product.p1_preco_compra_produto).toFixed(2) : '0.00';
        const precoVenda = product.p1_preco_venda_produto != null ? Number(product.p1_preco_venda_produto).toFixed(2) : '0.00';

        $('#productModalBody').html(`
            <div class="row g-3">
                <div class="col-md-4 text-center">
                    <img src="${img}" alt="${nome}" class="img-fluid img-thumbnail" style="max-width:240px;">
                </div>
                <div class="col-md-8">
                    <h5 class="mb-1">${nome}</h5>
                    <p class="mb-1"><strong>Código:</strong> ${codigo}</p>
                    <p class="mb-1"><strong>Categoria:</strong> ${categoria}</p>
                    <p class="mb-1"><strong>Fornecedor:</strong> ${fornecedor}</p>
                    <p class="mb-1"><strong>Garantia:</strong> ${garantia}</p>
                    <p class="mb-1"><strong>Estoque:</strong> ${estoque}</p>
                    <p class="mb-1"><strong>Preço Unitário:</strong> R$ ${precoUnit}</p>
                    <p class="mb-1"><strong>Preço de Compra:</strong> R$ ${precoCompra}</p>
                    <p class="mb-1"><strong>Preço de Venda:</strong> R$ ${precoVenda}</p>
                    <p class="mb-1"><strong>Data de Fabricação:</strong> ${dataFabricacao}</p>
                    <p class="mb-1"><strong>Data de Cadastro:</strong> ${dataCadastro}</p>
                </div>
                <div class="col-12 mt-2">
                    <hr>
                    <p><small class="text-muted">ID: ${product.p1_id || ''}</small></p>
                </div>
            </div>
        `);

        // Configurar botão de editar (para abrir modal de edição)
        $('#editProductBtn').data('product-id', product.p1_id);

        // Mostrar modal
        const modal = new bootstrap.Modal(document.getElementById('productModal'));
        modal.show();
    }

    // Abrir modal de edição (produto) e preencher campos
    function openEditModal(id) {
        const product = productsData.find(p => Number(p.p1_id) === Number(id));
        if (product) {
            fillProductEditForm(product);
            const modal = new bootstrap.Modal(document.getElementById('editProductModal'));
            modal.show();
            return;
        }

        // fallback: buscar do servidor
        $.ajax({
            url: `<?= site_url('/produtos/') ?>${id}`,
            method: 'GET',
            dataType: 'json'
        }).done(function(resp) {
            if (resp) {
                fillProductEditForm(resp);
                const modal = new bootstrap.Modal(document.getElementById('editProductModal'));
                modal.show();
            } else showAlert('error', 'Produto não encontrado');
        }).fail(function() {
            showAlert('error', 'Erro ao buscar produto');
        });
    }

    // Preencher formulário de edição com dados do produto
    function fillProductEditForm(product) {
        $('#edit-product-id').val(product.p1_id || '');
        $('#edit-product-name').val(product.p1_nome_produto || '');
        $('#edit-product-code').val(product.p1_codigo_produto || '');
    // Categoria: aceitar diferentes formatos de retorno (p1_categoria_id, c1_id, etc.)
    const categoriaVal = (product.p1_categoria_id ?? product.c1_id ?? product.p1_categoria) || '';
    let categoriaSelected = categoriaVal ? String(categoriaVal) : '';
    if (!categoriaSelected && product.c1_categoria) {
        const opt = $('#edit-categoria-id option').filter(function() { return $(this).text().trim() === String(product.c1_categoria).trim(); }).first();
        if (opt && opt.length) categoriaSelected = opt.val();
    }
    $('#edit-categoria-id').val(categoriaSelected ? String(categoriaSelected) : '').trigger('change');

    // Fornecedor: aceitar p1_fornecedor_id ou f1_id
    const supplierVal = (product.p1_fornecedor_id ?? product.f1_id ?? product.p1_fornecedor) || '';
    let supplierSelected = supplierVal ? String(supplierVal) : '';
    if (!supplierSelected && (product.f1_nome_fantasia || product.f1_nome)) {
        const supplierName = product.f1_nome_fantasia || product.f1_nome;
        const opt = $('#edit-product-supplier option').filter(function() { return $(this).text().trim() === String(supplierName).trim(); }).first();
        if (opt && opt.length) supplierSelected = opt.val();
    }
    $('#edit-product-supplier').val(supplierSelected ? String(supplierSelected) : '').trigger('change');

    // Garantia: aceitar p1_garantia_id ou g1_id
    const garantiaVal = (product.p1_garantia_id ?? product.g1_id ?? product.p1_garantia) || '';
    let garantiaSelected = garantiaVal ? String(garantiaVal) : '';
    if (!garantiaSelected && product.g1_nome) {
        const opt = $('#edit-garantia-id option').filter(function() { return $(this).text().trim() === String(product.g1_nome).trim(); }).first();
        if (opt && opt.length) garantiaSelected = opt.val();
    }
    $('#edit-garantia-id').val(garantiaSelected ? String(garantiaSelected) : '').trigger('change');
        $('#edit-product-qnt').val(product.p1_quantidade_produto != null ? product.p1_quantidade_produto : 0);
        // Format monetary values to PT-BR (1.234,56) so jQuery Mask displays correctly
        try {
            const toPtBR = (val) => {
                if (val === null || val === undefined || val === '') return '';
                const n = Number(val) || 0;
                return n.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            };

            $('#edit-product-unit-price').val(product.p1_preco_unitario_produto != null ? toPtBR(product.p1_preco_unitario_produto) : '');
            $('#edit-product-purchase-price').val(product.p1_preco_compra_produto != null ? toPtBR(product.p1_preco_compra_produto) : '');
            $('#edit-product-sale-price').val(product.p1_preco_venda_produto != null ? toPtBR(product.p1_preco_venda_produto) : '');

            // Reapply mask to ensure formatting and caret behavior
            try {
                const moneyPattern = '000.000.000.000.000,00';
                $('#edit-product-unit-price, #edit-product-purchase-price, #edit-product-sale-price').mask(moneyPattern, { reverse: true });
            } catch (e) {
                // ignore if mask plugin not available
            }
        } catch (e) {
            // fallback: raw values
            $('#edit-product-unit-price').val(product.p1_preco_unitario_produto != null ? Number(product.p1_preco_unitario_produto).toFixed(2) : '');
            $('#edit-product-purchase-price').val(product.p1_preco_compra_produto != null ? Number(product.p1_preco_compra_produto).toFixed(2) : '');
            $('#edit-product-sale-price').val(product.p1_preco_venda_produto != null ? Number(product.p1_preco_venda_produto).toFixed(2) : '');
        }

    const img = productImagePath(product.p1_imagem_produto);
    $('#editImagePreview').attr('src', img);
    }

    // Duplicate/unused client view/edit helpers removed

    // fillEditForm (client) removed

    // Salvar novo produto
    async function saveProduct() {
        const $form = $('#addProductForm');
        if (!$form[0].checkValidity()) {
            $form.addClass('was-validated');
            return;
        }

        // Construir FormData para suportar upload de imagem
        const formEl = $form[0];
        const fd = new FormData(formEl);

        // Incluir arquivo se selecionado
        const fileInput = $('#img-product')[0];
        if (fileInput && fileInput.files && fileInput.files[0]) {
            fd.set('img-product', fileInput.files[0]);
        }

        // Normalizar preços (ex: 1.234,56 -> 1234.56)
        const normalizeMoney = (val) => {
            if (!val) return '0.00';
            // remover espaços
            val = String(val).trim();
            // remover pontos de milhar e substituir vírgula por ponto
            val = val.replace(/\./g, '').replace(/,/g, '.');
            // remover caracteres que não sejam dígitos ou ponto
            val = val.replace(/[^0-9.]/g, '');
            if (val === '') return '0.00';
            return parseFloat(val).toFixed(2);
        };

        fd.set('product-unit-price', normalizeMoney($('#product-unit-price').val()));
        fd.set('product-purchase-price', normalizeMoney($('#product-purchase-price').val()));
        fd.set('product-sale-price', normalizeMoney($('#product-sale-price').val()));

        const qty = parseInt($('#product-qnt').val()) || 0;
        fd.set('product-qnt', qty);

        // Calcular total em produto (quantidade * preço de venda)
        const total = (qty * parseFloat(fd.get('product-sale-price') || 0)).toFixed(2);
        fd.set('total-price-on-product', total);

        try {
            const response = await $.ajax({
                url: '<?= site_url('/produtos') ?>',
                method: 'POST',
                data: fd,
                processData: false,
                contentType: false,
                dataType: 'json'
            });

            if (response && response.success !== false) {
                showAlert('success', 'Produto cadastrado com sucesso!');
                // fechar modal e resetar formulário (Bootstrap 5)
                (function(){
                    const modalEl = document.getElementById('addProductModal');
                    if (modalEl) {
                        const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                        modal.hide();
                    }
                })();
                $form[0].reset();
                $form.removeClass('was-validated');
                // restaurar preview da imagem (default)
                $('#imagePreview').attr('src', productImagePath(null));
                await loadProducts();
            } else {
                showAlert('error', (response && response.message) ? response.message : 'Erro ao cadastrar produto');
            }
        } catch (error) {
            console.error('Erro:', error);
            showAlert('error', 'Erro ao cadastrar produto');
        }
    }

    // Atualizar produto
    async function updateProduct() {
        const $form = $('#editProductForm');
        if (!$form[0].checkValidity()) {
            $form.addClass('was-validated');
            return;
        }

        const id = $('#edit-product-id').val();
        if (!id) {
            showAlert('error', 'ID do produto ausente');
            return;
        }

        // Construir FormData para suportar upload de imagem
        const formEl = $form[0];
        const fd = new FormData(formEl);

        // Incluir arquivo se selecionado
        const fileInput = $('#edit-img-product')[0];
        if (fileInput && fileInput.files && fileInput.files[0]) {
            fd.set('img-product', fileInput.files[0]);
        }

        // Normalizar preços (ex: 1.234,56 -> 1234.56)
        const normalizeMoney = (val) => {
            if (!val) return '0.00';
            val = String(val).trim();
            val = val.replace(/\./g, '').replace(/,/g, '.');
            val = val.replace(/[^0-9.]/g, '');
            if (val === '') return '0.00';
            return parseFloat(val).toFixed(2);
        };

        fd.set('product-unit-price', normalizeMoney($('#edit-product-unit-price').val()));
        fd.set('product-purchase-price', normalizeMoney($('#edit-product-purchase-price').val()));
        fd.set('product-sale-price', normalizeMoney($('#edit-product-sale-price').val()));

        const qty = parseInt($('#edit-product-qnt').val()) || 0;
        fd.set('product-qnt', qty);

        // Calcular total em produto (quantidade * preço de venda)
        const total = (qty * parseFloat(fd.get('product-sale-price') || 0)).toFixed(2);
        fd.set('total-price-on-product', total);

        // Some servers don't accept multipart PUT; use POST with _method=PUT
        fd.set('_method', 'PUT');

        try {
            const response = await $.ajax({
                url: `<?= site_url('/produtos/') ?>${id}`,
                method: 'POST',
                data: fd,
                processData: false,
                contentType: false,
                dataType: 'json'
            });

            if (response && (response.p1_id || response.success !== false)) {
                showAlert('success', 'Produto atualizado com sucesso!');
                (function(){
                    const modalEl = document.getElementById('editProductModal');
                    if (modalEl) {
                        const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                        modal.hide();
                    }
                })();
                $form.removeClass('was-validated');
                await loadProducts();
            } else {
                showAlert('error', (response && response.message) ? response.message : 'Erro ao atualizar produto');
            }
        } catch (error) {
            console.error('Erro:', error);
            showAlert('error', 'Erro ao atualizar produto');
        }
    }

    // Excluir produto (front-end) — usa SweetAlert e DELETE como em deleteClient
    async function deleteProduct(id, name) {
        if (!id) return showAlert('error', 'ID do produto ausente');

        Swal.fire({
            title: `Tem certeza que deseja excluir o produto "${name}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sim, excluir',
            cancelButtonText: 'Cancelar'
        }).then(async (resultSwal) => {
            if (resultSwal.isConfirmed) {
                try {
                    const response = await $.ajax({
                        url: `<?= site_url('/produtos/') ?>${id}`,
                        method: 'DELETE',
                        dataType: 'json'
                    });

                    if (response) {
                        showAlert('success', 'Produto excluído com sucesso!');
                        await loadProducts();
                    } else {
                        showAlert('error', response && response.message ? response.message : 'Erro ao excluir produto');
                    }
                } catch (error) {
                    console.error('Erro:', error);
                    showAlert('error', 'Erro ao excluir produto');
                }
            }
        });
    }

    // Função para mostrar alertas
    function showAlert(type, message) {
        // Remover alertas existentes
        $('.alert').remove();

        // Criar novo alerta
        const alertClass = type === 'error' ? 'danger' : type;
        const $alertDiv = $(`
            <div class="alert alert-${alertClass} alert-dismissible fade show">
                <strong>${type === 'error' ? 'Erro!' : 'Sucesso!'}</strong> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `).css({
            position: 'fixed',
            top: '20px',
            right: '20px',
            zIndex: '9999',
            minWidth: '300px'
        });

        $('body').append($alertDiv);

        // Remover automaticamente após 5 segundos
        setTimeout(() => {
            $alertDiv.alert('close');
        }, 5000);
    }

    // Limpar formulários quando os modais de produto são fechados
    $('#addProductModal').on('hidden.bs.modal', function() {
        const $form = $('#addProductForm');
        if ($form && $form[0]) {
            $form[0].reset();
            $form.removeClass('was-validated');
        }
    });

    $('#editProductModal').on('hidden.bs.modal', function() {
        const $form = $('#editProductForm');
        if ($form && $form.length) {
            $form.removeClass('was-validated');
        }
    });
</script>

<?= $this->endSection() ?>