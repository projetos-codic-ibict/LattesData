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
		if (strlen($id) == 11) {
			//$token = '1bff0ead-c76f-371e-8f47-e56d6b0a024c';
			//$url = "https://api.cnpq.br/lattes-data/v1/processos/".$id;
			$token = getenv('token_lattes');
			$url = getenv('url_lattes');

			/*********************************** BRAPCI */
			if (strpos($url,'brapci'))
				{
					/***************** Brapci */
					$BrapciAPI = new \App\Models\Lattes\BrapciAPI();
					jslog('Extrator: LattesExtrator');
					$js = $BrapciAPI->get($id);
					if (strlen($js) > 0)
						{
							$file = $this->temp_file($id);
							file_put_contents($file, $js);
							jslog('Saved: '.$file);
						} else {
							jslog('Erro save file: '.$id);
						}
				} else {
					/***************** CNPQ */
					$LattesExtrator = new \App\Models\Lattes\LattesExtrator();
					jslog('Extrator: Brapci');
				}
			return true;
		} else {
			return false;
		}
	}

	function temp_file($id='')
		{
		dircheck('.tmp');
		dircheck('.tmp/LattesData');
		$file = '.tmp/LattesData/' . $id . '.json';
		return $file;
		}

	function cachedAPI($id = '')
	{
		$file = $this->temp_file($id);

		if (file_exists($file))
			{
			$dt = filemtime($file);
			$date1 = date_create(date("Y-m-d"));
			$date2 = date_create(date("Y-m-d", $dt));
			$diff = date_diff($date1, $date2);
			$dias = $diff->format("%a");

				if ($dias > $this->FileDayValid) {
					return false;
				}
			return true;
			}
		return false;
	}

	function dv($p = '')
	{
		$nr = substr($p, 4, 6);
		$ye = substr($p, 0, 4);
		$dv = substr($p, 10, 1);

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

		$m = array(9, 8, 7, 6, 5, 4, 0, 0, 3, 2);

		/**************** Campos inválidos */
		if (strlen($p) != 11) {
			//echo '<br>ERRO:' . $p;
			return false;
		}

		/*************** Prepara Número */
		$pp = $nr . $ye;
		$sum = 0;
		for ($r = 0; $r < strlen($pp); $r++) {
			$sum += round($pp[$r]) * $m[$r];
		}
		$DV = $sum % 11;
		$DV = 11 - $DV;
		if ($DV > 9) {
			$DV = 0;
		}
		if ($dv == $DV) {
			return true;
		} else {
			return false;
		}
	}

	function padroniza_processo($p = '')
	{
		///ex: CNPq processo 573710/2008-2
		//174760/2008-2
		//padronizado: 20085737102
		$dig = array(9, 8, 7, 6, 5, 4, 0, 0, 3, 2);
		if (strpos($p, '/') > 0) {
			$p = substr($p, strpos($p, '/') + 1, 4) .
				substr($p, 0, 6) .
				substr($p, strlen($p) - 1, 1);
		}

		/* Valida anos */
		$year = round(substr($p, 0, 4));
		$p = sonumero($p);
		$erro = 0;
		if (($year < 1980) and ($year > date("Y"))) {
			$erro = 1;
		}
		if (!$this->dv($p)) {
			$erro = 2;
		}
		return array($p, $erro);
	}	

	function Process($dt = array('20113023806', 0))
	{
		$sx = '';
		$id = $dt[0];
		/******************** Recupera nome do arquivo */
		$cached = $this->cachedAPI($id);

		/************************************ GET API CNPq */
		if (!$cached) {
			jslog('API CNPq ' . $id);
			$file = $this->API_getFileCnpq($id);
		} else {
			jslog('Cache CNPq ' . $id);
		}

		/********************************** Fase de Processamento */
		$file = $this->temp_file($id);
		if (!file_exists($file))
			{
				$sx = 'Erro de importação';
				return $sx;
			}

		/*********************** read metadata */
		jslog('File: '.$file);

		$dt = file_get_contents($file);
		$dt = (array)json_decode($dt);

		/********************************************** MODALIDADE */
		$MOD = 'X';
		if (isset($dt['modalidade'])) {
			$MOD = (array)$dt['modalidade'];
			$MOD = (string)$MOD['codigo'];
		}
		jslog('Project Type ' . $MOD);
		switch ($MOD) {
			case 'PQ':
				$js = new \App\Models\Lattes\LattesDataPQ();
				$sx .= $js->register($dt, $id);
				break;
			case 'AI':
				$js = new \App\Models\Lattes\LattesDataINCT();
				$sx .= $js->register($dt, $id);
				break;
			default:
				$sx .= 'OPS ' . $MOD . ' not implemented - '.$id;
				print_r($dt);
				return $sx;
		}
		return $sx;
	}

	function filename($process = '')
	{
		$file = ".tmp/datasets/dataset_" . $process . '.json';
		return $file;
	}
}
