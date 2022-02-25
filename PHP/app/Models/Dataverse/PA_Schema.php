<?php

namespace App\Models\Dataverse;

use CodeIgniter\Model;

class PA_Schema extends Model
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
        'hidden','string:100','string:100',
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
            $sx = '==>'.$d1;
            switch($d1)
                {
                    case 'delete':
                        $this->delete($d2);
                        $sx .= $this->tableview();
                        break;
                    case 'datafieldEd':
                        $sx .= $this->datafieldEd($d2,$d3,$d4);
                        break;
                    case 'edit':
                        $sx .= $this->edit($d2,$d3,$d4);
                        break;                        
                    case 'viewid':
                        $sx .= $this->viewid($d2);
                        break;
                    default:
                        $sx .= $this->tableview();
                        break;
                }
            return $sx;
        }
    function edit($d1,$d2,$d3)
        {
            $PA_Field = new \App\Models\Dataverse\PA_Field();
            $this->id = $d1;
            $this->path = PATH.'/edit/'.$d1;
            if ($d1 == 0)
                {
                    $this->path_back = PATH.'/';
                } else {
                    $this->path_back = PATH.'/viewid/'.$d1;
                }
            

            $sx = h(lang('dataverse.SchemaEd'),1);
            $sx .= form($this);
            $sx = bs(bsc($sx,12));
            return $sx;
        }

    function datafieldEd($d1,$d2,$d3)
        {
            $PA_Field = new \App\Models\Dataverse\PA_Field();
            $this->id = $d1;
            $this->path = PATH.'datafieldEd/'.$d1;
            $sx = h(lang('dataverse.datafieldEd'),1);
            $sx .= $PA_Field->editar($d1,$d3);
            return $sx;
        }

    function viewid($id)
        {
            $PA_Field = new \App\Models\Dataverse\PA_Field();
            $sx = '';
            $sql = "select * from ".$this->table." where id_mt = '".$id."'";
            $query = $this->db->query($sql);
            $row = $query->getRowArray();
            
            $sx .= '<h2>'.$row['mt_displayName'].'</h2>';
            $sx .= '<p>'.$row['mt_blockURI'].'</p>';
            
            $sx = bs(bsc($sx),12);

            $sx .= $PA_Field->viewid($id);

            return($sx);
        }

    function tableview()
        {
            $this->path = PATH;
            $sx = tableview($this);
            $sx = bs(bsc($sx));
            return $sx;
        }
}
