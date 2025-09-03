<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;
use App\Helpers\LogHelper;
use App\Helpers\ConfigHelper;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseController
{
    protected $usuarioModel;
    protected $session;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
        $this->session = session();
        helper(['form', 'url', 'cookie']);
    }

    /**
     * Exibe o formulário de login
     */        
    public function index()
    {
        // Se o usuário já está logado, redireciona para dashboard
        // FUTURAMENTE SERÁ IMPLEMENTADO
        // if ($this->session->get('logged_in')) {
        //     return redirect()->to('/dashboard');
        // }

        $data = [
            'title'   => 'Login - Sistema PDV',
            'appName' => ConfigHelper::appName(),
            'empresa' => ConfigHelper::empresa(),
            'logo'    => ConfigHelper::get('c3_logo_path') ?? IMG_PATH . 'logo.png',
            'validation' => $this->session->getFlashdata('validation')
        ];
    
        return view('login', $data);
    }

    /**
     * Processa o login do usuário
     */
    public function processLogin()
    {
        // Validação dos campos
        $rules = [
            'access-user' => 'required|min_length[3]',
            'access-password' => 'required|min_length[6]'
        ];

        $messages = [
            'access-user' => [
                'required' => 'O campo usuário é obrigatório',
                'min_length' => 'Usuário deve ter pelo menos 3 caracteres'
            ],
            'access-password' => [
                'required' => 'O campo senha é obrigatório',
                'min_length' => 'Senha deve ter pelo menos 6 caracteres'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $usuario = $this->request->getPost('access-user');
        $senha = $this->request->getPost('access-password');
        $lembrar = $this->request->getPost('remember');

        // Tenta fazer login
        $user = $this->usuarioModel->verificarLogin($usuario, $senha);

        if ($user) {
            // Configura dados da sessão
            $sessionData = [
                'user_id' => $user->u1_id,
                'user_nome' => $user->u1_nome,
                'user_email' => $user->u1_email,
                'user_usuario' => $user->u1_usuario_acesso,
                'user_permissao' => $user->u1_tipo_permissao,
                'logged_in' => true,
                'login_time' => time()
            ];

            $this->session->set($sessionData);

            // Se marcou "Lembrar-me", configura cookie
            if ($lembrar) {
                $this->setRememberMeCookie($user->u1_id);
            }

            // Registra log de login bem-sucedido
            LogHelper::login($user->u1_usuario_acesso, true);

            // Redireciona conforme permissão
            return $this->redirectByPermission($user->u1_tipo_permissao);
        }

        // Registra log de tentativa falha
        LogHelper::login($usuario, false);

        return redirect()->back()->withInput()->with('error', 'Usuário ou senha inválidos!');
    }

    /**
     * Faz logout do usuário
     */
    public function logout()
    {
        // Registra log de logout
        if ($this->session->get('user_usuario')) {
            LogHelper::logout($this->session->get('user_usuario'));
        }

        // Limpa dados da sessão
        $sessionData = [
            'user_id', 'user_nome', 'user_email', 'user_usuario', 
            'user_permissao', 'logged_in', 'login_time'
        ];
        
        $this->session->remove($sessionData);
        $this->session->destroy();

        // Remove cookie de "Lembrar-me"
        $this->clearRememberMeCookie();

        return redirect()->to('/login')->with('success', 'Logout realizado com sucesso!');
    }

    /**
     * Redireciona usuário conforme sua permissão
     */
    private function redirectByPermission($permissao)
    {
        $redirectUrl = '/dashboard'; // URL padrão
        $message = 'Login realizado com sucesso!';

        switch ($permissao) {
            case UsuarioModel::PERMISSAO_ADMIN:
                $redirectUrl = 'home';
                $message = 'Bem-vindo à área administrativa!';
                break;
            
            case UsuarioModel::PERMISSAO_VENDA:
                $redirectUrl = '/vendas';
                $message = 'Acesso ao PDV liberado!';
                break;
            
            case UsuarioModel::PERMISSAO_CADASTRO:
                $redirectUrl = '/cadastros';
                $message = 'Acesso à área de cadastros concedido!';
                break;
            
            default:
                $redirectUrl = '/minha-conta';
                $message = 'Login realizado com sucesso!';
                break;
        }

        return redirect()->to($redirectUrl)->with('success', $message);
    }

    /**
     * Configura cookie de "Lembrar-me"
     */
    private function setRememberMeCookie($userId)
    {
        $token = bin2hex(random_bytes(32));
        $expiry = time() + (30 * 24 * 60 * 60); // 30 dias

        // Salva token no banco (implementação opcional)
        // $this->usuarioModel->saveRememberToken($userId, $token);

        // Configura cookie
        set_cookie('remember_me', $token, $expiry, '', '', false, true);
        set_cookie('user_id', $userId, $expiry, '', '', false, true);
    }

    /**
     * Remove cookie de "Lembrar-me"
     */
    private function clearRememberMeCookie()
    {
        delete_cookie('remember_me');
        delete_cookie('user_id');
    }

    /**
     * Exibe página de recuperação de senha
     */
    public function recuperarSenha()
    {
        $data = [
            'title' => 'Recuperar Senha - Sistema PDV'
        ];

        return view('auth/recuperar_senha', $data);
    }

    /**
     * Processa solicitação de recuperação de senha
     */
    public function processRecuperarSenha()
    {
        $rules = [
            'email' => 'required|valid_email'
        ];

        $messages = [
            'email' => [
                'required' => 'O campo e-mail é obrigatório',
                'valid_email' => 'Informe um e-mail válido'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $email = $this->request->getPost('email');
        $token = $this->usuarioModel->gerarTokenResetSenha($email);

        if ($token) {
            // Aqui você implementaria o envio de e-mail
            // $this->enviarEmailRecuperacao($email, $token);
            
            return redirect()->to('/login')->with('success', 'Instruções de recuperação enviadas para seu e-mail!');
        } else {
            return redirect()->back()->with('error', 'E-mail não encontrado em nosso sistema!');
        }
    }

    /**
     * Exibe formulário de redefinição de senha
     */
    public function redefinirSenha($token = null)
    {
        if (!$token) {
            return redirect()->to('/recuperar-senha')->with('error', 'Token inválido!');
        }

        $user = $this->usuarioModel->verificarTokenResetSenha($token);

        if (!$user) {
            return redirect()->to('/recuperar-senha')->with('error', 'Token inválido ou expirado!');
        }

        $data = [
            'title' => 'Redefinir Senha - Sistema PDV',
            'token' => $token,
            'validation' => $this->session->getFlashdata('validation')
        ];

        return view('auth/redefinir_senha', $data);
    }

    /**
     * Processa redefinição de senha
     */
    public function processRedefinirSenha()
    {
        $rules = [
            'token' => 'required',
            'nova_senha' => 'required|min_length[6]',
            'confirmar_senha' => 'required|matches[nova_senha]'
        ];

        $messages = [
            'nova_senha' => [
                'required' => 'A nova senha é obrigatória',
                'min_length' => 'A senha deve ter pelo menos 6 caracteres'
            ],
            'confirmar_senha' => [
                'required' => 'A confirmação de senha é obrigatória',
                'matches' => 'As senhas não coincidem'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $token = $this->request->getPost('token');
        $novaSenha = $this->request->getPost('nova_senha');

        $user = $this->usuarioModel->verificarTokenResetSenha($token);

        if (!$user) {
            return redirect()->to('/recuperar-senha')->with('error', 'Token inválido ou expirado!');
        }

        if ($this->usuarioModel->alterarSenha($user->u1_id, $novaSenha)) {
            // Registra log de alteração de senha
            LogHelper::registrar(
                LogHelper::TIPO_SENHA_ALTERADA,
                'Senha redefinida com sucesso',
                "Usuário: {$user->u1_usuario_acesso}"
            );

            return redirect()->to('/login')->with('success', 'Senha redefinida com sucesso!');
        } else {
            return redirect()->back()->with('error', 'Erro ao redefinir senha!');
        }
    }

    /**
     * Exibe página de acesso negado
     */
    public function acessoNegado()
    {
        // Registra log de acesso negado
        LogHelper::acessoNegado(
            'Tentativa de acesso não autorizado',
            "Usuário: " . ($this->session->get('user_usuario') ?? 'Não logado') . 
            ", URL: " . current_url()
        );

        $data = [
            'title' => 'Acesso Negado - Sistema PDV',
            'permissao_necessaria' => $this->session->getFlashdata('permissao_necessaria')
        ];

        return view('auth/acesso_negado', $data);
    }

    /**
     * Middleware para verificar autenticação
     */
    public static function checkAuth($permissaoNecessaria = null)
    {
        $session = session();
        
        // Verifica se está logado
        if (!$session->get('logged_in')) {
            return redirect()->to('/login?return=not_authenticated')->with('error', 'Por favor, faça login para continuar.');
        }

        // Verifica permissão específica se solicitada
        if ($permissaoNecessaria) {
            $usuarioModel = new UsuarioModel();
            $userId = $session->get('user_id');
            
            if (!$usuarioModel->temPermissao($userId, $permissaoNecessaria)) {
                $session->setFlashdata('permissao_necessaria', $permissaoNecessaria);
                return redirect()->to('/acesso-negado');
            }
        }

        // Verifica timeout de sessão (30 minutos)
        self::checkSessionTimeout();

        return true;
    }

    /**
     * Verifica timeout de sessão (30 minutos de inatividade)
     */
    public static function checkSessionTimeout()
    {
        $session = session();
        
        if ($session->get('logged_in')) {
            $loginTime = $session->get('login_time');
            $currentTime = time();
            
            // 30 minutos de inatividade
            if (($currentTime - $loginTime) > 1800) {
                $session->setFlashdata('error', 'Sessão expirada por inatividade.');
                return redirect()->to('/logout');
            }
            
            // Atualiza tempo da sessão
            $session->set('login_time', $currentTime);
        }
    }
}