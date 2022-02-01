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

	function cachedAPI($id)
	{
		dircheck('.tmp');
		dircheck('.tmp/LattesData');
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

	function Process($id = '20113023806')
	{
		$sx = '';
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
				$Dataset = new \App\Models\Dataverse\Datasets();
				//$sx .= $this->modPQ($dt,$id);
				$dd = $this->modPQ($dt, $id);

				/* ETAPAS */
				$sx .= $Dataset->CreateDatasets($dd);
				/* ENVIA e-MAIL */
				$msg = 'Dataset processado ' . $id;
				$sx .= bsmessage($msg, 1);
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
