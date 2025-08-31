<?php 
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ConfiguracaoModel;

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
            'validation' => session('validation')
        ];
        
        return view('admin/configuracoes', $data);
    }
    
    public function salvar()
    {
        // Verificar permissão (apenas administradores)
        if (!AuthController::checkAuth(UsuarioModel::PERMISSAO_ADMIN)) {
            return redirect()->to('/acesso-negado');
        }
        
        $dados = $this->request->getPost();
        
        if ($this->configuracaoModel->atualizarConfiguracoes($dados)) {
            return redirect()->to('/admin/configuracoes')->with('success', 'Configurações atualizadas com sucesso!');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->configuracaoModel->errors());
        }
    }
}