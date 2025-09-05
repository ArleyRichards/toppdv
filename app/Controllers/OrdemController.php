<?php

namespace App\Controllers;

use App\Helpers\ConfigHelper;
use App\Models\ClienteModel;
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

        $data['clientes'] = $clientes;

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

        // Retorna todas as ordens ativas (não deletadas)
        $ordens = $this->model
            ->select('o1_ordens.*')
            ->where('o1_ordens.o1_deleted_at', null)
            ->findAll();

        $data = [];
        foreach ($ordens as $ordem) {
            $data[] = [
                'o1_id' => isset($ordem->o1_id) ? (int) $ordem->o1_id : null,
                'o1_numero_ordem' => $ordem->o1_numero_ordem ?? null,
                'o1_cliente_id' => isset($ordem->o1_cliente_id) ? (int) $ordem->o1_cliente_id : null,
                'o1_equipamento' => $ordem->o1_equipamento ?? null,
                'o1_marca' => $ordem->o1_marca ?? null,
                'o1_modelo' => $ordem->o1_modelo ?? null,
                'o1_numero_serie' => $ordem->o1_numero_serie ?? null,
                'o1_defeito_relatado' => $ordem->o1_defeito_relatado ?? null,
                'o1_observacoes_entrada' => $ordem->o1_observacoes_entrada ?? null,
                'o1_acessorios_entrada' => $ordem->o1_acessorios_entrada ?? null,
                'o1_estado_aparente' => $ordem->o1_estado_aparente ?? null,
                'o1_tecnico_id' => isset($ordem->o1_tecnico_id) ? (int) $ordem->o1_tecnico_id : null,
                'o1_status' => $ordem->o1_status ?? null,
                'o1_prioridade' => $ordem->o1_prioridade ?? null,
                'o1_data_entrada' => $ordem->o1_data_entrada ?? null,
                'o1_data_previsao' => $ordem->o1_data_previsao ?? null,
                'o1_data_conclusao' => $ordem->o1_data_conclusao ?? null,
                'o1_data_entrega' => $ordem->o1_data_entrega ?? null,
                'o1_valor_servicos' => isset($ordem->o1_valor_servicos) ? (float) $ordem->o1_valor_servicos : 0.0,
                'o1_valor_produtos' => isset($ordem->o1_valor_produtos) ? (float) $ordem->o1_valor_produtos : 0.0,
                'o1_valor_total' => isset($ordem->o1_valor_total) ? (float) $ordem->o1_valor_total : 0.0,
                'o1_desconto' => isset($ordem->o1_desconto) ? (float) $ordem->o1_desconto : 0.0,
                'o1_valor_final' => isset($ordem->o1_valor_final) ? (float) $ordem->o1_valor_final : 0.0,
                'o1_laudo_tecnico' => $ordem->o1_laudo_tecnico ?? null,
                'o1_observacoes_conclusao' => $ordem->o1_observacoes_conclusao ?? null,
                'o1_garantia_servico' => isset($ordem->o1_garantia_servico) ? (int) $ordem->o1_garantia_servico : 0,
                'o1_created_at' => $ordem->o1_created_at ?? null,
                'o1_updated_at' => $ordem->o1_updated_at ?? null,
            ];
        }

        return $this->respond($data);
    }

    /**
     * Retorna os dados de um cliente pelo ID.
     * Método GET: /clientes/{id}
     * @param int $id
     * @return 
     * @author Arley Richards <arleyrichards@gmail.com>
     */
    public function show($id = null){
        $cliente = $this->model->find($id);

        if ($cliente) {
            // Formatar dados para exibição
            $cliente->c2_cpf = $this->formatarCpf($cliente->c2_cpf);
            $cliente->c2_telefone = $this->formatarTelefone($cliente->c2_telefone);
            $cliente->c2_celular = $this->formatarTelefone($cliente->c2_celular);

            return $this->respond($cliente);
        }

        return $this->failNotFound('Cliente não encontrado');
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

    private function generateNumeroOrdem($id = null)
    {
        $id = $id ?? $this->getNextId();
        return 'OR' . str_pad($id, 6, '0', STR_PAD_LEFT);
    }
}
