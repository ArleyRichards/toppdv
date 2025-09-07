<?php
try {
    $db = new PDO('mysql:host=localhost;dbname=ci_pdv', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $db->query('SELECT * FROM o1_ordens WHERE o1_deleted_at IS NULL');
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo 'Registros encontrados: ' . count($results) . PHP_EOL;
    if (count($results) > 0) {
        echo 'Primeiro registro:' . PHP_EOL;
        echo json_encode($results[0], JSON_PRETTY_PRINT) . PHP_EOL;
    } else {
        echo 'Nenhum registro encontrado' . PHP_EOL;
    }
} catch (Exception $e) {
    echo 'Erro: ' . $e->getMessage() . PHP_EOL;
}
?>
