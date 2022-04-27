<?php

namespace App\Models\Dataverse;

use CodeIgniter\Model;

class PA_Vocabulary extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'dataverse_tsv_vocabulary';
    protected $primaryKey       = 'id_vc';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
                'id_vc','vc_name','vc_value',
                'vc_class_1','vc_order'
    ];

    protected $typeFields    = [
                'hidden','sql:m_name:m_name:(select m_name from dataverse_tsv_metadata group by m_name) as tabela','string:100',
                'string:100','[0-200]'
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

    function index($d1,$d2,$d3)
        {
            $sx = '';
            switch($d1)
                {
                    case 'vc':
                        $sx = $this->viewid($d2);
                        break;
                    case 'edit':
                        $sx = $this->edit($d2,$d3);
                        break;
                }
            return $sx;
        }

    function edit($d2,$d3)
        {
            $this->path = PATH.MODULE.'dataverse/pa/vocabulary/';
            $this->path_back = PATH.MODULE.'dataverse/pa/vocabulary/vc/'.get("vc_name");
            $this->id = $d2;
            $sx = form($this);
            $sx = bs(bsc($sx,12));
            return $sx;            
        }

    function viewid($id)
        {
            $dt = $this
            ->where('vc_name',$id)
            ->orderBy('vc_order','asc');

            $this->path = PATH.MODULE.'dataverse/pa/vocabulary';

            $sx = tableview($this);

            $sx = bs(bsc($sx,12));
            return $sx;
            echo '<pre>';
            print_r($dt);
        }

    function vocabularies_name($id)
        {
            $PA_Field = new \App\Models\Dataverse\PA_Field();
            $dt = $PA_Field
                ->select("m_name")
                ->where('m_allowControlledVocabulary',1)
                ->where('m_active',1)
                ->where('m_schema',$id)
                ->groupBy('m_name')
                ->orderBy('m_name')
                ->findAll();
            return $dt;
        }  

    function export($id)
        {
            $sep = "\t";
            $dt = $this
            ->where('vc_name',$id)
            ->orderBy('vc_order','asc')
            ->findAll();
            $sx = '';
            for ($r=0;$r < count($dt);$r++)
                {
                    $line = $dt[$r];
                    $sx .= $sep.trim($line['vc_name']);
                    $sx .= $sep.trim($line['vc_value']);
                    $sx .= $sep.trim($line['vc_class_1']);
                    $sx .= $sep.round(trim($line['vc_order']));
                    $sx .= "\n";
                }
            $sx = substr($sx,0,strlen($sx)-2);
            return $sx;
        }      

    function vocabularies($id)
        {
            $PA_Field = new \App\Models\Dataverse\PA_Field();
            $dt = $this->vocabularies_name($id);
            if (count($dt) > 0)
            {
                $sx = lang('dataverse.vocabulary_select').': ';
                for ($r=0;$r < count($dt);$r++)
                    {
                        $line = $dt[$r];
                        $link = '<a href="'.PATH.MODULE.'dataverse/pa/vocabulary/vc/'.$line['m_name'].'">';
                        $linka = '</a>';
                        $sx .= $link.'<b>'.$line['m_name'].'</b>'.$linka.' | ';
                    }
            } else {
                $sx = '';
            }
            return $sx;
        }

    function import($id,$ln)
        {
            $fd = explode("\t",$ln);

            $dt = $this
                ->where('vc_name',$fd[1])
                ->where('vc_value',$fd[2])
                ->findAll();

                
            $sx = lang('vocabulary').': '.'<b>'.$fd[1].'</b>'.'=>'.'<i>'.$fd[2].'</i> ';
            
            $dd['vc_name'] = $fd[1];
            $dd['vc_value'] = $fd[2];
            $dd['vc_class_1'] = $fd[3];
            $dd['vc_order'] = $fd[4];
            if (count($dt) == 0)
                {
                    $this->set($dd)->insert();
                    $sx .= '<b class="text-primary">'.lang('dataverse.insered').'</b>';
                }
            else
                {
                    $this->set($dd)->where('id_vc',$dt[0]['id_vc'])->update();
                    $sx .= '<b class="text-success">'.lang('dataverse.update').'</b>';
                }
        return $sx;
        }
}
