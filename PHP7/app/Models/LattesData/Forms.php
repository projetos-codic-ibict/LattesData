<?php

namespace App\Models\LattesData;

use CodeIgniter\Model;

class Forms extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = '*';
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

    function Home()
    {        
        $sx = '';
        $LattesData = new \App\Models\LattesData\LattesData();
            if (isset($_POST['process']) and (strlen($_POST['process']) > 0))
                {
                    $proc = $_POST['process'];
                    $proc = $LattesData->padroniza_processo($proc);
                    jslog("Processo: ".$proc[1]);
                    if ($proc[1] != 0)
                        {
                            $sx .= $this->welcome();        
                        } else {
                            $sx .= $LattesData->show_metadate($proc);
                        }
                } else {
                    $sx .= $this->welcome();
                }        
        $sx .= '20113023806';
        return $sx;
    }

function welcome()
    {
        $sx = '';
        $sx = h('Deposite seus dados');
        $sx .= '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse ac rhoncus mi. Maecenas luctus sapien velit. Etiam eget dolor mollis, porta leo eu, dignissim ligula. Vestibulum porttitor tempus consequat. Ut tellus urna, convallis et risus nec, tincidunt placerat dui. Curabitur non lectus aliquam, iaculis erat vel, molestie nisl. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras vel mollis augue, in imperdiet ipsum. Donec maximus efficitur ex, vel laoreet massa euismod ut. Aliquam porttitor mollis odio dapibus auctor. Donec at dui et justo eleifend tincidunt. Duis at finibus mi, ac lacinia felis. Mauris et ultrices nulla. Aenean nec luctus elit. Nulla facilisi.</p>';
        $sx .= '<p>Sed placerat lacus id sagittis consectetur. Ut vitae sodales mi. Donec vel quam sed elit mattis elementum. Nunc feugiat odio a sem rhoncus, at consequat enim interdum. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris aliquam ultrices erat ac pharetra. Phasellus dictum nisi quis nunc euismod, sit amet blandit elit condimentum.</p>';
        $sx .= '<p>Donec eleifend ante eu fringilla lacinia. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum mattis ac orci vitae faucibus. Fusce cursus, ex et scelerisque commodo, tellus dui euismod felis, eu vestibulum ipsum libero eu libero. Cras in lacus et felis molestie cursus. Maecenas tempus nisl ac lacus interdum dignissim non ut velit. Suspendisse pellentesque metus arcu. Nulla facilisi. Quisque ac porta leo, sit amet porta sem. Vivamus aliquet, lacus non commodo efficitur, lacus nulla rhoncus quam, ac lobortis enim leo eu diam. Aenean accumsan ullamcorper fringilla. Nulla dapibus id quam ut condimentum. In tincidunt orci et eleifend varius.</p>';
        $sx .= '<div style="height: 200px;"></div>';
        $sx .= '<p>20203121198</p>';
        return $sx;
    }

 function form()
        {
            $erro = '';
            $LattesData = new \App\Models\LattesData\LattesData();
            $proc = '';
            if (isset($_POST['process']) and (strlen($_POST['process']) > 0))
                {
                    $proc = $_POST['process'];
                    $proc = $LattesData->padroniza_processo($proc); 

                    switch ($proc[1])
                        {
                            case '0':
                                $proc = $proc[0];
                                break;
                            case '2':
                                $erro = 'ERRO - '.$proc[0].' - '.$proc[1];
                                $proc = '';
                                break;
                            default:
                                $proc = '';
                                break;
                        }
                }
            
            $sx = '
            <div class="border border-1 border-primary" style="width: 100%;">
            <div class="card-body">
              <h1 class="card-title">Depositar</h1>
              <h5 class="card-subtitle mb-2 text-muted">Conjunto de dados (<i>Datasets</i>)</h5>
              <p class="card-text">
              ';
            $sx .= form_open(URL);
            //$sx .= '<form method="post" accept-charset="utf-8">';
            $sx .= form_input('process', '', 'class="form-control" placeholder="Número do processo"');
            $sx .= 'Ex: 123456/2022-2';
            $info = 'O número do processo do CNPQ é composto por seis dígitos, '.chr(13)
                    .' seguido de um ponto e dois dígitos. Ex: 123456/2022-2'.chr(13)
                    .' O número do processo é disponibilizado em seu termo de outorga.';

            $sx .= ' <span title="'.$info.'" style="cursor: pointer; font-size: 150%">&#x1F6C8;</span><br>';
            $sx .= form_submit('action', 'depositar', 'class="btn btn-primary" style="width: 100%;"');
            $sx .= form_close();
            $sx .= '
              </p>
            </div>
          </div>';

          if ($erro != '')
                {
                    $sx .= '<div class="alert alert-danger" role="alert">'.$erro.'</div>';
                }
          return $sx;            
        }  
}