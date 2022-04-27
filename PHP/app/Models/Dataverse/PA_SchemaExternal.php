<?php

namespace App\Models\Dataverse;

use CodeIgniter\Model;

class PA_SchemaExternal extends Model
{
    protected $DBGroup          = 'schema';
    protected $table            = 'dataverse_tsv_schema';
    protected $primaryKey       = 'id_mt';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_mt','mt_name','mt_dataverseAlias',
        'mt_displayName','mt_blockURI'
    ];

    protected $typeFields    = [
        'hidden','string:100*','string:100',
        'string:100','string:100'
    ];    

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

    function index($d1,$d2,$d3,$d4)
        {
            $sx = '';
            $sx .= breadcrumbs();
            switch($d1)
                {
                    default:
                    $sx .= $this->help();
                }
            return $sx;
        }
    function help()
        {
$sx = '';
$sx .= '';
        }
}
