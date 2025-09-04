<?php 
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Helpers\ConfigHelper;
use App\Models\ConfiguracaoModel;
use App\Models\UsuarioModel;

class ConfiguracaoController extends BaseController
{
    protected $configuracaoModel;
    
    public function __construct()
    {
        $this->configuracaoModel = new ConfiguracaoModel();
    }
    
    public function index()
    {
        // Verificar permissão (apenas administradores)
        if (!AuthController::checkAuth(UsuarioModel::PERMISSAO_ADMIN)) {
            return redirect()->to('/acesso-negado');
        }
        
        $data = [
            'title' => 'Configurações do Sistema',
            'configuracoes' => $this->configuracaoModel->buscarConfiguracoes(),
            'validation' => session('validation'),
            'appName' => ConfigHelper::appName(),
            'empresa' => ConfigHelper::empresa(),
            'logo'    => ConfigHelper::get('c3_logo_path') ?? IMG_PATH . 'logo.png',
        ];
        
        return view('configuracoes', $data);
    }
    
    public function salvar()
    {
        // Verificar permissão (apenas administradores)
        if (!AuthController::checkAuth(UsuarioModel::PERMISSAO_ADMIN)) {
            return redirect()->to('/acesso-negado');
        }
        
        $dados = $this->request->getPost();
        
        if ($this->configuracaoModel->atualizarConfiguracoes($dados)) {
            return redirect()->to('configuracoes')->with('success', 'Configurações atualizadas com sucesso!');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->configuracaoModel->errors());
        }
    }
}