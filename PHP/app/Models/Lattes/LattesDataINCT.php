<?php

namespace App\Models\Lattes;

use CodeIgniter\Model;

class LattesDataINCT extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'lattesdataincts';
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



    function register($dt, $id)
    {
        $Dataverse = new \App\Models\Dataverse\Dataverse();
        $Dataset = new \App\Models\Dataverse\Datasets();
        $Util = new \App\Models\Lattes\LattesDataUtils();
        $js = new \App\Models\Lattes\LattesDataINCT();
        $dv = $js->recover($dt, $id);
        $dv['id'] = $id;

        /****************************************** USER */
        $user = $Util->getUser($dv);

        /************ CRIA DATAVERSE - CHAMADA DATAVERSE */
        $chamada = $Util->getChamada($dt, $user);
        $Dataverse->CreateDataverse($chamada, 'beneficiarios');
        $parent = $chamada['alias'];
        jslog('Create Dataverse - Chamada ' . $parent);

        /*********** CRIA DATAVERSE -  PROJETO DATAVERSE */
        $projeto = $Util->getProjeto($dt, $user);
        $Dataverse->CreateDataverse($projeto, $parent);
        jslog('Create Dataverse - Projeto ' . $parent);

        /******************************* PROJETO DATASET */
        $parent = $projeto['alias'];
        jslog('Process Dataset - Projeto ' . $parent);
        $dataset = $Util->getDataset($dt, $user);

        $dd['api'] = 'api/dataverses/' . $parent . '/datasets';
        $dd['user'] = $user;
        $dd['id'] = $id;
        $Dataset->CreateDatasets($dd, $dataset, $parent);
        jslog('Create Dataset - Projeto ' . $parent);

        return "xx";
    }

    function recover($dt, $id)
    {
        return $dt;
        $projeto = (array)$dt['projeto'];
        $titulo = (string)$projeto['titulo'];
        $titulo = nbr_author($titulo, 7);
        $dti = brtos($dt['dataInicioVigencia']);
        $dtf = brtos($dt['dataTerminoVigencia']);

        $processo = (string)$dt['numeroProcesso'];

        $abs = (string)$projeto['resumo'];

        /**************************************************/
        $key = (string)$dt['palavrasChave'];
        $key = troca($key, ', ', ';');
        $key = troca($key, '. ', ';');
        $key = explode(';', $key);
        $keys = '<ul>';
        foreach ($key as $word) {
            $word = nbr_author($word, 7);
            $keys .= '<li>' . $word . '</li>';
        }
        $keys .= '</ul>';

        $dv = array();
        $dv['alias'] = $id;
        $dv['datasetVersion'] = array();
        //$dv['datasetVersion']['termsOfUse'] = 'CC0 Waiver';
        //$dv['datasetVersion']['license'] = 'CC0';
        $dv['datasetVersion']['termsOfUse'] = 'CC-BY 4.0';
        $dv['datasetVersion']['license'] = 'CC-BY 4.0';
        /********************** metadataBlocks */

        /********************************************** Citation */
        $ci = array();
        array_push($ci, $this->primitive('title', $titulo));
        array_push($ci, $this->primitive('productionDate', $this->date($dti)));

        /********************************************** Description */
        $desc = array();
        array_push($desc, $this->primitive('dsDescriptionValue', $abs));
        /* CITATION */
        array_push($ci, $this->compound('dsDescription', $desc, 'dsDescriptionValue'));

        /** Subject */
        array_push($ci, $this->controlledVocabulary('subject', array('Genetica')));

        $mb['citation']['fields'] = $ci;

        /* Display Name */
        $mb['citation']['displayName'] = "Display Name Metadata";

        /** Author */
        $auth = array();
        array_push($auth, $this->primitive('authorAffiliation', 'CNPq'));
        array_push($auth, $this->primitive('authorName', 'Fulando de Tal'));
        /* CITATION */
        array_push($ci, $this->compound('author', $auth));
        $mb['citation']['fields'] = $ci;

        /* Metada Block */
        $dv['datasetVersion']['metadataBlocks'] = $mb;
        $dv['id'] = $id;
        if ((!isset($_ENV['DATAVERSE_URL'])) or (!isset($_ENV['DATAVERSE_APIKEY']))) {
            echo "ERRO: defina a variavel DATAVERSE_URL e DATAVERSE_APIKEY no .env";
            exit;
        }
        $dv['url'] = $_ENV['DATAVERSE_URL'];
        $dv['apikey'] = $_ENV['DATAVERSE_APIKEY'];

        return $dv;

        //$json = json_encode($dv,JSON_PRETTY_PRINT);
        //$file = $this->filename($id);
        //file_put_contents($file,$json);
    }
}
