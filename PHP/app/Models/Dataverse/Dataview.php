<?php

namespace App\Models\Dataverse;

use CodeIgniter\Model;

class Dataview extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'dataviews';
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

    function index($d1,$d2,$d3)
        {
            $sx = '';
            $sx .= h('Dataview',1);

            $sx .= '<code>mkdir /home/dataverse/dataview/</code>';
            $sx .= '<code>cd  /home/dataverse/dataview/</code>';
            $sx .= '<br>';
            $sx .= '<br>';
            $sx .= '<code>echo "COPIA Arquivos de configuração"</code>';
            $sx .= '<code>cp /data/LattesData/_Documentation/Dataview/*.json .</code>';
            $sx .= '<br>';
            $sx .= '<br>';

            $sx .= '<code>curl -X POST -H \'Content-type: application/json\' http://localhost:8080/api/admin/externalTools --upload-file <b>file_config</b>.json</code>';
            return $sx;
        }
}
