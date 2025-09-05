<?php 
namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table = 'u1_usuarios';
    protected $primaryKey = 'u1_id';
    
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    
    // Nome das colunas de timestamp
    protected $createdField = 'u1_created_at';
    protected $updatedField = 'u1_updated_at';
    protected $deletedField = 'u1_deleted_at';
    
    protected $allowedFields = [
        'u1_cpf',
        'u1_nome',
        'u1_email',
        'u1_usuario_acesso',
        'u1_senha_usuario',
        'u1_tipo_permissao',
        'u1_data_ultimo_acesso',
        'u1_token_reset_senha_acesso',
        'u1_horario_geracao_token',
        'u1_created_at',
        'u1_updated_at',
        'u1_deleted_at'
    ];
    
    protected $returnType = 'object';
    
    // Validações
    protected $validationRules = [
        'u1_cpf' => 'required|min_length[11]|max_length[14]|is_unique[u1_usuarios.u1_cpf,u1_id,{u1_id}]',
        'u1_nome' => 'required|min_length[3]|max_length[255]',
        'u1_email' => 'required|valid_email|max_length[255]|is_unique[u1_usuarios.u1_email,u1_id,{u1_id}]',
        'u1_usuario_acesso' => 'required|min_length[3]|max_length[100]|is_unique[u1_usuarios.u1_usuario_acesso,u1_id,{u1_id}]',
        'u1_senha_usuario' => 'required|min_length[6]|max_length[255]',
        'u1_tipo_permissao' => 'required|in_list[administrador,cadastro,venda,usuario]',
        'u1_token_reset_senha_acesso' => 'permit_empty|max_length[255]',
        'u1_horario_geracao_token' => 'permit_empty|valid_date'
    ,
    // Placeholder field used by is_unique rules during update operations
    'u1_id' => 'permit_empty'
    ];
    
    protected $validationMessages = [
        'u1_cpf' => [
            'is_unique' => 'Este CPF já está cadastrado no sistema'
        ],
        'u1_email' => [
            'is_unique' => 'Este e-mail já está cadastrado no sistema'
        ],
        'u1_usuario_acesso' => [
            'is_unique' => 'Este nome de usuário já está em uso'
        ],
        'u1_tipo_permissao' => [
            'in_list' => 'Tipo de permissão deve ser: administrador, cadastro, venda ou usuario'
        ]
    ];
    
    // Tipos de permissão disponíveis
    const PERMISSAO_ADMIN = 'administrador';
    const PERMISSAO_CADASTRO = 'cadastro';
    const PERMISSAO_VENDA = 'venda';
    const PERMISSAO_USUARIO = 'usuario';
    
    // Callback para hash da senha antes de inserir/atualizar
    // Preserve punctuation in CPF: removed automatic formatting callback
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];
    
    /**
     * Aplica hash na senha antes de salvar
     */
    protected function hashPassword(array $data)
    {
        if (!empty($data['data']['u1_senha_usuario'])) {
            // Verifica se a senha já está com hash (não começa com $2y$)
            if (!preg_match('/^\$2y\$/', $data['data']['u1_senha_usuario'])) {
                $data['data']['u1_senha_usuario'] = password_hash($data['data']['u1_senha_usuario'], PASSWORD_DEFAULT);
            }
        }
        return $data;
    }
    
    /**
     * Formata CPF removendo caracteres especiais
     */
    protected function formatarCpf(array $data)
    {
    // Intencional: manter formatação do CPF conforme fornecida.
    // Se no futuro desejar normalizar (remover pontos/traço), reativar a limpeza abaixo.
    // if (!empty($data['data']['u1_cpf'])) {
    //     $cpf = preg_replace('/[^0-9]/', '', $data['data']['u1_cpf']);
    //     $data['data']['u1_cpf'] = $cpf;
    // }
    return $data;
    }
    
    /**
     * Verifica credenciais de login
     */
    public function verificarLogin($usuario, $senha)
    {
        $user = $this->where('u1_usuario_acesso', $usuario)
                    ->orWhere('u1_email', $usuario)
                    ->first();
        
        if (!$user) {
            return false;
        }
        
        if (!password_verify($senha, $user->u1_senha_usuario)) {
            return false;
        }
        
        // Atualiza data do último acesso
        $this->update($user->u1_id, ['u1_data_ultimo_acesso' => date('Y-m-d H:i:s')]);
        
        return $user;
    }
    
    /**
     * Busca usuário por e-mail
     */
    public function buscarPorEmail($email)
    {
        return $this->where('u1_email', $email)->first();
    }
    
    /**
     * Busca usuário por CPF
     */
    public function buscarPorCpf($cpf)
    {
        $cpfLimpo = preg_replace('/[^0-9]/', '', $cpf);
        return $this->where('u1_cpf', $cpfLimpo)->first();
    }
    
    /**
     * Busca usuário por nome de usuário
     */
    public function buscarPorUsuario($usuario)
    {
        return $this->where('u1_usuario_acesso', $usuario)->first();
    }
    
    /**
     * Gera token para reset de senha
     */
    public function gerarTokenResetSenha($email)
    {
        $user = $this->buscarPorEmail($email);
        
        if (!$user) {
            return false;
        }
        
        $token = bin2hex(random_bytes(32));
        $dados = [
            'u1_token_reset_senha_acesso' => $token,
            'u1_horario_geracao_token' => date('Y-m-d H:i:s')
        ];
        
        return $this->update($user->u1_id, $dados) ? $token : false;
    }
    
    /**
     * Verifica token de reset de senha
     */
    public function verificarTokenResetSenha($token)
    {
        $user = $this->where('u1_token_reset_senha_acesso', $token)->first();
        
        if (!$user) {
            return false;
        }
        
        // Verifica se o token não expirou (24 horas)
        if ($user->u1_horario_geracao_token) {
            $horarioToken = strtotime($user->u1_horario_geracao_token);
            $agora = time();
            
            if (($agora - $horarioToken) > 86400) { // 24 horas
                return false;
            }
        }
        
        return $user;
    }
    
    /**
     * Altera senha do usuário
     */
    public function alterarSenha($userId, $novaSenha)
    {
        $dados = [
            'u1_senha_usuario' => $novaSenha,
            'u1_token_reset_senha_acesso' => null,
            'u1_horario_geracao_token' => null
        ];
        
        return $this->update($userId, $dados);
    }
    
    /**
     * Verifica se usuário tem permissão específica
     */
    public function temPermissao($userId, $permissao)
    {
        $user = $this->find($userId);
        
        if (!$user) {
            return false;
        }
        
        // Administrador tem acesso a tudo
        if ($user->u1_tipo_permissao === self::PERMISSAO_ADMIN) {
            return true;
        }
        
        return $user->u1_tipo_permissao === $permissao;
    }
    
    /**
     * Verifica se usuário é administrador
     */
    public function isAdmin($userId)
    {
        return $this->temPermissao($userId, self::PERMISSAO_ADMIN);
    }
    
    /**
     * Lista usuários por tipo de permissão
     */
    public function listarPorPermissao($permissao)
    {
        return $this->where('u1_tipo_permissao', $permissao)
                   ->orderBy('u1_nome', 'ASC')
                   ->findAll();
    }
    
    /**
     * Busca usuários ativos (não deletados)
     */
    public function buscarAtivos()
    {
        return $this->orderBy('u1_nome', 'ASC')->findAll();
    }
    
    /**
     * Busca usuários com permissão de venda
     */
    public function buscarVendedores()
    {
        return $this->where('u1_tipo_permissao', self::PERMISSAO_VENDA)
                   ->orWhere('u1_tipo_permissao', self::PERMISSAO_ADMIN)
                   ->orderBy('u1_nome', 'ASC')
                   ->findAll();
    }
    
    /**
     * Atualiza dados do último acesso
     */
    public function atualizarUltimoAcesso($userId)
    {
        return $this->update($userId, [
            'u1_data_ultimo_acesso' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Busca estatísticas de usuários
     */
    public function getEstatisticas()
    {
        $totalUsuarios = $this->countAllResults();
        $usuariosAtivos = $this->where('u1_deleted_at', null)->countAllResults();
        
        $porPermissao = $this->select('u1_tipo_permissao, COUNT(*) as total')
                           ->groupBy('u1_tipo_permissao')
                           ->findAll();
        
        return [
            'total_usuarios' => $totalUsuarios,
            'usuarios_ativos' => $usuariosAtivos,
            'por_permissao' => $porPermissao
        ];
    }
    
    /**
     * Valida CPF do usuário
     */
    public function validarCpf($cpf)
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        
        // Verifica se foi informado todos os digitos corretamente
        if (strlen($cpf) != 11) {
            return false;
        }
        
        // Verifica se foi informada uma sequência de digitos repetidos
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }
        
        // Faz o cálculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        
        return true;
    }
}