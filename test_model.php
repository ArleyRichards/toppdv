<?php
require_once 'vendor/autoload.php';

// Inicializar o CodeIgniter
$app = require_once 'app/Config/Bootstrap.php';
$app->run();

use App\Models\OrdemModel;

try {
    $model = new OrdemModel();

    // Teste 1: Query simples
    echo "=== Teste 1: Query simples ===" . PHP_EOL;
    $ordens = $model->where('o1_deleted_at IS NULL')->findAll();
    echo 'Registros encontrados: ' . count($ordens) . PHP_EOL;

    if (count($ordens) > 0) {
        echo 'Tipo do primeiro resultado: ' . gettype($ordens[0]) . PHP_EOL;
        echo 'Primeiro registro (array):' . PHP_EOL;
        print_r($ordens[0]);
    }

    // Teste 2: Query com findAll() sem where
    echo PHP_EOL . "=== Teste 2: findAll() sem where ===" . PHP_EOL;
    $todas = $model->findAll();
    echo 'Total de registros: ' . count($todas) . PHP_EOL;

} catch (Exception $e) {
    echo 'Erro: ' . $e->getMessage() . PHP_EOL;
    echo 'Arquivo: ' . $e->getFile() . PHP_EOL;
    echo 'Linha: ' . $e->getLine() . PHP_EOL;
}
?>
