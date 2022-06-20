<?php

namespace App\Models\Cnpq;

use CodeIgniter\Model;

class Header extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'headers';
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

    function form()
        {
            $erro = '';
            $LattesData = new \App\Models\Lattes\LattesData();
            $proc = '';
            if (isset($_POST['process']))
                {
                    $proc = $_POST['process'];
                    $proc = $LattesData->padroniza_processo($proc); 
                    
                    switch ($proc[1])
                        {
                            case '0':
                                $proc = $proc[0];
                                break;
                            case '2':
                                $erro = 'ERRO - '.$proc[1];
                                $proc = '';
                                break;
                            default:
                                $erro = 'ERRO - '.$proc[1];
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
            $sx .= form_open();
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

    function header()
        {
            $sx = '';
            $sx .= view('header/head');
            $sx .= '<body>';
            return $sx;
        }

    function footer()
        {
            $file = '/var/www/dataverse/branding/custom-footer.html';
            if (file_exists($file)) {
                $header = file_get_contents($file);
            } else {
                $file = '../../Dataverse/cnpq/branding/custom-footer.html';
                if (file_exists($file)) {
                    $header = file_get_contents($file); 
                } else {
                    $header = 'HEADER NOT FOUND';
                }
            }
            return $header;
        }        
}
