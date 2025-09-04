    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4 mt-5">
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

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Script para alternar entre temas (futuramente)
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('themeToggle');
            if (themeToggle) {
                themeToggle.addEventListener('click', function() {
                    const html = document.documentElement;
                    const currentTheme = html.getAttribute('data-bs-theme');
                    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                    html.setAttribute('data-bs-theme', newTheme);

                    // Salvar preferência (implementar localStorage futuramente)
                    localStorage.setItem('theme', newTheme);
                });
            }
        });
    </script>
    </body>

    </html>