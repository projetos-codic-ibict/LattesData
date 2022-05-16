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
            $sx .= breadcrumbs();
            switch($d1)
                {
                    case 'edit_field':
                        $PA_Field = new \App\Models\Dataverse\PA_Field();
                        $sx .= $PA_Field->editar($d2,$d3);
                        break;                        
                    case 'vocabulary':
                        $PA_Vocabulary = new \App\Models\Dataverse\PA_Vocabulary();
                        $sx .= $PA_Vocabulary->index($d2,$d3,$d4);
                        break;
                    case 'proprieties':
                        $PA_Proprieties = new \App\Models\Dataverse\PA_Proprieties();
                        $sx .= $PA_Proprieties->index($d2,$d3,$d4);
                        break;                        
                    case 'change_field':
                        $PA_Field = new \App\Models\Dataverse\PA_Field();
                        echo $PA_Field->change($d2,$d3);
                        exit;
                        break;
                    case 'api_send_schema':
                        $sx .= $this->API_send($d2,$d3,$d4);
                        break;                        
                    case 'export':
                        $rst = $this->export($d2,$d3,$d4);
                        $size = strlen($rst);

                        $PA_Field = new \App\Models\Dataverse\PA_Field();
                        $dt = $this->find($d2);
                        $file = trim($dt['mt_name']).'.tsv';

                        header('Content-Description: File Transfer');
                        header("Content-Type: text/plain; charset=UTF-8");
                        header('Content-Disposition: attachment; filename="'.$file.'"');
                        header('Expires: 0');
                        header('Content-Length: ' . $size);
                        echo $rst;
                        exit;

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
                    case 'datafieldDel':
                            $sx .= $this->datafieldDel($d2,$d3,$d4);
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

    function API_send($id)
        {
            $CR = chr(10);
            /*************************************** CRIAR RESULTADO */
            $dir = '../.tmp';
            dircheck($dir);
            $dir = '../.tmp/schema/';
            dircheck($dir);

            $PA_Field = new \App\Models\Dataverse\PA_Field();
            $dt = $this->find($id);
            $file = trim($dt['mt_name']).'.tsv';

            $filename = $dir.$file;
            $filename2 = $file;

            $rst = $this->export($id);
            file_put_contents($filename,$rst);

            /*************************************** Recupera Schema */
            $DIR = '/home/dataverse/dataverse-api/';
            $PATH = $_SERVER['DOCUMENT_ROOT'];

            $cmd1 = '';
            $cmd2 = '';
            $cmd1 .= 'echo "ACESSANDO A PASTA DE CONFIGURACOES"'.$CR;
            $cmd1 .= 'mkdir '.$DIR.$CR;
            $cmd1 .= 'cd '.$DIR.$CR;
            $cmd1 .= $CR;
            $cmd1 .= 'echo "Checando '.$DIR.'update-fields.sh"'.$CR;
            if (!file_exists($DIR.'update-fields.sh'))
                {
                    $cmd1 .= 'echo "COPIANDO ARQUIVO DE CONFIGURACAO"'.$CR;
                    $cmd1 .= 'cp '.troca($PATH,'/PHP/public','').'_Documentation/Dataverse/update-fields.sh update-fields.sh'.$CR;
                    $cmd1 .= '<br>';
                }

            $cmd1 .= 'echo "ENVIANDO METADADOS PARA O SISTEMA"'.$CR;
            $cmd1 .= 'rm *.tsv -r'.$CR;
            $f2 = $PATH.$filename;
            $f2 = troca($f2,'/PHP/public../','/PHP/');
            $cmd1 .= 'cp '.$f2.' '.$DIR.$file.$CR;            
            $cmd1 .= $CR.$CR;
            $cmd1 .= 'echo "CARREGANDO A ATUALIZACAO DO SCHEMA"'.$CR;
            $cmd1 .= 'curl http://localhost:8080/api/admin/datasetfield/load -X POST --data-binary @'.$file.' -H "Content-type: text/tab-separated-values"'.$CR;

            $cmd1 .= $CR.$CR;
            $cmd1 .= 'echo "CARREGANDO SCHEMA"'.$CR;
            $cmd1 .= 'rm schema.xml -r'.$CR;
            $cmd1 .= 'curl "http://localhost:8080/api/admin/index/solr/schema" > schema.xml '.$CR;
            
            $cmd1 .= $CR.$CR;
            $cmd1 .= 'echo "ATUALIZANDO O SCHEMA"'.$CR;
            $cmd1 .= 'cat schema.xml | ./update-fields.sh /usr/local/solr/solr-8.11.1/server/solr/collection1/conf/schema.xml'.$CR;

            $cmd2 .= 'echo "Iniciando atualização do SOLR"'.$CR;
            $cmd2 = 'cd '.$dir.$CR;
            $cmd2 .= 'echo "Atualizando o SOLR"'.$CR;
            $cmd2 .= 'curl "http://localhost:8983/solr/admin/cores?action=RELOAD&core=collection1"'.$CR;

            $cmd2 .= 'echo "End"<br>'.$CR;
            $cmd2 .= 'cd /data/LattesData'.$CR;

            $txt1 = shell_exec($cmd1);
            $txt2 = shell_exec($cmd2);
            $sx = '<code>'.troca($cmd1.$cmd2,chr(10),'<br>').'</code>';
            $sx .= h('Resultado do envio #1 - ');
            $sx .= '<tt>'.$txt1.'</tt>';
            $sx .= h('Resultado do envio #2 - SOLR');
            $sx .= '<tt>'.$txt2.'</tt>';
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

            $sx .= '<a href="'.PATH.MODULE.'dataverse/pa/viewid/'.$d1.'" class="btn btn-primary">'.lang('dataverse.return').'</a>';

            $sx = bs(bsc($sx));
            return $sx;
        }

    function export($d1)
        {
            $sx = $this->Export_metadataBlock($d1);
            return $sx;
        }


    function Export_metadataBlock($d1)
        {
            $PA_Field = new \App\Models\Dataverse\PA_Field();
            $PA_Vocabulary = new \App\Models\Dataverse\PA_Vocabulary();
            $tab = "\t";
            $dt = $this->find($d1);
            $file = trim($dt['mt_name']).'.tsv';

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
            $rst = $ln1.chr(10).$ln2.chr(10).$blnk2.$ln3;
            $rst = substr($rst,0,strlen($rst)-1);
            return $rst;
        }

    function edit($d1,$d2,$d3)
        {
            $this->id = $d1;
            $this->path = PATH.MODULE.'dataverse/pa/';
            if ($d1 == 0)
                {
                    $this->path_back = PATH.MODULE.'dataverse/pa/';
                } else {
                    $this->path_back = PATH.MODULE.'dataverse/pa/viewid/'.$d1;
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
            $this->path = PATH.MODULE.'dataverse/pa/datafieldEd/'.$d1;
            $sx = h(lang('dataverse.datafieldEd'),1);
            $sx .= $PA_Field->editar($d1,$d3);
            return $sx;
        }

    function datafieldDel($d1,$d2,$d3)
        {
            $PA_Field = new \App\Models\Dataverse\PA_Field();
            $this->id = $d1;
            $PA_Field->where('id_m',$d1)->delete();
            $sx = wclose();
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

            $sx .= '<a href="'.PATH.MODULE.'dataverse/pa/export/'.$id.'">'.lang('dataverse.export').'</a>';
            $sx .= ' | ';
            $sx .= '<a href="'.PATH.MODULE.'dataverse/pa/api_send_schema/'.$id.'">'.lang('dataverse.api_send').'</a>';            
            $sx .= ' | ';
            $sx .= '<a href="'.PATH.MODULE.'dataverse/pa/proprieties/'.$id.'">'.lang('dataverse.proprieties').'</a>';            
            $sx .= ' | ';
            $sx .= '<a href="'.PATH.MODULE.'dataverse/pa/import/'.$id.'">'.lang('dataverse.import').'</a>';
            $sx .= ' | ';
            $sx .= '<a href="'.PATH.MODULE.'dataverse/pa/edit_field/0?m_schema='.$id.'">'.lang('dataverse.new_field').'</a>';
            $sx .= ' | ';
            $sx .= '<a href="'.PATH.MODULE.'dataverse/pa/viewid/'.$id.'?reorder=1">'.lang('dataverse.reorder').'</a> ';
            $sx .= '<a href="'.PATH.MODULE.'dataverse/pa/viewid/'.$id.'?reorder=2">'.'+2'.'</a>';
            
            $sx .= '<h2>'.$row['mt_displayName'].'</h2>';
            $sx .= '<p>'.$row['mt_blockURI'].'</p>';

            $sx .= $PA_Vocabulary->vocabularies($id);
            
            $sx = bs(bsc($sx),12);

            $sx .= $PA_Field->viewid($id);

            return($sx);
        }

    function tableview()
        {
            $this->path = PATH.MODULE.'dataverse/pa';
            $sx = tableview($this);
            $sx = bs(bsc($sx));
            return $sx;
        }
}
