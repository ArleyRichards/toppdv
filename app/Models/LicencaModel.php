<?php
namespace App\Models;

use CodeIgniter\Model;

class LicencaModel extends Model
{
    protected $table = 'l2_licencas';
    protected $primaryKey = 'l2_id';
    protected $returnType = 'object';
    protected $allowedFields = [
        'l2_user_id', 'l2_data_ativacao_sistema', 'l2_data_ultima_renovacao', 'l2_data_proxima_renovacao', 'l2_chave_pix'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'l2_created_at';
    protected $updatedField  = 'l2_updated_at';
    protected $deletedField  = 'l2_deleted_at';

    public function buscarPorUsuario($userId)
    {
        if (empty($userId)) return [];
        return $this->where('l2_user_id', $userId)->where('l2_deleted_at', null)->orderBy('l2_created_at', 'DESC')->findAll();
    }
}
