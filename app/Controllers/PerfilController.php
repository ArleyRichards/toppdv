<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;

class PerfilController extends BaseController
{
    protected $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
    }

    public function index()
    {
        // Exige usuário autenticado
        if (! session('user_id')) {
            return redirect()->to('/login');
        }

        $usuario = $this->usuarioModel->find(session('user_id'));

        $data = [
            'title' => 'Meu Perfil',
            'usuario' => $usuario,
            'validation' => session('validation')
        ];

        return view('perfil', $data);
    }

    public function salvar()
    {
        if (! session('user_id')) {
            return redirect()->to('/login');
        }

        $id = session('user_id');
        $post = $this->request->getPost();

        // Monta dados para atualizar — apenas campos permitidos
        $dados = [];
        $fields = ['u1_nome','u1_cpf','u1_email','u1_usuario_acesso'];
        foreach ($fields as $f) {
            if (isset($post[$f])) {
                $dados[$f] = $post[$f];
            }
        }

        // Se senha informada, inclua (será hasheada pelo model)
        if (!empty($post['u1_senha_usuario'])) {
            $dados['u1_senha_usuario'] = $post['u1_senha_usuario'];
        }

    // Incluir a chave primária nos dados para que as regras "is_unique[...,u1_id,{u1_id}]"
    // do Model reconheçam que estamos atualizando o próprio registro e não acusar duplicidade.
    $dados['u1_id'] = $id;

    // Simples: tenta atualizar via model; em caso de falha, volta com erros
    if ($this->usuarioModel->update($id, $dados)) {
            // Atualiza sessão com nome/usuario/email caso tenham mudado
            $usuario = $this->usuarioModel->find($id);
            session()->set('user_nome', $usuario->u1_nome);
            session()->set('user_usuario', $usuario->u1_usuario_acesso);
            session()->set('user_email', $usuario->u1_email ?? session('user_email'));

            return redirect()->to('perfil')->with('success', 'Perfil atualizado com sucesso!');
        }

        return redirect()->back()->withInput()->with('errors', $this->usuarioModel->errors());
    }
}
