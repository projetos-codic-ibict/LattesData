<?php

namespace App\Models\Lattes;

use CodeIgniter\Model;

class LattesData extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'lattesdatas';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [];

	// Dates
	protected $useTimestamps        = false;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'created_at';
	protected $updatedField         = 'updated_at';
	protected $deletedField         = 'deleted_at';

	// Validation
	protected $validationRules      = [];
	protected $validationMessages   = [];
	protected $skipValidation       = false;
	protected $cleanValidationRules = true;

	// Callbacks
	protected $allowCallbacks       = true;
	protected $beforeInsert         = [];
	protected $afterInsert          = [];
	protected $beforeUpdate         = [];
	protected $afterUpdate          = [];
	protected $beforeFind           = [];
	protected $afterFind            = [];
	protected $beforeDelete         = [];
	protected $afterDelete          = [];
	var $FileDayValid = 3;

	function API_getFileCnpq($id)
	{
		/* https://codeigniter4.github.io/userguide/libraries/curlrequest.html#config-for-curlrequest */
		$token = getenv("token_lattes");		
		if ($token == '') { echo "Variável <b>token_lattes</b> não definida no .env"; exit;}
		$url = "https://cnpqapi-fomento.cnpq.br/v1/lattesdata/processos/" . $id;

		/********************************************************* CURL */
		$client = \Config\Services::curlrequest();
		$ssl = getenv('CURL_SSL');
		$response = $client->request('GET', $url, [
			'headers' => [
				'auth-token' => $token
			], 
			'verify' => $ssl,
			'timeout' => 10,
			'http_errors' => false
		]);
		$body = (string)$response->getBody();

		/************************************ SAVE TO FILE */
		if (strlen($body) > 0)
			{
				$file = '.tmp/LattesData/' . $id . '.json';		
				file_put_contents($file,$body);
			} else {
				$file = '';
			}
		return $file;
	}

	function cachedAPI($id='')
	{		
		dircheck('.tmp');
		dircheck('.tmp/LattesData');
		
		$file = '.tmp/LattesData/' . $id . '.json';

		$check = true;
		if (ENVIRONMENT == 'development') { $check = false; };

		if (!file_exists($file)) {
			$file = '../../Datasets/processos_pq1a/' . $id . '.json';
			if (!file_exists($file)) {
				$file = '../../Datasets/processos_incts/' . $id . '.json';
				if (!file_exists($file)) {
					$file = '';
				}
			}
		}

		/******************** Verificar Validade do arquivo */
		if (($file != '') and ($check)) {
			$dt = filemtime($file);
			$date1 = date_create(date("Y-m-d"));
			$date2 = date_create(date("Y-m-d", $dt));
			$diff = date_diff($date1, $date2);
			$dias = $diff->format("%a");

			if ($dias > $this->FileDayValid) {
				//$file = '';
			}
		}
		return $file;
	}

	function dv($p='')
		{
			$nr = substr($p,4,6);
			$ye = substr($p,0,4);
			$dv = substr($p,10,1);

			/***************************************************************************
			 * Ex: 309985/2013-7
			 * nr   | 3 | 0 | 9 | 9 | 8 | 5 | 2 | 0 | 1 | 3 |
			 * mult | 9 | 8 | 7 | 6 | 5 | 4 | 0 | 0 | 3 | 2 |
			 * soma |27 | 0 |63 |54 |40 |20 | 0 | 0 | 3 | 6 | => Soma: 213
			 * 
			 * DV = $soma % 11 => 4
			 * DV = 11 - $soma - 11 (7)
			 * Se DV = 10 ou DV == 1 => DV = 0
			 ***************************************************************************/

			$m = array(9,8,7,6,5,4,0,0,3,2);

			/**************** Campos inválidos */
			if (strlen($p) != 11) {
				echo '<br>ERRO:'.$p;
				return false;
			} 

			/*************** Prepara Número */
			$pp = $nr.$ye;
			$sum = 0;
			for ($r=0;$r < strlen($pp);$r++)
				{
					$sum += round($pp[$r]) * $m[$r];
				}
			$DV = $sum % 11;
			$DV = 11 - $DV;
			if ($DV > 9) {
				$DV = 0;
			}
			if ($dv == $DV)
				{
					return true;
				} else {
					return false;
				}
		}

    function padroniza_processo($p='')
        {
            ///ex: CNPq processo 573710/2008-2
			//174760/2008-2
            //padronizado: 20085737102
			$dig = array(9,8,7,6,5,4,0,0,3,2);
            if (strpos($p,'/') > 0)
                {
                    $p = substr($p,strpos($p,'/')+1,4).
                         substr($p,0,6).
                         substr($p,strlen($p)-1,1);
                }

            /* Valida anos */
            $year = round(substr($p,0,4));
            $p = sonumero($p);
            $erro = 0;
            if (($year < 1980) and ($year > date("Y")))
                {
                    $erro = 1;
                }
			if (!$this->dv($p))
				{
					$erro = 2;
				}
            return array($p,$erro);
        }

	function getUser($dt)
		{
			$du = $dt['identificadoresPessoa'];
            if (!isset($du['nomePessoa'])) 
                {
                    $nome = 'sem nome da silva';
                } else {
                    $nome = $du['nomePessoa'];
                }
            $nomep = nbr_author($nome,1);
     
            $firstname = mb_strtolower(substr($nomep,strpos($nomep,',')+1,strlen($nomep)));
            $lastname = mb_strtolower(substr($nomep,0,strpos($nomep,',')));
            $firstname = nbr_author($firstname,7);
            $lastname = nbr_author($lastname,7);

            $email = $dt['emailContato'];

            /***************** AFILIAÇÃO */
            $aff = (array)$dt['instituicoes'];
            if (isset($aff[0]))
                {
                    $affn = (array)$aff[0];
                    $sigla = $affn['siglaMacro'];
                    $inst = $affn['nomeMacro'];
                    if ($inst == '')
                        {
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
            for ($r=0;$r < count($aff);$r++)
                {
                    $affn = (array)$aff[$r];
                    $idp_type = $affn['tipo'];
                    $idp_value = $affn['identificador'];
                    $ids[$idp_type] = $idp_value;
                }

            $dv['firstName'] = $firstname;
            $dv['lastName'] = $lastname;
            $dv['userName'] = troca($email,'@','-');
            $dv['affiliation'] = $inst;
            $dv['position'] = 'Research';
            $dv['email'] = $email;

			return $dv;
		}

	function getChamada($dt,$user)
		{
			$chamada = (array)$dt['chamada'];			
			$DV['alias'] = troca($chamada['sigla'],' ','');
			$DV['name'] = $chamada['nome'];

			$contact[0]['contactEmail'] = 'lattesdata@cnpq.br';

			$DV['dataverseContacts'] = $contact;
			$DV['affiliation'] = 'CNPq';
			$DV['description'] = $chamada['nome'].' - '.$chamada['sigla'];
			$DV['dataverseType'] = "LABORATORY";

			return $DV;			
		}

	function getProjeto($dt,$user)
		{
			
			$projeto = (array)$dt['projeto'];
			$nome = $projeto['titulo'];
			$desciption = $projeto['resumo'];
			$alias = 'CNPq'.trim($dt['numeroProcesso']);
			$pre1 = substr($alias,0,strpos($alias,'/'));
			$pre2 = substr($alias,strpos($alias,'/')+1,4);
			$alias = $pre2.$pre1;

			$DV['alias'] = $alias;
			$DV['name'] = $nome;

			$contact[0]['contactEmail'] = $user['email'];

			$DV['dataverseContacts'] = $contact;
			$DV['affiliation'] = $user['affiliation'];
			$DV['description'] = $desciption;
			$DV['dataverseType'] = "LABORATORY";

			return $DV;			
		}	

	function getDataset($dt,$user)
		{
			$DV = array();

			$projeto = (array)$dt['projeto'];

			/* Licence */
			$DV['datasetVersion']['license']['name'] = 'CC BY';
			$DV['datasetVersion']['license']['uri'] = 'http://creativecommons.org/licenses/by/4.0/';

			$DV['protocol'] = 'doi';
			$DV['authority'] = getenv('DOI');
			$DV['identifier'] = troca($dt['numeroProcesso'],'/','');
			$DV['identifier'] = substr($DV['identifier'],0,strpos($DV['identifier'],'-'));		

			/* Citation */
			$fld = array();

			/*************** Title */
			$title = $projeto['titulo'];
			$fields = array('typeName'=>'title','multiple'=>'false','value'=>$title,'typeClass'=>'primitive');
			array_push($fld,$fields);

			/*************** productionDate */
			$productionDate = $dt['dataInicioVigencia'];
			$productionDate = substr($productionDate,6,4).'-'.substr($productionDate,3,2).'-'.substr($productionDate,0,2);
			$fields = array('typeName'=>'productionDate','multiple'=>'false','value'=>$productionDate,'typeClass'=>'primitive');
			array_push($fld,$fields);

			/*************** dsDescription */
			$value = $projeto['resumo'];
			$fields = array('typeName'=>'dsDescription','multiple'=>'false','value'=>$value,'typeClass'=>'primitive');
			array_push($fld,$fields);

			/********************* subject */
			$key = $dt['palavrasChave'];
			$key = troca($key,';',',');
			$key = troca($key,'.',',');
			$fields = array('typeName'=>'subject','multiple'=>'false','value'=>$value,'typeClass'=>'primitive');
			array_push($fld,$fields);
						
			$DV['metadataBlocks']['citation']['fields'] = $fld;
			return $DV;

		}	

	function recoverPQ($dt,$id)
		{
			$sx = '';

				$Users = new \App\Models\Dataverse\Users();
				$Dataset = new \App\Models\Dataverse\Datasets();
				$Dataverse = new \App\Models\Dataverse\Dataverse();
				//$sx .= $this->modPQ($dt,$id);
				$dd = $this->modPQ($dt, $id);

				/****************************************** USER */
				$user = $this->getUser($dt);	

				/***************************** CHAMADA DATAVERSE */
				$chamada = $this->getChamada($dt,$user);
				$Dataverse->CreateDataverse($chamada,'beneficiarios');
				$parent = $chamada['alias'];				

				/***************************** PROJETO DATAVERSE */
				$projeto = $this->getProjeto($dt,$user);			
				$Dataverse->CreateDataverse($projeto,$parent);

				/******************************* PROJETO DATASET */
				$dataset = $this->getDataset($dt,$user);
				$Dataverse->CreateDataverse($projeto,$parent);

				$sx .= $Dataset->CreateDatasets($dd);
				echo $sx;
				
				/* ENVIA e-MAIL */
				$msg = 'Dataset processado ' . $id;
				$sx .= bsmessage($msg, 1);
			return $sx;
		}

	function Process($dt = array('20113023806',0))
	{
		$sx = '';
		$id = $dt[0];
		$file = $this->cachedAPI($id);

		/************************************ GET API CNPq */
		if ($file == '') 
		{
			$sx = bsmessage('Coletando arquivo CNPQ ' . $id);
			$file = $this->API_getFileCnpq($id);
		} else {
			$sx = bsmessage('Coletando arquivo do Cache ' . $id);
		}

		/*********************** read metadata */
		$dt = file_get_contents($file);
		$dt = (array)json_decode($dt);

		/********************************************** MODALIDADE */
		$MOD = 'X';
		if (isset($dt['modalidade'])) {
			$MOD = (array)$dt['modalidade'];
			$MOD = (string)$MOD['codigo'];
		}

		switch ($MOD) {
			case 'PQ':
				$this->recoverPQ($dt,$id);
				break;
			case 'AI':
				echo "INCT";
				exit;
				$sx .= $this->modAI($dt, $id);
				break;

			case 'APQ':
				$sx .= h("INCT-APQ",3);
				//$sx .= $this->modAI($dt, $id);
				break;
			case 'X':
				$sx .= h('Erro Lattes ID',1);
				$sx .= '<p>'.file_get_contents($file).'</p>';
				break;
			default:
				$sx .= 'OPS ' . $MOD . ' not implemented';
				return $sx;
		}
		return $sx;
	}

	function filename($process = '')
	{
		$file = ".tmp/datasets/dataset_" . $process . '.json';
		return $file;
	}
	function modPQ($dt, $id)
	{
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
		$dv['datasetVersion'] = array();
		$dv['datasetVersion']['termsOfUse'] = 'CC0 Waiver';
		$dv['datasetVersion']['license'] = 'CC0';

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

		/* Metada Block */
		$dv['datasetVersion']['metadataBlocks'] = $mb;
		$dv['id'] = $id;
		if ((!isset($_ENV['DATAVERSE_URL'])) or (!isset($_ENV['DATAVERSE_APIKEY']))) {
			echo "ERRO: defina a variavel DATAVERSE_URL e DATAVERSE_APIKEY no .env";
			exit;
		}
		$dv['url'] = $_ENV['DATAVERSE_URL'];
		$dv['apikey'] = $_ENV['DATAVERSE_APIKEY'];
		$dv['api'] = 'api/dataverses/produtividadePQ1A/datasets';

		return $dv;

		//$json = json_encode($dv,JSON_PRETTY_PRINT);
		//$file = $this->filename($id);
		//file_put_contents($file,$json);
	}

	function primitive($field, $value)
	{
		$primitive = array('typeName' => $field, 'multiple' => false, 'value' => $value, 'typeClass' => 'primitive');
		return $primitive;
	}
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
}
