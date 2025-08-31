<?php 
namespace App\Helpers;

use App\Models\ConfiguracaoModel;

class ConfigHelper
{
    public static function get($chave = null)
    {
        $model = new ConfiguracaoModel();
        
        if ($chave === null) {
            return $model->buscarConfiguracoes();
        }
        
        return $model->buscarConfiguracao($chave);
    }
    
    public static function appName()
    {
        return self::get('c3_nome_app') ?? 'Sistema PDV';
    }
    
    public static function versao()
    {
        return self::get('c3_versao_app') ?? '1.0.0';
    }
    
    public static function empresa()
    {
        return self::get('c3_nome_empresa') ?? 'Sua Empresa';
    }
    
    public static function formatarMoeda($valor)
    {
        $model = new ConfiguracaoModel();
        return $model->formatarMoeda($valor);
    }
    
    public static function tema()
    {
        return self::get('c3_tema') ?? 'dark';
    }
    
    public static function timezone()
    {
        return self::get('c3_timezone') ?? 'America/Sao_Paulo';
    }
    
    public static function emManutencao()
    {
        $model = new ConfiguracaoModel();
        return $model->estaEmManutencao();
    }
}