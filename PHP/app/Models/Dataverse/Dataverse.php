<?php

namespace App\Models\Dataverse;

use __PHP_Incomplete_Class;
use CodeIgniter\Model;

class Dataverse extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'dataverses';
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

	//var $url = 'https://vitrinedadosabertos.inep.rnp.br/';
	var $url = '';
	var $apiKey = '';

	function __construct()
	{
		$this->apiKey = getenv('DATAVERSE_APIKEY');
		$this->url = getenv('DATAVERSE_URL');
	}

	function CreateDataverse($dd,$PARENT='')
		{
			$API = new \App\Models\Dataverse\API();
			$file = '.tmp/dataverse/dataverse-'.$dd['alias'].'.json';
			file_put_contents($file, json_encode($dd, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
			$url = getenv("DATAVERSE_URL");

			
			$dd['AUTH'] = true;
			$dd['POST'] = true;
			$dd['FILE'] = $file;
			$dd['url'] = $url;
			$dd['api'] = 'api/dataverses/'.$PARENT;
			$dd['apikey'] = $this->apiKey;
			$dd['FILE'] = $file;

			$rsp = $API->curlExec($dd);
			exit;
			/******************************** Retorno */
			$msg = (string)$rsp['json'];
			$msg = (array)json_decode($msg);

			if (!isset($msg['status']))
				{
					return lang('Response empty');
				}
			$sta = trim((string)$msg['status']);
			switch($sta)
				{
					case 'OK':
						$sx = 'OK';
					break;
					
					case 'ERROR':
						$sx = '<pre style="color: red;">'; 
						$sx .= $msg['message'];	
						$sx .= '<br>Dataverse Name: <b>'.$alias.'</b>';
						$sx .= '<br><a href="'.$this->url.'dataverse/'.$PARENT.'" target="_blank">'.$url.'/'.$PARENT.'</a>';
						$sx .= '</pre>';
						echo $sx;
						break;
				}
			return $sx;
		}	


/**********************************************************************
 * TESTED *************************************************************
 ***********************************************************************/
	
	function test()
		{
			$sx = '';
			
			echo $sx;

			if (isset($_GET['process']))
				{
					$id = sonumero($_GET['process']);
					//$sx .= $this->PQ1();
					$file = '../../Datasets/processos_pq1a/'.trim($id).'.json';
					
					echo '<hr>'.$file.'</hr>';				
					if (file_exists($file))
						{							
							echo "OK";
							$txt = file_get_contents($file);
							$txt = json_decode($txt);
							echo '<pre>';
							print_r($txt);
						} else {
							echo " - NOT FOUND";
						}
				}
			return $sx;
		}

	function ViewDataverseCollection($collection='lattesdata')
		{
		$api = 'api/dataverses';
		$url = $this->url . $api . '/'.$collection;

		$op = array();
		$rsp = $this->curl($url,'');

		echo '<pre style="color: blue;">'; print_r($rsp); echo '</pre>';
		exit;
		}

	function ViewDataverseTree($collection='lattesdata')
		{
		$api = 'api/info/metrics/tree/';
		$url = $this->url . $api;

		$rsp = $this->curl($url);

		echo '<pre style="color: blue;">=====>';
		print_r($rsp);
		echo '</pre>';
		exit;
		}


}
