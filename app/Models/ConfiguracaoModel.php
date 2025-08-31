<?php 
namespace App\Models;

use CodeIgniter\Model;

class ConfiguracaoModel extends Model
{
    protected $table = 'c3_configuracoes';
    protected $primaryKey = 'c3_id';
    
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    
    protected $createdField = 'c3_created_at';
    protected $updatedField = 'c3_updated_at';
    protected $deletedField = 'c3_deleted_at';
    
    protected $allowedFields = [
        'c3_nome_app',
        'c3_versao_app',
        'c3_nome_empresa',
        'c3_cnpj_empresa',
        'c3_email_contato',
        'c3_telefone_empresa',
        'c3_site_empresa',
        'c3_endereco_empresa',
        'c3_logo_path',
        'c3_favicon_path',
        'c3_timezone',
        'c3_idioma',
        'c3_moeda',
        'c3_simbolo_moeda',
        'c3_casas_decimais',
        'c3_separador_decimal',
        'c3_separador_milhar',
        'c3_tema',
        'c3_limite_backup',
        'c3_email_notificacoes',
        'c3_smtp_host',
        'c3_smtp_port',
        'c3_smtp_usuario',
        'c3_smtp_senha',
        'c3_smtp_criptografia',
        'c3_status_loja',
        'c3_mensagem_manutencao',
        'c3_created_at',
        'c3_updated_at',
        'c3_deleted_at'
    ];
    
    protected $returnType = 'object';
    
    // Validações
    protected $validationRules = [
        'c3_nome_app' => 'required|min_length[3]|max_length[255]',
        'c3_versao_app' => 'required|max_length[20]',
        'c3_nome_empresa' => 'required|min_length[3]|max_length[255]',
        'c3_cnpj_empresa' => 'permit_empty|min_length[14]|max_length[18]',
        'c3_email_contato' => 'required|valid_email|max_length[255]',
        'c3_telefone_empresa' => 'permit_empty|max_length[15]',
        'c3_site_empresa' => 'permit_empty|valid_url|max_length[255]',
        'c3_timezone' => 'required|max_length[50]',
        'c3_idioma' => 'required|max_length[10]',
        'c3_moeda' => 'required|max_length[10]',
        'c3_simbolo_moeda' => 'required|max_length[5]',
        'c3_casas_decimais' => 'required|integer|in_list[0,1,2,3]',
        'c3_separador_decimal' => 'required|max_length[5]',
        'c3_separador_milhar' => 'required|max_length[5]',
        'c3_tema' => 'required|in_list[light,dark,auto]',
        'c3_limite_backup' => 'required|integer|greater_than[0]',
        'c3_email_notificacoes' => 'permit_empty|valid_email|max_length[255]',
        'c3_smtp_host' => 'permit_empty|max_length[255]',
        'c3_smtp_port' => 'permit_empty|integer',
        'c3_smtp_usuario' => 'permit_empty|max_length[255]',
        'c3_smtp_senha' => 'permit_empty|max_length[255]',
        'c3_smtp_criptografia' => 'permit_empty|in_list[ssl,tls,none]',
        'c3_status_loja' => 'required|in_list[aberta,fechada,manutencao]'
    ];
    
    protected $validationMessages = [
        'c3_email_contato' => [
            'valid_email' => 'O e-mail de contato deve ser válido'
        ],
        'c3_site_empresa' => [
            'valid_url' => 'O site da empresa deve ser uma URL válida'
        ],
        'c3_casas_decimais' => [
            'in_list' => 'As casas decimais devem ser entre 0 e 3'
        ]
    ];
    
    // Callback para formatação automática
    protected $beforeInsert = ['formatarCampos'];
    protected $beforeUpdate = ['formatarCampos'];
    
    /**
     * Formata campos antes de salvar
     */
    protected function formatarCampos(array $data)
    {
        // Formata CNPJ (remove caracteres especiais)
        if (!empty($data['data']['c3_cnpj_empresa'])) {
            $data['data']['c3_cnpj_empresa'] = preg_replace('/[^0-9]/', '', $data['data']['c3_cnpj_empresa']);
        }
        
        // Formata telefone (remove caracteres especiais)
        if (!empty($data['data']['c3_telefone_empresa'])) {
            $data['data']['c3_telefone_empresa'] = preg_replace('/[^0-9]/', '', $data['data']['c3_telefone_empresa']);
        }
        
        return $data;
    }
    
    /**
     * Busca configurações do sistema
     */
    public function buscarConfiguracoes()
    {
        $configuracoes = $this->first();
        
        if (!$configuracoes) {
            // Se não existir configurações, cria um registro padrão
            return $this->criarConfiguracoesPadrao();
        }
        
        return $configuracoes;
    }
    
    /**
     * Cria configurações padrão do sistema
     */
    public function criarConfiguracoesPadrao()
    {
        $dadosPadrao = [
            'c3_nome_app' => 'Sistema PDV',
            'c3_versao_app' => '1.0.0',
            'c3_nome_empresa' => 'Sua Empresa',
            'c3_email_contato' => 'contato@empresa.com',
            'c3_timezone' => 'America/Sao_Paulo',
            'c3_idioma' => 'pt-BR',
            'c3_moeda' => 'BRL',
            'c3_simbolo_moeda' => 'R$',
            'c3_casas_decimais' => 2,
            'c3_separador_decimal' => ',',
            'c3_separador_milhar' => '.',
            'c3_tema' => 'dark',
            'c3_limite_backup' => 30,
            'c3_status_loja' => 'aberta'
        ];
        
        $this->insert($dadosPadrao);
        return $this->find($this->insertID());
    }
    
    /**
     * Atualiza configurações do sistema
     */
    public function atualizarConfiguracoes($dados)
    {
        $configuracoes = $this->first();
        
        if (!$configuracoes) {
            return $this->insert($dados);
        }
        
        return $this->update($configuracoes->c3_id, $dados);
    }
    
    /**
     * Busca configuração específica
     */
    public function buscarConfiguracao($chave)
    {
        $configuracoes = $this->buscarConfiguracoes();
        return $configuracoes->$chave ?? null;
    }
    
    /**
     * Verifica se o sistema está em manutenção
     */
    public function estaEmManutencao()
    {
        $configuracoes = $this->buscarConfiguracoes();
        return $configuracoes->c3_status_loja === 'manutencao';
    }
    
    /**
     * Formata valor monetário conforme configurações
     */
    public function formatarMoeda($valor)
    {
        $configuracoes = $this->buscarConfiguracoes();
        
        $valorFormatado = number_format(
            (float)$valor,
            $configuracoes->c3_casas_decimais,
            $configuracoes->c3_separador_decimal,
            $configuracoes->c3_separador_milhar
        );
        
        return $configuracoes->c3_simbolo_moeda . ' ' . $valorFormatado;
    }
    
    /**
     * Busca configurações de e-mail
     */
    public function buscarConfiguracoesEmail()
    {
        $configuracoes = $this->buscarConfiguracoes();
        
        return [
            'protocol' => 'smtp',
            'SMTPHost' => $configuracoes->c3_smtp_host,
            'SMTPPort' => $configuracoes->c3_smtp_port,
            'SMTPUser' => $configuracoes->c3_smtp_usuario,
            'SMTPPass' => $configuracoes->c3_smtp_senha,
            'SMTPCrypto' => $configuracoes->c3_smtp_criptografia,
            'mailType' => 'html',
            'charset' => 'utf-8'
        ];
    }
    
    /**
     * Verifica se configurações de e-mail estão completas
     */
    public function emailConfigurado()
    {
        $configuracoes = $this->buscarConfiguracoes();
        
        return !empty($configuracoes->c3_smtp_host) &&
               !empty($configuracoes->c3_smtp_port) &&
               !empty($configuracoes->c3_smtp_usuario) &&
               !empty($configuracoes->c3_smtp_senha);
    }
    
    /**
     * Busca timezone configurado
     */
    public function getTimezone()
    {
        return $this->buscarConfiguracao('c3_timezone') ?? 'America/Sao_Paulo';
    }
    
    /**
     * Busca idioma configurado
     */
    public function getIdioma()
    {
        return $this->buscarConfiguracao('c3_idioma') ?? 'pt-BR';
    }
    
    /**
     * Busca tema configurado
     */
    public function getTema()
    {
        return $this->buscarConfiguracao('c3_tema') ?? 'dark';
    }
}