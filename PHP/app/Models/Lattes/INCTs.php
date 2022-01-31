<?php

namespace App\Models\Lattes;

use CodeIgniter\Model;

class INCTs extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'incts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    var $erro = 0;
    var $erro_msg = '';


    function erro($id)
        {
            $erro[-1] = 'Erro desconhecido';
            $erro[0] = 'Sucesso';
            $erro[1] = 'Formato inválido, use: 573710/2008-2';
            if (isset($erro[$id]))
                {
                    $msg = $erro[$id];
                } else {
                    $msg = $erro[-1];
                }
            return $msg;
        }

    function chamadas()
        {
            $chamadas = array();
            array_push($chamada,'Chamada MCTI/CNPq/CAPES/FAPs 016/2014');
            return $chamada;
        }
}
