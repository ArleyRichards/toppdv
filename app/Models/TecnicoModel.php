<?php
namespace App\Models;

use CodeIgniter\Model;

class TecnicoModel extends Model
{
    protected $table = 't1_tecnicos';
    protected $primaryKey = 't1_id';

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;

    protected $createdField = 't1_created_at';
    protected $updatedField = 't1_updated_at';
    protected $deletedField = 't1_deleted_at';

    protected $allowedFields = [
        't1_nome',
        't1_cpf',
        't1_telefone',
        't1_email',
        't1_observacao',
        't1_created_at',
        't1_updated_at',
        't1_deleted_at'
    ];

    protected $returnType = 'object';

    protected $skipValidation = true;

    protected $validationRules = [
        't1_nome' => 'required|min_length[3]|max_length[255]',
        't1_cpf' => 'permit_empty|min_length[11]|max_length[14]|is_unique[t1_tecnicos.t1_cpf,t1_id,{t1_id}]',
        't1_telefone' => 'permit_empty|max_length[15]',
        't1_email' => 'permit_empty|valid_email|max_length[255]'
    ];

    protected $validationMessages = [
        't1_cpf' => [
            'is_unique' => 'Este CPF já está cadastrado como técnico'
        ]
    ];

    protected $beforeInsert = ['formatarCpf', 'formatarTelefones'];
    protected $beforeUpdate = ['formatarCpf', 'formatarTelefones'];

    /**
     * Formata CPF removendo caracteres especiais
     */
    protected function formatarCpf(array $data)
    {
        if (!empty($data['data']['t1_cpf'])) {
            // remove qualquer caractere não numérico
            $digits = preg_replace('/\D/', '', $data['data']['t1_cpf']);
            // se tiver 11 dígitos, formata como 000.000.000-00
            if (strlen($digits) === 11) {
                $data['data']['t1_cpf'] = substr($digits, 0, 3) . '.' . substr($digits, 3, 3) . '.' . substr($digits, 6, 3) . '-' . substr($digits, 9, 2);
            } else {
                // caso não seja 11 dígitos, mantemos o valor sem alteração (ou apenas dígitos)
                $data['data']['t1_cpf'] = $data['data']['t1_cpf'];
            }
        }
        return $data;
    }

    /**
     * Formata telefones removendo caracteres especiais
     */
    protected function formatarTelefones(array $data)
    {
        if (!empty($data['data']['t1_telefone'])) {
            $digits = preg_replace('/\D/', '', $data['data']['t1_telefone']);
            $len = strlen($digits);
            if ($len === 11) {
                // (00) 00000-0000
                $data['data']['t1_telefone'] = '(' . substr($digits, 0, 2) . ') ' . substr($digits, 2, 5) . '-' . substr($digits, 7, 4);
            } elseif ($len === 10) {
                // (00) 0000-0000
                $data['data']['t1_telefone'] = '(' . substr($digits, 0, 2) . ') ' . substr($digits, 2, 4) . '-' . substr($digits, 6, 4);
            } else {
                // valores fora do padrão: manter o valor original (ou apenas dígitos)
                $data['data']['t1_telefone'] = $data['data']['t1_telefone'];
            }
        }
        return $data;
    }

    /**
     * Busca técnicos ativos (não deletados)
     */
    public function buscarAtivos($limit = 100)
    {
        return $this->where('t1_deleted_at', null)
                    ->orderBy('t1_nome', 'ASC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Busca técnicos para popular um select
     */
    public function buscarParaSelect()
    {
        $tecnicos = $this->select('t1_id, t1_nome, t1_cpf')
                         ->where('t1_deleted_at', null)
                         ->orderBy('t1_nome', 'ASC')
                         ->findAll();

        $options = ['' => 'Selecione um técnico...'];
        foreach ($tecnicos as $t) {
            $label = $t->t1_nome;
            if (!empty($t->t1_cpf)) $label .= ' (' . $t->t1_cpf . ')';
            $options[$t->t1_id] = $label;
        }

        return $options;
    }

    /**
     * Busca técnico por ID
     */
    public function buscarPorId($id)
    {
        return $this->find($id);
    }
}
