    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4 mt-5 site-footer">
        <div class="container">
            <div class="row">
                <?php

                use App\Helpers\ConfigHelper; ?>
                <?php $appName = ConfigHelper::appName();
                $appVersion = ConfigHelper::versao();
                $empresa = ConfigHelper::empresa(); ?>
                <div class="col-md-6">
                    <p class="mb-0"><?= esc($empresa) ?></p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="d-flex flex-column flex-md-row justify-content-md-end gap-3">
                        <p><?= esc($appName) ?></p>
                        <small class="text-muted">Versão <?= esc($appVersion) ?></small>
                    </div>
                </div>
            </div>
            <hr class="my-3">
            <div class="text-center">
                <small class="text-muted">
                    &copy; <?= date('Y') ?> <?= esc($appName) ?>. Todos os direitos reservados.
                </small>
            </div>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    
    <!-- jQuery Mask Plugin -->
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

</body>
</html>
