<?php 
namespace App\Helpers;

use App\Models\LogModel;

class LogHelper
{
    public static function registrar($tipoLog, $acao, $detalhes = null, $valor = null, $idReferencia = null, $status = 'sucesso')
    {
        $model = new LogModel();
        $idUsuario = session('user_id') ?? 0;
        
        return $model->registrarLog($idUsuario, $tipoLog, $acao, $detalhes, $valor, $idReferencia, $status);
    }
    
    public static function login($usuario, $sucesso = true)
    {
        $status = $sucesso ? LogModel::STATUS_SUCESSO : LogModel::STATUS_ERRO;
        $acao = $sucesso ? "Login realizado com sucesso" : "Tentativa de login falhou";
        
        return self::registrar(LogModel::TIPO_LOGIN, $acao, "Usuário: {$usuario}", null, null, $status);
    }
    
    public static function logout($usuario)
    {
        return self::registrar(LogModel::TIPO_LOGOUT, "Logout realizado", "Usuário: {$usuario}");
    }
    
    public static function venda($tipo, $idVenda, $valor, $detalhes = '')
    {
        return self::registrar($tipo, "Venda {$tipo}", $detalhes, $valor, $idVenda);
    }
    
    public static function erro($acao, $detalhes)
    {
        return self::registrar(LogModel::TIPO_ERRO_SISTEMA, $acao, $detalhes, null, null, LogModel::STATUS_ERRO);
    }
    
    public static function acessoNegado($acao, $detalhes = '')
    {
        return self::registrar(LogModel::TIPO_ACESSO_NEGADO, $acao, $detalhes, null, null, LogModel::STATUS_ERRO);
    }
    
    public static function cadastro($tipo, $idItem, $nome)
    {
        return self::registrar($tipo, "Cadastro de {$tipo}", "Item: {$nome}", null, $idItem);
    }
    
    public static function edicao($tipo, $idItem, $nome)
    {
        return self::registrar($tipo, "Edição de {$tipo}", "Item: {$nome}", null, $idItem);
    }
}