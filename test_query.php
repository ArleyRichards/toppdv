<?php
// Teste simples para verificar se a correção funcionou
require_once __DIR__ . '/../vendor/autoload.php';

use Config\Database;

try {
    $db = Database::connect();

    // Testar a query corrigida
    $query = $db->query("
        SELECT
            p3_produtos_ordem.*,
            p1_produtos.p1_nome_produto as produto_nome,
            p1_produtos.p1_codigo_produto as produto_codigo
        FROM p3_produtos_ordem
        LEFT JOIN p1_produtos ON p1_produtos.p1_id = p3_produtos_ordem.p3_produto_id
        WHERE p3_produtos_ordem.p3_ordem_id = 1 AND p3_produtos_ordem.p3_deleted_at IS NULL
        ORDER BY p3_produtos_ordem.p3_id ASC
    ");

    $result = $query->getResultArray();

    echo "Query executada com sucesso!\n";
    echo "Número de registros retornados: " . count($result) . "\n";

    if (count($result) > 0) {
        echo "Primeiro registro:\n";
        print_r($result[0]);
    }

} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}
?>
