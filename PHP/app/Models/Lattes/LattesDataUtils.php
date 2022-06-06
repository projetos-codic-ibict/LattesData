<?php

namespace App\Models\Lattes;

use CodeIgniter\Model;

class LattesDataUtils extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'lattesdatautils';
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

    function getUser($dt)
    {
        $du = $dt['identificadoresPessoa'];
        if (!isset($du['nomePessoa'])) {
            $nome = 'sem nome da silva';
        } else {
            $nome = $du['nomePessoa'];
        }
        $nomep = nbr_author($nome, 1);

        $firstname = mb_strtolower(substr($nomep, strpos($nomep, ',') + 1, strlen($nomep)));
        $lastname = mb_strtolower(substr($nomep, 0, strpos($nomep, ',')));
        $firstname = nbr_author($firstname, 7);
        $lastname = nbr_author($lastname, 7);

        $email = $dt['emailContato'];

        /***************** AFILIAÇÃO */
        $aff = (array)$dt['instituicoes'];
        if (isset($aff[0])) {
            $affn = (array)$aff[0];
            $sigla = $affn['siglaMacro'];
            $inst = $affn['nomeMacro'];
            if ($inst == '') {
                $sigla = $affn['sigla'];
                $inst = $affn['nome'];
            }
        } else {
            $sigla = '';
            $inst = '';
        }
        /**************** Identificadores */
        $aff = (array)$dt['identificadoresPessoa'];
        $ids = array();
        for ($r = 0; $r < count($aff); $r++) {
            $affn = (array)$aff[$r];
            $idp_type = $affn['tipo'];
            $idp_value = $affn['identificador'];
            $ids[$idp_type] = $idp_value;
        }

        $dv['firstName'] = $firstname;
        $dv['lastName'] = $lastname;
        $dv['userName'] = troca($email, '@', '-');
        $dv['affiliation'] = $inst;
        $dv['position'] = 'Research';
        $dv['email'] = $email;

        return $dv;
    }

    function getChamada($dt, $user)
    {
        $chamada = (array)$dt['chamada'];
        $DV['alias'] = troca($chamada['sigla'], ' ', '');
        $DV['name'] = $chamada['nome'];

        $contact[0]['contactEmail'] = 'lattesdata@cnpq.br';

        $DV['dataverseContacts'] = $contact;
        $DV['affiliation'] = 'CNPq';
        $DV['description'] = $chamada['nome'] . ' - ' . $chamada['sigla'];
        $DV['dataverseType'] = "LABORATORY";

        return $DV;
    }

    function getProjeto($dt, $user)
    {

        $projeto = (array)$dt['projeto'];
        $nome = $projeto['titulo'];
        $desciption = $projeto['resumo'];
        $alias = 'CNPq' . trim($dt['numeroProcesso']);
        $pre1 = substr($alias, 0, strpos($alias, '/'));
        $pre2 = substr($alias, strpos($alias, '/') + 1, 4);
        $alias = $pre2 . $pre1;

        $DV['alias'] = $alias;
        $DV['name'] = $nome;

        $contact[0]['contactEmail'] = $user['email'];

        $DV['dataverseContacts'] = $contact;
        $DV['affiliation'] = $user['affiliation'];
        $DV['description'] = $desciption;
        $DV['dataverseType'] = "LABORATORY";

        return $DV;
    }

    function getDataset($dt, $user)
    {
        $DV = array();
        /* Protocolo */
        $DV['protocol'] = 'doi';
        $DV['authority'] = getenv('DOI');
        $DV['publisher'] = 'CNPq LattesData';

        $DV['publicationDate'] = date("Y-m-d");
        $DV['metadataLanguage'] = "undefined";

        $projeto = (array)$dt['projeto'];

        /* Licence */
        //$DV['datasetVersion']['license']['name'] = 'CC BY';
        $DV['datasetVersion']['license']['name'] = 'CC BY 4.0';
        $DV['datasetVersion']['license']['uri'] = 'http://creativecommons.org/licenses/by/4.0';


        //$DV['authority'] = getenv('DOI');
        //$DV['identifier'] = troca($dt['numeroProcesso'],'/','');
        //$DV['identifier'] = substr($DV['identifier'],0,strpos($DV['identifier'],'-'));	

        $DV['datasetVersion']['fileAccessRequest'] = false;

        /* Citation */
        $fld = array();

        /*************** Title */
        $title = $projeto['titulo'];
        $fields = array('typeName' => 'title', 'multiple' => false, 'value' => $title, 'typeClass' => 'primitive');
        array_push($fld, $fields);

        /********************* Authors */

        $name = $dt['nomePessoa'];
        $email = $dt['emailContato'];
        $author = array();
        $aff = 'Desconhecida';
        $auth = array();
        $auth['authorName']['typeName'] = 'authorName';
        $auth['authorName']['multiple'] = false;
        $auth['authorName']['typeClass'] = 'primitive';
        $auth['authorName']['value'] = $name;
        $auth['authorAffiliation']['typeName'] = 'authorAffiliation';
        $auth['authorAffiliation']['multiple'] = false;
        $auth['authorAffiliation']['typeClass'] = 'primitive';
        $auth['authorAffiliation']['value'] = $aff;
        $auth3 = array($auth);
        $fields = array('typeName' => 'author', 'multiple' => true, 'typeClass' => 'compound', 'value' => $auth3);
        array_push($fld, $fields);

        /********************** Contact */
        $auth = array();
        $auth['datasetContactName']['typeName'] = 'datasetContactName';
        $auth['datasetContactName']['multiple'] = false;
        $auth['datasetContactName']['typeClass'] = 'primitive';
        $auth['datasetContactName']['value'] = $name;

        $auth['datasetContactAffiliation']['typeName'] = 'datasetContactAffiliation';
        $auth['datasetContactAffiliation']['multiple'] = false;
        $auth['datasetContactAffiliation']['typeClass'] = 'primitive';
        $auth['datasetContactAffiliation']['value'] = $aff;

        $auth['datasetContactEmail']['typeName'] = 'datasetContactEmail';
        $auth['datasetContactEmail']['multiple'] = false;
        $auth['datasetContactEmail']['typeClass'] = 'primitive';
        $auth['datasetContactEmail']['value'] = $email;

        $auth3 = array($auth);
        $fields = array('typeName' => 'datasetContact', 'multiple' => true, 'typeClass' => 'compound', 'value' => $auth3);
        array_push($fld, $fields);

        /*************** dsDescription */
        $resumo = $projeto['resumo'];
        $abstact = array();
        $abstact['dsDescriptionValue']['typeName'] = 'dsDescriptionValue';
        $abstact['dsDescriptionValue']['multiple'] = false;
        $abstact['dsDescriptionValue']['typeClass'] = 'primitive';
        $abstact['dsDescriptionValue']['value'] = $resumo;

        $auth3 = array($abstact);
        $fields = array('typeName' => 'dsDescription', 'multiple' => true, 'typeClass' => 'compound', 'value' => $auth3);
        array_push($fld, $fields);
        /*
			$abs3['dsDescriptionValue'] = array($abstact);	
			$fields = array('typeName'=>'dsDescription','multiple'=>true,'typeClass'=>'compound','value'=>$abs3);
			array_push($fld,$fields);					
			*/


        /*************** productionDate */
        /*
			$productionDate = sonumero($dt['dataInicioVigencia']);
			$productionDate = substr($productionDate,6,4).'-'.substr($productionDate,3,2).'-'.substr($productionDate,0,2);
			$fields = array('typeName'=>'productionDate','multiple'=>false,'value'=>$productionDate,'typeClass'=>'primitive');
			array_push($fld,$fields);
			*/


        /********************* subject */
        $key = $dt['palavrasChave'];
        $key = troca($key, ';', ',');
        $key = troca($key, '.', ',');
        $value = array('Medicine, Health and Life Sciences');
        $fields = array('typeName' => 'subject', 'multiple' => true, 'typeClass' => 'controlledVocabulary', 'value' => $value);
        array_push($fld, $fields);

        /* Depositor */
        $fields = array('typeName' => 'depositor', 'multiple' => false, 'typeClass' => 'primitive', 'value' => $name);
        array_push($fld, $fields);

        /* Depositor */
        $date = date("Y-m-d");
        $fields = array('typeName' => 'dateOfDeposit', 'multiple' => false, 'typeClass' => 'primitive', 'value' => $date);
        array_push($fld, $fields);

        $DV['datasetVersion']['metadataBlocks']['citation']['displayName'] = "Citation Metadata";
        $DV['datasetVersion']['metadataBlocks']['citation']['name'] = "citation";
        $DV['datasetVersion']['metadataBlocks']['citation']['fields'] = $fld;
        return $DV;
    }

    /******************************** */
    function controlledVocabulary($field, $value)
    {
        if (is_array($value)) {
            $primitive = array('typeName' => $field, 'multiple' => true, 'value' => $value, 'typeClass' => 'controlledVocabulary');
        } else {
            $primitive = array('typeName' => $field, 'multiple' => false, 'value' => $value, 'typeClass' => 'controlledVocabulary');
        }
        return $primitive;
    }

    function compound($field, $value, $subfield = '')
    {
        $dt = array();
        if (strlen($subfield) > 0) {
            $dt[$subfield] = $value[0];
            $dt = array($dt);
        } else {
            $dt = $value;
        }


        $compound = array('typeName' => $field, 'multiple' => true, 'value' => $dt, 'typeClass' => 'compound');

        //				echo '<pre>';
        //print_r($compound);
        //exit;

        return $compound;
    }

    function date($dt)
    {
        $dt = sonumero($dt);
        $dt = substr($dt, 0, 4) . '-' . substr($dt, 4, 2) . '-' . substr($dt, 6, 2);
        return $dt;
    }

    function primitive($field, $value)
    {
        $primitive = array('typeName' => $field, 'multiple' => false, 'value' => $value, 'typeClass' => 'primitive');
        return $primitive;
    }    
}
