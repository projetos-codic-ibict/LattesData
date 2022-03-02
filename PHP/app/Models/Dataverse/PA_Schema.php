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
            switch($d1)
                {
                    case 'vocabulary':
                        $PA_Vocabulary = new \App\Models\Dataverse\PA_Vocabulary();
                        $sx .= $PA_Vocabulary->index($d2,$d3,$d4);
                        break;
                    case 'change_field':
                        $PA_Field = new \App\Models\Dataverse\PA_Field();
                        echo $PA_Field->change($d2,$d3);
                        exit;
                        break;
                    case 'export':
                        $sx .= $this->export($d2,$d3,$d4);
                        break;
                    case 'import':
                        $sx .= $this->import($d2,$d3,$d4);
                        break;                        
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

    function import($d1,$d2,$d3)
        {
            $PA_Field = new \App\Models\Dataverse\PA_Field();
            $PA_Vocabulary = new \App\Models\Dataverse\PA_Vocabulary();
            
            $sx = '';
            $sx .= h('Import Schema');
            if ((isset($_FILES)) and (count($_FILES) > 0))
                {
                    $file = $_FILES['file'];
                    $file = $file['tmp_name'];
                    $handle = fopen($file, "r");
                    $phase = 0;
                    if ($handle) {
                        while (($line = fgets($handle)) !== false) {
                            $cmd = substr($line,0,strpos($line,"\t"));
                            switch ($cmd)
                                {
                                    case '#metadataBlock':
                                    $phase = 1;
                                    break;

                                    case '#datasetField':
                                    $phase = 2;
                                    break;

                                    case '#controlledVocabulary':
                                    $phase = 3;
                                    break;

                                    default:
                                        switch($phase)
                                            {
                                                case 2:
                                                    $sx .= '<li>'.$PA_Field->import($d1,$line).'</li>';
                                                    break;
                                                case 3:
                                                    $sx .= '<li>'.$PA_Vocabulary->import($d1,$line).'</li>';
                                                    break;                                                    
                                            }
                                }
                        }
                        fclose($handle);
                    } else {
                        // error opening the file.
                    }                     
                } else {
                    $sx .= lang('dataverse.upload_file_tsl');
                    $sx .= '<form method="post" enctype="multipart/form-data">';
                    $sx .= '<input type="file" name="file" id="file" />';
                    $sx .= '<input type="submit" value="Import" />';
                    $sx .= '</form>';
                }

            $sx .= '<a href="'.PATH.'/viewid/'.$d1.'" class="btn btn-primary">'.lang('dataverse.return').'</a>';

            $sx = bs(bsc($sx));
            return $sx;
        }

    function export($d1,$d2,$d3)
        {
            $sx = $this->Export_metadataBlock($d1);
            echo '<pre>';
            echo $sx;
        }


    function Export_metadataBlock($d1)
        {
            $PA_Field = new \App\Models\Dataverse\PA_Field();
            $PA_Vocabulary = new \App\Models\Dataverse\PA_Vocabulary();
            $tab = "\t";
            $dt = $this->find($d1);
            $meta = array('#metadataBlock','name','dataverseAlias','displayName','blockURI');
            $field = array('','mt_name','mt_dataverseAlias','mt_displayName','mt_blockURI');
            $ln1 = '';
            $ln2 = '';
            $ln3 = '#controlledVocabulary	DatasetField	Value	identifier	displayOrder											'.chr(10);
            for ($r=0;$r < count($meta);$r++)
                {
                    $ln1 .= $meta[$r].$tab;
                    if ($field[$r] == '')
                        {
                            $ln2 .= $tab;
                        } else {
                            $ln2 .= $dt[$field[$r]].$tab;
                        }
                }

            $blnk2 = $PA_Field->Export_metadataBlock($d1);


            $vcs = $PA_Vocabulary->vocabularies_name($d1);
            for ($r=0;$r < count($vcs);$r++)
                {
                    $line = $vcs[$r];
                    $vc = $line['m_name'];
                    $ln3 .= $PA_Vocabulary->export($vc);
                }
            header("Content-Type: text/plain; charset=UTF-8");
            header('Content-Disposition: attachment; filename="'.$dt['mt_name'].'.tsv"');
            header('Expires: 0');
            echo $ln1.chr(10).$ln2.chr(10).$blnk2.$ln3;
            exit;
        }

    function edit($d1,$d2,$d3)
        {
            $this->id = $d1;
            $this->path = PATH;
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
            $PA_Vocabulary = new \App\Models\Dataverse\PA_Vocabulary();
            $sx = '';
            $sql = "select * from ".$this->table." where id_mt = '".$id."'";
            $query = $this->db->query($sql);
            $row = $query->getRowArray();

            $sx .= '<a href="'.URL.'home/dataverse/export/'.$id.'">'.lang('dataverse.export').'</a>';
            $sx .= ' | ';
            $sx .= '<a href="'.URL.'home/dataverse/import/'.$id.'">'.lang('dataverse.import').'</a>';
            
            $sx .= '<h2>'.$row['mt_displayName'].'</h2>';
            $sx .= '<p>'.$row['mt_blockURI'].'</p>';

            $sx .= $PA_Vocabulary->vocabularies($id);
            
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
