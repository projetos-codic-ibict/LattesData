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
		$url = getenv("url_lattes");	
		if ($token == '') { echo "Variável <b>token_lattes</b> não definida no .env"; exit;}
		if ($url == '') { echo "Variável <b>url_lattes</b> não definida no .env"; exit;}
		$url .= $id;

		/********************************************************* CURL */
		$client = \Config\Services::curlrequest();
		$ssl = getenv("CURL_SSL");
		
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

	function cachedAPI($dt='')
	{
		
		dircheck('.tmp');
		dircheck('.tmp/LattesData');
		$id = $dt[0];
		
		$file = '.tmp/LattesData/' . $id . '.json';
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
		if ($file != '') {
			$dt = filemtime($file);
			$date1 = date_create(date("Y-m-d"));
			$date2 = date_create(date("Y-m-d", $dt));
			$diff = date_diff($date1, $date2);
			$dias = $diff->format("%a");

			if ($dias > $this->FileDayValid) {
				$file = '';
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

	function Process($dt = array('20113023806',0))
	{
		$sx = '';
		$DataverseUser = new \App\Models\Dataverse\Users();

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

		$user = $DataverseUser->createUser($dt);

		switch ($MOD) {
			case 'PQ':
				$Dataset = new \App\Models\Dataverse\Datasets();
				$Dataverse = new \App\Models\Dataverse\Dataverse();
				$dd = $this->modPQ($dt, $id);

				/* ETAPAS */

				/* VER DATAVERSE I */
				$dv = array();
				
				$dd['name'] = 'Beneficiários do CNPq';
				$dd['alias'] = 'bcnpq';
				$dd['affiliation'] = 'CNPq';
				$dd['description'] = 'Datasets do projetos beneficiados com recursos do CNPq';
				$dd['dataverseContacts'] = array();
				array_push($dd['dataverseContacts'], array('contactEmail' => 'cnpq@cnpq.br'));
				array_push($dd['dataverseContacts'], array('contactEmail' => 'lattesdata@cnpq.br'));	
				$dd['dataverseType'] = 'LABORATORY';

				//$dd['id'] = substr($);
				$sx .= $Dataverse->CreateDataverse($dd);

				/* VER DATAVERSE II */

				/* VER DATASET */
				//$sx .= $Dataset->CreateDatasets($dd);

				/* ENVIA e-MAIL */
				$msg = 'Dataset processado ' . $id;
				$sx .= bsmessage($msg, 1);
				break;

			case 'AI':
				$sx .= $this->modAI($dt, $id);
				break;

			case 'APQ':
				$sx .= h("INCT-APQ",3);
				//$sx .= $this->modAI($dt, $id);
				break;
			case 'X':
				$sx .= h('Erro',1);
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

	function metadados($dt)
		{
			$dv = array();
			$dt = (array)$dt;
			$simple = array(
						'numeroProcesso','dataInicioVigencia','dataTerminoVigencia',
						'emailContato','nomePessoa','identificadoresPessoa'
						);
			$dataverse_field = array(
				'processo','di','df',
				'email','nomePessoa','identificadoresPessoa',
				);
			for ($r=0;$r < count($simple);$r++)
				{
					$value = '';
					$field = $simple[$r];
					$field_dv = $dataverse_field[$r];
					if (isset($field)) { $value = $dt[$simple[$r]]; }
					if (is_array($value))
						{
							$dv[$field_dv] = (array)$value;
						} else {
							$dv[$field_dv] = $value;
						}					
				}

				/************************************** PROJETO */
				$proj = (array)$dt['projeto'];
				$titulo = (string)$proj['titulo'];
				$titulo = mb_strtolower($titulo);
				$titulo = nbr_author($titulo, 7);
				$dv['title'] = $titulo;

				$dv['goal'] = $proj['objetivo'];
				$txt = $proj['resumo'];
				$txt = troca($txt,chr(13),'_x_');
				$txt = troca($txt,chr(10),'_x_');
				$txt = troca($txt,'_x__x_','_x_');
				$txt = troca($txt,'_x__x_','_x_');
				$txt = troca($txt,'_x_',chr(13));
				$dv['abstract'] = $txt;

				/************************************** CHAMADA */
				$chamada = (array)$dt['chamada'];
				$titulo = (string)$chamada['nome'];
				$dv['chamada'] = $titulo;
				$sigla =  mb_strtolower(troca($chamada['sigla'],' ','_'));
				$dv['chamada_sigla'] = $sigla;
				
				$dv['chamada_parent'] = (string)$chamada['nomeParent'];
				$sigla =  mb_strtolower(troca($chamada['siglaParent'],' ','_'));
				$dv['chamada_sigla_parent'] = $sigla;	

				/************************************** Modalidade */
				$modalidade = (array)$dt['modalidade'];
				$dv['modalidade'] = (string)$modalidade['nome'];
				$dv['modalidade_codigo'] = (string)$modalidade['codigo'];

				/************************************** instituicoes */
				$instituicoes = array();
				$inst = (array)$dt['instituicoes'];
				for ($r=0;$r < count($inst);$r++)
					{
						$insts = (array)$inst[$r];
						$cod = $insts['codigo'];
						$tipo = $insts['tipo'];
						$sigla = $insts['sigla'];
						$nome = $insts['nome'];
						$siglaMacro = $insts['siglaMacro'];
						$nomeMacro = $insts['nomeMacro'];
						array_push($instituicoes,array($cod,$tipo,$sigla,$nome,$siglaMacro,$nomeMacro));
					}
				$dv['instituicoes'] = $instituicoes;


				/******************************************** KEYWORD***/
				$kys = array();
				$key = (string)$dt['palavrasChave'];
				$key = troca($key, ', ', ';');
				$key = troca($key, '. ', ';');
				$key = explode(';', $key);
				foreach ($key as $word) {
					$word = mb_strtolower(trim($word));
					$word = nbr_author($word, 7);
					array_push($kys, $word);
				}
				$dv['keywords'] = $kys;

				return $dv;
		}

	function modAI($dt, $id)
	{
		$sx = '';
		$Dataverse = new \App\Models\Dataverse\Dataverse();
		$dv = $this->metadados($dt);

		$dv['datasetVersion'] = array();
		$dv['datasetVersion']['termsOfUse'] = 'CC0 Waiver';
		$dv['datasetVersion']['license'] = 'CC0';

		if ((!isset($_ENV['DATAVERSE_URL'])) or (!isset($_ENV['DATAVERSE_APIKEY']))) {
			echo "ERRO: defina a variavel DATAVERSE_URL e DATAVERSE_APIKEY no .env";
			exit;
		}

		/* Davaserve */
		$processo = $dv['processo'];

		$modalidade_ano = substr($processo,7,4);
		$modalidade = $dv['modalidade'];
		$modadidade_cod = $dv['modalidade_codigo'];
		$modalidade_nome =$dv['modalidade'];
		$dv_name = 'Chamada '.$modalidade_nome.' - '.$modadidade_cod.' - '.$modalidade_ano;
		$dv_name = $dv['chamada'];

		$dv_id = strtolower($modadidade_cod.$modalidade_ano);
		$dv_id = $dv['chamada_sigla'];

		/**************************************************************** ROOT */
		$PARENT = 'lattesdata';
		$name = 'Beneficiários CNPq';
		$alias = 'bcnpq';
		$contact = array('contactEmail' => 'cnpq@cnpq.br');
		$affiliation = 'CNPq';
		$descript ='Beneficiários CNPq';
		$dataverseType = 'LABORATORY';
		$sx .= $Dataverse->CreateDataverse($PARENT,$name,$alias,$contact,$affiliation,$descript,$dataverseType);
		
		/**************************************************************** CHAMADA */
		$PARENT = 'bcnpq';
		$name = $dv_name;
		$alias = $dv_id;
		$contact = array(array('contactEmail' => 'cnpq@cnpq.br'));
		$affiliation = 'CNPq';
		$descript = $dv_name;
		$dataverseType = 'LABORATORY';
		$sx .= $Dataverse->CreateDataverse($PARENT,$name,$alias,$contact,$affiliation,$descript,$dataverseType);

		/**************************************************** Collections */
		$processo_id = troca($processo,'/','_');
		$processo_id = substr($processo_id,0,strpos($processo_id,'-'));
		$processo_id = 'cnpq_'.$processo_id;
		
		$PARENT = $dv_id;
		$name = $dv['title'];
		$alias = $processo_id;
		$contact = array(array('contactName' => $dv['nomePessoa'], 'contactEmail' => $dv['email']));
		$affiliation = 'CNPq';
		$descript =$dv['abstract'];
		$dataverseType = 'LABORATORY';
		$sx .= $Dataverse->CreateDataverse($PARENT,$name,$alias,$contact,$affiliation,$descript,$dataverseType);
		
		return $sx;
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
		$dv['api'] = 'api/dataverses/pq2014/datasets';

		/* Davaserve */
		$processo = $dt['numeroProcesso'];

		$modalidade_ano = substr($processo,7,4);
		$modalidade = (array)$dt['modalidade'];
		$modadidade_cod = $modalidade['codigo'];
		$modalidade_nome = $modalidade['nome'];
		$dv_name = 'Chamada '.$modalidade_nome.' - '.$modadidade_cod.' - '.$modalidade_ano;
		$dv_id = strtolower($modadidade_cod.$modalidade_ano);
		echo h($dv_name);
		echo h($dv_id);

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
