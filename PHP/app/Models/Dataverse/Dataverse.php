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
	var $url = 'http://200.130.0.214:8080/';
	var $apiKey = 'b8fb20a6-15ed-40b1-87e1-a1da20a82c1b';


/**********************************************************************
 * TESTED *************************************************************
 ***********************************************************************/
	function CreateDataverse($root,$name,$alias,$id,$affiliation,$type)
		{
			$dd = array();
			$dd['name'] = 'Bolsistas Produtividade PQ1A';
			$dd['alias'] = 'produtividadePQ1A';
			$dd['dataverseContacts'] = array();
			array_push($dd['dataverseContacts'], array('contactEmail' => 'cnpq@cnpq.br'));
			array_push($dd['dataverseContacts'], array('contactEmail' => 'lattesdata@cnpq.br'));
	
			$dd['affiliation'] = 'CNPq';
			$dd['description'] = 'Projetos dos Bolsistas Produtividade PQ1A';
			$dd['dataverseType'] = 'LABORATORY';
			$dd['id'] = '2018';
			$sx = $this->CreateDataverse($dd);			
			return $sx;
		}		
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

	function CreateDataverse($dd='')	
		{
		$API = new \App\Models\Dataverse\API();
		
		$url = $this->url.'api/dataverses/lattesdata';
		$id = $dd['id'];

		$json = json_encode($dd, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
		$id = strzero(1,8);
		$file = '.tmp/dataverse/dataverse-'.$id.'.json';
		file_put_contents($file, $json);

		$dd['AUTH'] = true;
		$dd['POST'] = true;
		$dd['FILE'] = $file;

		$rsp = $API->curlExec($dd);
		$rsp = json_decode($rsp,true);
		
		$sta = $rsp['status'];
		switch($sta)
			{
				case 'OK':
					$sx = 'OK';
				break;
				case 'ERROR':
					$sx = '<pre style="color: red;">'; 
					$sx .= $rsp['message'];	
					$sx .= '<br>Dataverse Name: <b>'.$dd['alias'].'</b>';
					$sx .= '<br><a href="'.$this->url.'dataverse/'.$dd['alias'].'" target="_blank">'.$url.'/'.$dd['alias'].'</a>';
					$sx .= '</pre>';
					break;
			}
		return $sx;
		}
}
