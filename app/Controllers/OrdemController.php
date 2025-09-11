<?php

namespace App\Controllers;

use App\Helpers\ConfigHelper;
use App\Models\ClienteModel;
use App\Models\ProdutoModel;
use App\Models\ServicoModel;
use App\Models\TecnicoModel;
use App\Models\ProdutosOrdemModel;
use App\Models\ServicosOrdemModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

/**
 * Controller responsável pelas operações de ordens de serviços.
 * @author Arley Richards <arleyrichards@gmail.com>
 */
class OrdemController extends ResourceController
{

    use ResponseTrait;

    protected $modelName = 'App\Models\OrdemModel';
    protected $format    = 'json';

    public function index()
    {
        $data = [
            'title' => 'Gerenciamento de ordens de serviços',
            'appName' => ConfigHelper::appName(),
            'empresa' => ConfigHelper::empresa(),
            'logo'    => ConfigHelper::get('c3_logo_path') ?? IMG_PATH . 'logo.png',
        ];

        // Carregar clientes para popular o select no modal de nova ordem
        try {
            $clienteModel = new ClienteModel();
            $clientes = $clienteModel->where('c2_deleted_at', null)->orderBy('c2_nome', 'ASC')->findAll();
        } catch (\Exception $e) {
            // em caso de erro, passar array vazio para não quebrar a view
            $clientes = [];
        }

        // Carregar técnicos para popular o select no modal de nova ordem
        try {
            $tecnicoModel = new TecnicoModel();
            $tecnicos = $tecnicoModel->where('t1_deleted_at', null)->orderBy('t1_nome', 'ASC')->findAll();
        } catch (\Exception $e) {
            // em caso de erro, passar array vazio para não quebrar a view
            $tecnicos = [];
        }

        // Carregar produtos para popular o select no modal de operações
        try {
            $produtoModel = new ProdutoModel();
            $produtos = $produtoModel->where('p1_deleted_at', null)->orderBy('p1_nome_produto', 'ASC')->findAll();
        } catch (\Exception $e) {
            // em caso de erro, passar array vazio para não quebrar a view
            $produtos = [];
        }

        // Carregar serviços para popular o select no modal de operações
        try {
            $servicoModel = new ServicoModel();
            $servicos = $servicoModel->where('s1_deleted_at', null)->orderBy('s1_nome_servico', 'ASC')->findAll();
        } catch (\Exception $e) {
            // em caso de erro, passar array vazio para não quebrar a view
            $servicos = [];
        }

        $data['clientes'] = $clientes;
        $data['tecnicos'] = $tecnicos;
        $data['produtos'] = $produtos;
        $data['servicos'] = $servicos;

        return view('ordens', $data);
    }

    /**
     * Lista todas as ordens de serviço.
     * Método GET: /ordens/list
     * @return
     * @author Arley Richards <arleyrichards@gmail.com>
     */
    public function list()
    {
        // Permitir requisições GET
        $request = service('request');

        // Retorna todas as ordens ativas (não deletadas) com JOINs para trazer nomes relacionados
        $db = \Config\Database::connect();
        $query = $db->query("
            SELECT
                o1_ordens.*,
                c2_clientes.c2_nome as cliente_nome,
                t1_tecnicos.t1_nome as tecnico_nome
            FROM o1_ordens
            LEFT JOIN c2_clientes ON c2_clientes.c2_id = o1_ordens.o1_cliente_id
            LEFT JOIN t1_tecnicos ON t1_tecnicos.t1_id = o1_ordens.o1_tecnico_id
            WHERE o1_ordens.o1_deleted_at IS NULL
        ");
        $ordens = $query->getResultArray();

        $data = [];
        foreach ($ordens as $ordem) {
            // Como o returnType é 'array', vamos acessar como array
            $data[] = [
                'o1_id' => isset($ordem['o1_id']) ? (int) $ordem['o1_id'] : null,
                'o1_numero_ordem' => $ordem['o1_numero_ordem'] ?? null,
                'o1_cliente_id' => isset($ordem['o1_cliente_id']) ? (int) $ordem['o1_cliente_id'] : null,
                'cliente_nome' => $ordem['cliente_nome'] ?? null,
                'o1_equipamento' => $ordem['o1_equipamento'] ?? null,
                'o1_marca' => $ordem['o1_marca'] ?? null,
                'o1_modelo' => $ordem['o1_modelo'] ?? null,
                'o1_numero_serie' => $ordem['o1_numero_serie'] ?? null,
                'o1_defeito_relatado' => $ordem['o1_defeito_relatado'] ?? null,
                'o1_observacoes_entrada' => $ordem['o1_observacoes_entrada'] ?? null,
                'o1_acessorios_entrada' => $ordem['o1_acessorios_entrada'] ?? null,
                'o1_estado_aparente' => $ordem['o1_estado_aparente'] ?? null,
                'o1_tecnico_id' => isset($ordem['o1_tecnico_id']) ? (int) $ordem['o1_tecnico_id'] : null,
                'tecnico_nome' => $ordem['tecnico_nome'] ?? null,
                'o1_status' => $ordem['o1_status'] ?? null,
                'o1_prioridade' => $ordem['o1_prioridade'] ?? null,
                'o1_data_entrada' => $ordem['o1_data_entrada'] ?? null,
                'o1_data_previsao' => $ordem['o1_data_previsao'] ?? null,
                'o1_data_conclusao' => $ordem['o1_data_conclusao'] ?? null,
                'o1_data_entrega' => $ordem['o1_data_entrega'] ?? null,
                'o1_valor_servicos' => isset($ordem['o1_valor_servicos']) ? (float) $ordem['o1_valor_servicos'] : 0.0,
                'o1_valor_produtos' => isset($ordem['o1_valor_produtos']) ? (float) $ordem['o1_valor_produtos'] : 0.0,
                'o1_valor_total' => isset($ordem['o1_valor_total']) ? (float) $ordem['o1_valor_total'] : 0.0,
                'o1_desconto' => isset($ordem['o1_desconto']) ? (float) $ordem['o1_desconto'] : 0.0,
                'o1_valor_final' => isset($ordem['o1_valor_final']) ? (float) $ordem['o1_valor_final'] : 0.0,
                'o1_laudo_tecnico' => $ordem['o1_laudo_tecnico'] ?? null,
                'o1_observacoes_conclusao' => $ordem['o1_observacoes_conclusao'] ?? null,
                'o1_garantia_servico' => isset($ordem['o1_garantia_servico']) ? (int) $ordem['o1_garantia_servico'] : 0,
                'o1_created_at' => $ordem['o1_created_at'] ?? null,
                'o1_updated_at' => $ordem['o1_updated_at'] ?? null,
            ];
        }

        return $this->respond($data);
    }    /**
     * Retorna os dados completos de uma ordem incluindo produtos e serviços.
     * Método GET: /ordens/{id}
     * @param int $id
     * @return
     * @author Arley Richards <arleyrichards@gmail.com>
     */
    public function show($id = null){
        if (!$id) {
            return $this->failNotFound('ID da ordem é obrigatório');
        }

        $db = \Config\Database::connect();

        // Buscar dados da ordem com cliente e técnico
        $queryOrdem = $db->query("
            SELECT
                o1_ordens.*,
                c2_clientes.c2_nome as cliente_nome,
                c2_clientes.c2_cpf as cliente_cpf,
                c2_clientes.c2_telefone as cliente_telefone,
                c2_clientes.c2_celular as cliente_celular,
                c2_clientes.c2_email as cliente_email,
                c2_clientes.c2_endereco as cliente_endereco,
                t1_tecnicos.t1_nome as tecnico_nome,
                t1_tecnicos.t1_cpf as tecnico_cpf,
                t1_tecnicos.t1_telefone as tecnico_telefone,
                t1_tecnicos.t1_email as tecnico_email
            FROM o1_ordens
            LEFT JOIN c2_clientes ON c2_clientes.c2_id = o1_ordens.o1_cliente_id
            LEFT JOIN t1_tecnicos ON t1_tecnicos.t1_id = o1_ordens.o1_tecnico_id
            WHERE o1_ordens.o1_id = ? AND o1_ordens.o1_deleted_at IS NULL
        ", [$id]);

        $ordem = $queryOrdem->getRowArray();

        if (!$ordem) {
            return $this->failNotFound('Ordem não encontrada');
        }

        // Buscar produtos da ordem
        $queryProdutos = $db->query("
            SELECT
                p3_produtos_ordem.*,
                p1_produtos.p1_nome_produto as produto_nome,
                p1_produtos.p1_codigo_produto as produto_codigo
            FROM p3_produtos_ordem
            LEFT JOIN p1_produtos ON p1_produtos.p1_id = p3_produtos_ordem.p3_produto_id
            WHERE p3_produtos_ordem.p3_ordem_id = ? AND p3_produtos_ordem.p3_deleted_at IS NULL
            ORDER BY p3_produtos_ordem.p3_id ASC
        ", [$id]);

        $produtos = $queryProdutos->getResultArray();

        // Buscar serviços da ordem
        $queryServicos = $db->query("
            SELECT
                s2_servicos_ordem.*,
                s1_servicos.s1_nome_servico as servico_nome,
                s1_servicos.s1_descricao as servico_descricao,
                s1_servicos.s1_valor as servico_valor_padrao,
                u1_usuarios.u1_nome as tecnico_servico_nome
            FROM s2_servicos_ordem
            LEFT JOIN s1_servicos ON s1_servicos.s1_id = s2_servicos_ordem.s2_servico_id
            LEFT JOIN u1_usuarios ON u1_usuarios.u1_id = s2_servicos_ordem.s2_tecnico_id
            WHERE s2_servicos_ordem.s2_ordem_id = ? AND s2_servicos_ordem.s2_deleted_at IS NULL
            ORDER BY s2_servicos_ordem.s2_id ASC
        ", [$id]);

        $servicos = $queryServicos->getResultArray();

        // Formatar dados da ordem
        $ordemFormatada = [
            'o1_id' => (int) $ordem['o1_id'],
            'o1_numero_ordem' => $ordem['o1_numero_ordem'],
            'o1_cliente_id' => (int) $ordem['o1_cliente_id'],
            'cliente_nome' => $ordem['cliente_nome'],
            'cliente_cpf' => $this->formatarCpf($ordem['cliente_cpf']),
            'cliente_telefone' => $this->formatarTelefone($ordem['cliente_telefone']),
            'cliente_celular' => $this->formatarTelefone($ordem['cliente_celular']),
            'cliente_email' => $ordem['cliente_email'],
            'cliente_endereco' => $ordem['cliente_endereco'],
            'o1_equipamento' => $ordem['o1_equipamento'],
            'o1_marca' => $ordem['o1_marca'],
            'o1_modelo' => $ordem['o1_modelo'],
            'o1_numero_serie' => $ordem['o1_numero_serie'],
            'o1_defeito_relatado' => $ordem['o1_defeito_relatado'],
            'o1_observacoes_entrada' => $ordem['o1_observacoes_entrada'],
            'o1_acessorios_entrada' => $ordem['o1_acessorios_entrada'],
            'o1_estado_aparente' => $ordem['o1_estado_aparente'],
            'o1_tecnico_id' => (int) $ordem['o1_tecnico_id'],
            'tecnico_nome' => $ordem['tecnico_nome'],
            'tecnico_cpf' => $this->formatarCpf($ordem['tecnico_cpf']),
            'tecnico_telefone' => $this->formatarTelefone($ordem['tecnico_telefone']),
            'tecnico_email' => $ordem['tecnico_email'],
            'o1_status' => $ordem['o1_status'],
            'o1_prioridade' => $ordem['o1_prioridade'],
            'o1_data_entrada' => $ordem['o1_data_entrada'],
            'o1_data_previsao' => $ordem['o1_data_previsao'],
            'o1_data_conclusao' => $ordem['o1_data_conclusao'],
            'o1_data_entrega' => $ordem['o1_data_entrega'],
            'o1_valor_servicos' => (float) $ordem['o1_valor_servicos'],
            'o1_valor_produtos' => (float) $ordem['o1_valor_produtos'],
            'o1_valor_total' => (float) $ordem['o1_valor_total'],
            'o1_desconto' => (float) $ordem['o1_desconto'],
            'o1_valor_final' => (float) $ordem['o1_valor_final'],
            'o1_laudo_tecnico' => $ordem['o1_laudo_tecnico'],
            'o1_observacoes_conclusao' => $ordem['o1_observacoes_conclusao'],
            'o1_garantia_servico' => (int) $ordem['o1_garantia_servico'],
            'o1_created_at' => $ordem['o1_created_at'],
            'o1_updated_at' => $ordem['o1_updated_at']
        ];

        // Formatar produtos
        $produtosFormatados = [];
        foreach ($produtos as $produto) {
            $produtosFormatados[] = [
                'p3_id' => (int) $produto['p3_id'],
                'p3_produto_id' => (int) $produto['p3_produto_id'],
                'produto_nome' => $produto['produto_nome'],
                'produto_codigo' => $produto['produto_codigo'],
                'p3_quantidade' => (int) $produto['p3_quantidade'],
                'p3_valor_unitario' => (float) $produto['p3_valor_unitario'],
                'p3_valor_total' => (float) $produto['p3_valor_total'],
                'p3_observacoes' => $produto['p3_observacoes']
            ];
        }

        // Formatar serviços
        $servicosFormatados = [];
        foreach ($servicos as $servico) {
            $servicosFormatados[] = [
                's2_id' => (int) $servico['s2_id'],
                's2_servico_id' => (int) $servico['s2_servico_id'],
                'servico_nome' => $servico['servico_nome'],
                'servico_descricao' => $servico['servico_descricao'],
                'servico_valor_padrao' => (float) $servico['servico_valor_padrao'],
                's2_quantidade' => (int) $servico['s2_quantidade'],
                's2_valor_unitario' => (float) $servico['s2_valor_unitario'],
                's2_valor_total' => (float) $servico['s2_valor_total'],
                's2_status' => $servico['s2_status'],
                's2_tecnico_id' => $servico['s2_tecnico_id'] ? (int) $servico['s2_tecnico_id'] : null,
                'tecnico_servico_nome' => $servico['tecnico_servico_nome'],
                's2_observacoes' => $servico['s2_observacoes'],
                's2_data_inicio' => $servico['s2_data_inicio'],
                's2_data_conclusao' => $servico['s2_data_conclusao']
            ];
        }

        // Retornar dados completos
        return $this->respond([
            'ordem' => $ordemFormatada,
            'produtos' => $produtosFormatados,
            'servicos' => $servicosFormatados,
            'totais' => [
                'produtos_count' => count($produtosFormatados),
                'servicos_count' => count($servicosFormatados),
                'valor_produtos' => (float) $ordem['o1_valor_produtos'],
                'valor_servicos' => (float) $ordem['o1_valor_servicos'],
                'valor_total' => (float) $ordem['o1_valor_total'],
                'desconto' => (float) $ordem['o1_desconto'],
                'valor_final' => (float) $ordem['o1_valor_final']
            ]
        ]);
    }

    /**
     * Cadastra um novo cliente.
     * Método POST: /clientes
     * @return 
     * @author Arley Richards <arleyrichards@gmail.com>
     */
    public function create()    
    {
        // Accept JSON body or form-posted data
        $request = service('request');
        $input = $request->getJSON(true);
        if (!$input) {
            $input = $request->getPost() ?: [];
        }

        // Map incoming fields (frontend may send short names) to o1_ columns
        $data = [];
        $data['o1_id'] = isset($input['o1_id']) ? (int) $input['o1_id'] : $this->getNextId();
        $data['o1_numero_ordem'] = $input['o1_numero_ordem'] ?? $input['numero_ordem'] ?? $this->generateNumeroOrdem($data['o1_id']);
        $data['o1_cliente_id'] = isset($input['o1_cliente_id']) ? (int) $input['o1_cliente_id'] : (isset($input['cliente_id']) ? (int) $input['cliente_id'] : null);
        $data['o1_equipamento'] = $input['o1_equipamento'] ?? $input['equipamento'] ?? null;
        $data['o1_marca'] = $input['o1_marca'] ?? $input['marca'] ?? null;
        $data['o1_modelo'] = $input['o1_modelo'] ?? $input['modelo'] ?? null;
        $data['o1_numero_serie'] = $input['o1_numero_serie'] ?? $input['numero_serie'] ?? null;
        $data['o1_defeito_relatado'] = $input['o1_defeito_relatado'] ?? $input['defeito_relatado'] ?? null;
        $data['o1_observacoes_entrada'] = $input['o1_observacoes_entrada'] ?? $input['observacoes_entrada'] ?? null;
        $data['o1_acessorios_entrada'] = $input['o1_acessorios_entrada'] ?? $input['acessorios_entrada'] ?? null;
        $data['o1_estado_aparente'] = $input['o1_estado_aparente'] ?? $input['estado_aparente'] ?? 'Bom';
        $data['o1_tecnico_id'] = isset($input['o1_tecnico_id']) ? (int) $input['o1_tecnico_id'] : (isset($input['tecnico_id']) ? (int) $input['tecnico_id'] : null);
        $data['o1_status'] = $input['o1_status'] ?? $input['status'] ?? 'Aguardando';
        $data['o1_prioridade'] = $input['o1_prioridade'] ?? $input['prioridade'] ?? 'Média';
        $data['o1_data_entrada'] = $input['o1_data_entrada'] ?? $input['data_entrada'] ?? date('Y-m-d H:i:s');
        $data['o1_data_previsao'] = $input['o1_data_previsao'] ?? $input['data_previsao'] ?? null;
        $data['o1_data_conclusao'] = $input['o1_data_conclusao'] ?? $input['data_conclusao'] ?? null;
        $data['o1_data_entrega'] = $input['o1_data_entrega'] ?? $input['data_entrega'] ?? null;
    // valores financeiros e garantia removidos do formulário de abertura — manter valor_total se enviado pelo sistema
    $data['o1_valor_total'] = $this->parseDecimal($input['o1_valor_total'] ?? $input['valor_total'] ?? 0);
        $data['o1_laudo_tecnico'] = $input['o1_laudo_tecnico'] ?? $input['laudo_tecnico'] ?? null;
    $data['o1_observacoes_conclusao'] = $input['o1_observacoes_conclusao'] ?? $input['observacoes_conclusao'] ?? null;

        // Basic validation
        $errors = [];
        if (empty($data['o1_numero_ordem'])) $errors['o1_numero_ordem'] = 'Número da ordem é obrigatório.';
        if (empty($data['o1_cliente_id'])) $errors['o1_cliente_id'] = 'Cliente obrigatório.';
        if (empty($data['o1_equipamento'])) $errors['o1_equipamento'] = 'Equipamento é obrigatório.';
        if (empty($data['o1_defeito_relatado'])) $errors['o1_defeito_relatado'] = 'Descreva o defeito relatado.';

        if (!empty($errors)) {
            return $this->failValidationErrors($errors);
        }

        try {
            // Normalizar técnico: evitar inserir 0 ou id inexistente que quebra FK
            $tec = $data['o1_tecnico_id'] ?? null;
            if (empty($tec) || $tec === 0) {
                // tenta pegar usuário logado pela sessão (várias chaves possíveis)
                $session = service('session');
                $possible = [$session->get('u1_id'), $session->get('user_id'), $session->get('usuario_id'), $session->get('id')];
                $found = null;
                foreach ($possible as $p) {
                    if (!empty($p)) { $found = (int) $p; break; }
                }
                if ($found) {
                    $data['o1_tecnico_id'] = $found;
                } else {
                    // como último recurso, pega um usuário existente no banco para não violar FK
                    $db = \Config\Database::connect();
                    $row = $db->table('u1_usuarios')->select('u1_id')->limit(1)->get()->getRowArray();
                    $data['o1_tecnico_id'] = isset($row['u1_id']) ? (int) $row['u1_id'] : 1;
                }
            }
            // Insert — since o1_id is not AUTO_INCREMENT in the DDL, we ensure o1_id is set
            $inserted = $this->model->insert($data);
            if ($inserted === false) {
                // model->insert may return false on failure
                $dbErrors = $this->model->errors() ?: [];
                return $this->failValidationErrors($dbErrors ?: ['error' => 'Falha ao inserir ordem']);
            }

            // Fetch created record
            $created = $this->model->find($data['o1_id']);
            return $this->respondCreated($created, 'Ordem criada com sucesso');
        } catch (\Exception $e) {
            return $this->failServerError('Erro no servidor: ' . $e->getMessage());
        }
    }

    /**
     * Atualiza uma ordem de serviço existente.
     * Método PUT: /ordens/{id}
     * @param int $id
     * @return JSON
     * @author Arley Richards <arleyrichards@gmail.com>
     */
    public function update($id = null)
    {
        if (!$id) {
            return $this->failNotFound('ID da ordem é obrigatório');
        }

        // Verificar se a ordem existe
        $ordem = $this->model->find($id);
        if (!$ordem) {
            return $this->failNotFound('Ordem não encontrada');
        }

        // Receber dados do request
        $request = service('request');
        $input = $request->getJSON(true);
        if (!$input) {
            $input = $request->getPost() ?: [];
        }

        // Map incoming fields (frontend may send short names) to o1_ columns
        $data = [];
        $data['o1_cliente_id'] = isset($input['o1_cliente_id']) ? (int) $input['o1_cliente_id'] : (isset($input['cliente_id']) ? (int) $input['cliente_id'] : null);
        $data['o1_equipamento'] = $input['o1_equipamento'] ?? $input['equipamento'] ?? null;
        $data['o1_marca'] = $input['o1_marca'] ?? $input['marca'] ?? null;
        $data['o1_modelo'] = $input['o1_modelo'] ?? $input['modelo'] ?? null;
        $data['o1_numero_serie'] = $input['o1_numero_serie'] ?? $input['numero_serie'] ?? null;
        $data['o1_defeito_relatado'] = $input['o1_defeito_relatado'] ?? $input['defeito_relatado'] ?? null;
        $data['o1_observacoes_entrada'] = $input['o1_observacoes_entrada'] ?? $input['observacoes_entrada'] ?? null;
        $data['o1_acessorios_entrada'] = $input['o1_acessorios_entrada'] ?? $input['acessorios_entrada'] ?? null;
        $data['o1_estado_aparente'] = $input['o1_estado_aparente'] ?? $input['estado_aparente'] ?? null;
        $data['o1_tecnico_id'] = isset($input['o1_tecnico_id']) ? (int) $input['o1_tecnico_id'] : (isset($input['tecnico_id']) ? (int) $input['tecnico_id'] : null);
        $data['o1_prioridade'] = $input['o1_prioridade'] ?? $input['prioridade'] ?? null;
        $data['o1_data_entrada'] = $input['o1_data_entrada'] ?? $input['data_entrada'] ?? null;
        $data['o1_data_previsao'] = $input['o1_data_previsao'] ?? $input['data_previsao'] ?? null;

        // Basic validation
        $errors = [];
        if (empty($data['o1_cliente_id'])) $errors['cliente_id'] = 'Cliente é obrigatório.';
        if (empty($data['o1_equipamento'])) $errors['equipamento'] = 'Equipamento é obrigatório.';
        if (empty($data['o1_defeito_relatado'])) $errors['defeito_relatado'] = 'Defeito relatado é obrigatório.';

        if (!empty($errors)) {
            return $this->failValidationErrors($errors);
        }

        try {
            // Atualizar a ordem
            $updated = $this->model->update($id, $data);
            if ($updated === false) {
                $dbErrors = $this->model->errors() ?: [];
                return $this->failValidationErrors($dbErrors ?: ['error' => 'Falha ao atualizar ordem']);
            }

            // Buscar ordem atualizada
            $ordemAtualizada = $this->model->find($id);
            return $this->respond($ordemAtualizada, 200, 'Ordem atualizada com sucesso');

        } catch (\Exception $e) {
            return $this->failServerError('Erro no servidor: ' . $e->getMessage());
        }
    }

    /**
     * Exclui uma ordem de serviço.
     * Método DELETE: /ordens/{id}
     * @param int $id
     * @return JSON
     * @author Arley Richards <arleyrichards@gmail.com>
     */
    public function delete($id = null)
    {
        if (!$id) {
            return $this->failNotFound('ID da ordem é obrigatório');
        }

        // Verificar se a ordem existe
        $ordem = $this->model->find($id);
        if (!$ordem) {
            return $this->failNotFound('Ordem não encontrada');
        }

        // Iniciar transação
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Instanciar modelos para remover relacionamentos
            $produtosOrdemModel = new ProdutosOrdemModel();
            $servicosOrdemModel = new ServicosOrdemModel();

            // Remover produtos e serviços associados à ordem
            $produtosOrdemModel->where('p3_ordem_id', $id)->delete();
            $servicosOrdemModel->where('s2_ordem_id', $id)->delete();

            // Remover a ordem
            $deleted = $this->model->delete($id);
            if ($deleted === false) {
                $dbErrors = $this->model->errors() ?: [];
                $db->transRollback();
                return $this->failValidationErrors($dbErrors ?: ['error' => 'Falha ao excluir ordem']);
            }

            // Finalizar transação
            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->failServerError('Erro ao excluir ordem');
            }

            return $this->respondDeleted([
                'success' => true,
                'message' => 'Ordem excluída com sucesso',
                'ordem_id' => $id
            ]);

        } catch (\Exception $e) {
            $db->transRollback();
            return $this->failServerError('Erro ao excluir ordem: ' . $e->getMessage());
        }
    }

    /**
     * Parse decimal-friendly input (handles BR format like 1.234,56)
     */
    private function parseDecimal($value)
    {
        if ($value === null || $value === '') return 0.0;
        if (is_numeric($value)) return (float) $value;
        $s = trim((string) $value);
        // remove non numeric except , and . and -
        $s = preg_replace('/[^0-9,\.-]/', '', $s);
        // if contains comma and dot, assume dot thousand separator -> remove dots, replace comma
        if (strpos($s, ',') !== false && strpos($s, '.') !== false) {
            $s = str_replace('.', '', $s);
            $s = str_replace(',', '.', $s);
        } elseif (strpos($s, ',') !== false) {
            $s = str_replace(',', '.', $s);
        }
        return (float) $s;
    }

    /**
     * Formata CPF para exibição (xxx.xxx.xxx-xx) quando possível
     */
    private function formatarCpf($cpf)
    {
        if (empty($cpf)) return $cpf;
        $s = preg_replace('/\D/', '', $cpf);
        if (strlen($s) === 11) {
            return substr($s,0,3) . '.' . substr($s,3,3) . '.' . substr($s,6,3) . '-' . substr($s,9,2);
        }
        return $s;
    }

    /**
     * Formata telefone para exibição quando possível
     */
    private function formatarTelefone($tel)
    {
        if (empty($tel)) return $tel;
        $s = preg_replace('/\D/', '', $tel);
        if (strlen($s) === 10) {
            return '(' . substr($s,0,2) . ') ' . substr($s,2,4) . '-' . substr($s,6);
        } elseif (strlen($s) === 11) {
            return '(' . substr($s,0,2) . ') ' . substr($s,2,5) . '-' . substr($s,7);
        }
        return $s;
    }

    /**
     * Get next id for o1_ordens (DB does not use AUTO_INCREMENT in dump)
     */
    private function getNextId()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('o1_ordens');
        $row = $builder->selectMax('o1_id')->get()->getRowArray();
        $max = isset($row['o1_id']) ? (int) $row['o1_id'] : 0;
        return $max + 1;
    }

    /**
     * Gera um número de ordem único no formato OS000001
     * @param int|null $id ID opcional para usar no número (se null, pega próximo ID)
     * @return string Número da ordem gerado
     * @author Arley Richards <arleyrichards@gmail.com>
     */
    private function generateNumeroOrdem($id = null)
    {
        $id = $id ?? $this->getNextId();
        return 'OS' . str_pad($id, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Salva produtos e serviços associados a uma ordem, atualizando os totais.
     * Método POST: /ordens/{id}/operacoes
     * @param int $ordemId
     * @return JSON
     * @author Arley Richards <arleyrichards@gmail.com>
     */
    public function saveOperacoes($ordemId = null)
    {
        if (!$ordemId) {
            return $this->failValidationErrors(['ordem_id' => 'ID da ordem é obrigatório']);
        }

        // Verificar se a ordem existe
        $ordem = $this->model->find($ordemId);
        if (!$ordem) {
            return $this->failNotFound('Ordem não encontrada');
        }

        // Receber dados do request
        $request = service('request');
        $input = $request->getJSON(true);
        if (!$input) {
            $input = $request->getPost() ?: [];
        }

        $produtos = $input['produtos'] ?? [];
        $servicos = $input['servicos'] ?? [];
        $totalProdutos = $this->parseDecimal($input['total_produtos'] ?? 0);
        $totalServicos = $this->parseDecimal($input['total_servicos'] ?? 0);
        $totalGeral = $this->parseDecimal($input['total_geral'] ?? 0);

        // Iniciar transação
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Instanciar modelos
            $produtosOrdemModel = new ProdutosOrdemModel();
            $servicosOrdemModel = new ServicosOrdemModel();

            // Remover produtos e serviços existentes da ordem
            $produtosOrdemModel->where('p3_ordem_id', $ordemId)->delete();
            $servicosOrdemModel->where('s2_ordem_id', $ordemId)->delete();

            // Salvar produtos
            $produtosSalvos = [];
            foreach ($produtos as $produto) {
                $produtoId = $produto['produto_id'] ?? $produto['p3_produto_id'] ?? null;
                $quantidade = (int) ($produto['quantidade'] ?? $produto['p3_quantidade'] ?? 1);
                $valorUnitario = $this->parseDecimal($produto['preco'] ?? $produto['valor_unitario'] ?? $produto['p3_valor_unitario'] ?? 0);
                $valorTotal = $this->parseDecimal($produto['subtotal'] ?? $produto['valor_total'] ?? $produto['p3_valor_total'] ?? ($quantidade * $valorUnitario));
                $observacoes = $produto['observacoes'] ?? $produto['p3_observacoes'] ?? null;

                if ($produtoId && $quantidade > 0) {
                    $dadosProduto = [
                        'p3_ordem_id' => $ordemId,
                        'p3_produto_id' => $produtoId,
                        'p3_quantidade' => $quantidade,
                        'p3_valor_unitario' => $valorUnitario,
                        'p3_valor_total' => $valorTotal,
                        'p3_observacoes' => $observacoes
                    ];

                    $produtosSalvos[] = $produtosOrdemModel->insert($dadosProduto);
                }
            }

            // Salvar serviços
            $servicosSalvos = [];
            foreach ($servicos as $servico) {
                $servicoId = $servico['servico_id'] ?? $servico['s2_servico_id'] ?? null;
                $quantidade = (int) ($servico['quantidade'] ?? $servico['s2_quantidade'] ?? 1);
                $valorUnitario = $this->parseDecimal($servico['preco'] ?? $servico['valor_unitario'] ?? $servico['s2_valor_unitario'] ?? 0);
                $valorTotal = $this->parseDecimal($servico['subtotal'] ?? $servico['valor_total'] ?? $servico['s2_valor_total'] ?? ($quantidade * $valorUnitario));
                $observacoes = $servico['observacoes'] ?? $servico['s2_observacoes'] ?? null;
                $status = $servico['status'] ?? $servico['s2_status'] ?? 'Pendente';
                $tecnicoId = $servico['tecnico_id'] ?? $servico['s2_tecnico_id'] ?? null;

                if ($servicoId && $quantidade > 0) {
                    $dadosServico = [
                        's2_ordem_id' => $ordemId,
                        's2_servico_id' => $servicoId,
                        's2_quantidade' => $quantidade,
                        's2_valor_unitario' => $valorUnitario,
                        's2_valor_total' => $valorTotal,
                        's2_observacoes' => $observacoes,
                        's2_status' => $status,
                        's2_tecnico_id' => $tecnicoId
                    ];

                    $servicosSalvos[] = $servicosOrdemModel->insert($dadosServico);
                }
            }

            // Atualizar totais na ordem
            $dadosAtualizacao = [
                'o1_valor_produtos' => $totalProdutos,
                'o1_valor_servicos' => $totalServicos,
                'o1_valor_total' => $totalGeral,
                'o1_valor_final' => $totalGeral // Por enquanto sem desconto
            ];

            $this->model->update($ordemId, $dadosAtualizacao);

            // Finalizar transação
            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->failServerError('Erro ao salvar operações');
            }

            return $this->respond([
                'success' => true,
                'message' => 'Operações salvas com sucesso',
                'produtos_salvos' => count($produtosSalvos),
                'servicos_salvos' => count($servicosSalvos),
                'total_produtos' => $totalProdutos,
                'total_servicos' => $totalServicos,
                'total_geral' => $totalGeral
            ]);

        } catch (\Exception $e) {
            $db->transRollback();
            return $this->failServerError('Erro ao salvar operações: ' . $e->getMessage());
        }
    }

    /**
     * Faturar uma ordem: define data de faturamento, observações e atualiza status para 'Faturado'.
     * Método POST: /ordens/{id}/faturar
     */
    public function faturar($id = null)
    {
        try {
            if (empty($id) || !is_numeric($id)) {
                return $this->failValidationErrors(['id' => 'ID da ordem inválido']);
            }

            // Ler payload (aceitar JSON ou form)
            $request = service('request');
            $payload = $request->getJSON(true);
            if (empty($payload)) {
                $post = $request->getPost();
                $payload = is_array($post) ? $post : [];
            }

            $dataFaturamento = $payload['data_faturamento'] ?? ($payload['data'] ?? null);
            $observacoes = $payload['observacoes'] ?? null;

            if (empty($dataFaturamento)) {
                return $this->failValidationErrors(['data_faturamento' => 'Data de faturamento é obrigatória']);
            }

            // Buscar ordem
            $ordemModel = new \App\Models\OrdemModel();
            $ordem = $ordemModel->find($id);
            if (!$ordem) {
                return $this->failNotFound('Ordem não encontrada');
            }

            // Atualizar campos
            $update = [
                'o1_data_faturamento' => $dataFaturamento,
                'o1_observacoes_conclusao' => $observacoes,
                'o1_status' => 'Faturado'
            ];

            try {
                $ok = $ordemModel->update((int)$id, $update);
            } catch (\Throwable $t) {
                log_message('error', 'OrdemController::faturar - update error: ' . $t->getMessage());
                $ok = false;
            }

            if ($ok) {
                return $this->respond(['success' => true, 'message' => 'Ordem faturada com sucesso']);
            }

            $dbError = [];
            if (isset($ordemModel->db)) {
                $dbError = $ordemModel->db->error();
            }
            return $this->respond(['success' => false, 'message' => 'Não foi possível faturar a ordem', 'dbError' => $dbError], 500);
        } catch (\Exception $e) {
            log_message('error', 'OrdemController::faturar - exception: ' . $e->getMessage());
            return $this->failServerError('Erro interno ao faturar ordem');
        }
    }

    /**
     * Gerar cupom para uma ordem (salva arquivo) e retorna URL para download
     * Método GET: /ordens/{id}/gerarCupom
     * @return JSON
     * @author Arley Richards <arleyrichards@gmail.com>
     */
    public function gerarCupom($ordemId = null)
    {
        if (!$ordemId) {
            return $this->failNotFound('ID da ordem é obrigatório');
        }

        try {
            $db = \Config\Database::connect();

            // Buscar ordem: aceita id numérico ou número da ordem (ex: OS000001)
            log_message('debug', 'gerarCupom requested param: ' . $ordemId);
            $ordem = null;
            if (is_numeric($ordemId)) {
                $ordem = $db->table('o1_ordens')->where('o1_id', $ordemId)->where('o1_deleted_at IS NULL')->get()->getRowArray();
            }
            if (!$ordem) {
                // tentar buscar pelo número da ordem
                $ordem = $db->table('o1_ordens')->where('o1_numero_ordem', $ordemId)->where('o1_deleted_at IS NULL')->get()->getRowArray();
            }
            if (!$ordem) return $this->failNotFound('Ordem não encontrada');

            // Buscar produtos da ordem
            $produtos = $db->table('p3_produtos_ordem p')
                ->select('p.*, pr.p1_nome_produto as produto_nome, pr.p1_codigo_produto as produto_codigo')
                ->join('p1_produtos pr', 'pr.p1_id = p.p3_produto_id', 'left')
                ->where('p.p3_ordem_id', $ordemId)
                ->where('p.p3_deleted_at IS NULL')
                ->get()
                ->getResultArray();

            // Mapear dados para o formato esperado pelo CupomService (sem alterar vendas DB)
            $venda = [
                'v1_id' => $ordem['o1_id'],
                'v1_numero_venda' => $ordem['o1_numero_ordem'] ?? ('OS' . str_pad($ordem['o1_id'], 6, '0', STR_PAD_LEFT)),
                'v1_created_at' => $ordem['o1_data_entrada'] ?? date('Y-m-d H:i:s'),
                'v1_vendedor_nome' => $ordem['tecnico_nome'] ?? null,
                'v1_tipo_de_pagamento' => 'a_prazo',
                'v1_desconto' => isset($ordem['o1_desconto']) ? (float) $ordem['o1_desconto'] : 0.0,
                'v1_valor_total' => isset($ordem['o1_valor_final']) ? (float) $ordem['o1_valor_final'] : ((float) ($ordem['o1_valor_total'] ?? 0)),
                'v1_valor_a_ser_pago' => isset($ordem['o1_valor_final']) ? (float) $ordem['o1_valor_final'] : ((float) ($ordem['o1_valor_total'] ?? 0)),
                'v1_observacoes' => $ordem['o1_observacoes_conclusao'] ?? $ordem['o1_observacoes_entrada'] ?? ''
            ];

            $produtosParaCupom = [];
            foreach ($produtos as $p) {
                $produtosParaCupom[] = [
                    'nome_produto' => $p['produto_nome'] ?? ($p['nome'] ?? 'Produto'),
                    'p2_quantidade' => isset($p['p3_quantidade']) ? (int) $p['p3_quantidade'] : 1,
                    'p2_valor_unitario' => isset($p['p3_valor_unitario']) ? (float) $p['p3_valor_unitario'] : (float) ($p['p3_valor_unitario'] ?? 0),
                    'p2_subtotal' => isset($p['p3_valor_total']) ? (float) $p['p3_valor_total'] : (float) ($p['p3_valor_total'] ?? 0)
                ];
            }

            // Extrair configurações do próprio campo de observações da ordem (opcional)
            $configuracoes = $this->extrairConfiguracoesCupomOrden($venda['v1_observacoes'] ?? '');

            $cupomService = new \App\Libraries\CupomService();
            $cupomInfo = $cupomService->salvarCupom($venda, $produtosParaCupom, $configuracoes);

            if ($cupomInfo && file_exists($cupomInfo['caminho'])) {
                return $this->respond([
                    'success' => true,
                    'message' => 'Cupom gerado com sucesso',
                    'cupom' => $cupomInfo,
                    // manter o parâmetro original na URL para permitir lookup por número
                    'download_url' => base_url('ordens/downloadCupom/' . $ordemId)
                ]);
            }

            return $this->failServerError('Erro ao salvar o cupom');

        } catch (\Exception $e) {
            log_message('error', 'Erro ao gerar cupom da ordem: ' . $e->getMessage());
            return $this->failServerError('Erro ao gerar cupom: ' . $e->getMessage());
        }
    }

    /**
     * Entrega o PDF do cupom da ordem inline (rota: /ordens/downloadCupom/{id})
     */
    public function downloadCupom($ordemId = null)
    {
        try {
            if (!$ordemId) return redirect()->back()->with('error', 'ID da ordem é obrigatório');

            $db = \Config\Database::connect();
            // aceitar id numérico ou número da ordem (ex: OS000001)
            log_message('debug', 'downloadCupom requested param: ' . $ordemId);
            $ordem = null;
            if (is_numeric($ordemId)) {
                $ordem = $db->table('o1_ordens')->where('o1_id', $ordemId)->where('o1_deleted_at IS NULL')->get()->getRowArray();
            }
            if (!$ordem) {
                $ordem = $db->table('o1_ordens')->where('o1_numero_ordem', $ordemId)->where('o1_deleted_at IS NULL')->get()->getRowArray();
            }
            if (!$ordem) return redirect()->back()->with('error', 'Ordem não encontrada');

            $produtos = $db->table('p3_produtos_ordem p')
                ->select('p.*, pr.p1_nome_produto as produto_nome, pr.p1_codigo_produto as produto_codigo')
                ->join('p1_produtos pr', 'pr.p1_id = p.p3_produto_id', 'left')
                ->where('p.p3_ordem_id', $ordemId)
                ->where('p.p3_deleted_at IS NULL')
                ->get()
                ->getResultArray();

            $venda = [
                'v1_id' => $ordem['o1_id'],
                'v1_numero_venda' => $ordem['o1_numero_ordem'] ?? ('OS' . str_pad($ordem['o1_id'], 6, '0', STR_PAD_LEFT)),
                'v1_created_at' => $ordem['o1_data_entrada'] ?? date('Y-m-d H:i:s'),
                'v1_vendedor_nome' => $ordem['tecnico_nome'] ?? null,
                'v1_tipo_de_pagamento' => 'a_prazo',
                'v1_desconto' => isset($ordem['o1_desconto']) ? (float) $ordem['o1_desconto'] : 0.0,
                'v1_valor_total' => isset($ordem['o1_valor_final']) ? (float) $ordem['o1_valor_final'] : ((float) ($ordem['o1_valor_total'] ?? 0)),
                'v1_valor_a_ser_pago' => isset($ordem['o1_valor_final']) ? (float) $ordem['o1_valor_final'] : ((float) ($ordem['o1_valor_total'] ?? 0)),
                'v1_observacoes' => $ordem['o1_observacoes_conclusao'] ?? $ordem['o1_observacoes_entrada'] ?? ''
            ];

            $produtosParaCupom = [];
            foreach ($produtos as $p) {
                $produtosParaCupom[] = [
                    'nome_produto' => $p['produto_nome'] ?? ($p['nome'] ?? 'Produto'),
                    'p2_quantidade' => isset($p['p3_quantidade']) ? (int) $p['p3_quantidade'] : 1,
                    'p2_valor_unitario' => isset($p['p3_valor_unitario']) ? (float) $p['p3_valor_unitario'] : (float) ($p['p3_valor_unitario'] ?? 0),
                    'p2_subtotal' => isset($p['p3_valor_total']) ? (float) $p['p3_valor_total'] : (float) ($p['p3_valor_total'] ?? 0)
                ];
            }

            $configuracoes = $this->extrairConfiguracoesCupomOrden($venda['v1_observacoes'] ?? '');

            $cupomService = new \App\Libraries\CupomService();
            $pdf = $cupomService->gerarCupom($venda, $produtosParaCupom, $configuracoes);

            $nomeArquivo = 'cupom_ordem_' . ($venda['v1_numero_venda'] ?? $venda['v1_id']) . '.pdf';

            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . $nomeArquivo . '"');
            header('Content-Transfer-Encoding: binary');
            header('Accept-Ranges: bytes');

            $pdf->Output($nomeArquivo, 'I');
            exit;

        } catch (\Exception $e) {
            log_message('error', 'Erro ao gerar/download cupom da ordem: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao gerar cupom para download');
        }
    }

    /**
     * Extrair configurações de cupom a partir das observações da ordem
     */
    private function extrairConfiguracoesCupomOrden($observacoes)
    {
        $configuracoes = [
            'imprimir_nome_cliente' => false,
            'imprimir_garantias' => false
        ];

        if (empty($observacoes)) return $configuracoes;

        if (strpos($observacoes, 'Nome cliente: SIM') !== false) {
            $configuracoes['imprimir_nome_cliente'] = true;
        }
        if (strpos($observacoes, 'Garantias: SIM') !== false) {
            $configuracoes['imprimir_garantias'] = true;
        }

        return $configuracoes;
    }
}
