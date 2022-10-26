<?php

namespace App\Models\Dataverse;

use CodeIgniter\Model;

class FAQ extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'authenticateduser';
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


    function index($d1='',$d2='',$d3='')
    {
        $sx = '';
        $erro['Server refused connection at: http://localhost:8983/solr/collection1'] = 'Inicar o SOLR';
        foreach($erro as $key=>$value)
        {
            $sx .= '<h4>Erro: '.$key.'</h4>';
            $sx .= '<h5>Solução: '.$value.'</h5>';
            $sx .= '<hr>';
        }
        return $sx;
    }
}
