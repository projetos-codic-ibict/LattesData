<?php

namespace App\Models\Dataverse;

use CodeIgniter\Model;

class Datasets extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'datasets';
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

	function CreateDatasets($dd='',$dataset='',$parent)	
		{
			$sx ='???';
			$DataverseAPI = new \App\Models\Dataverse\API();

			$url = $this->url.'api/dataverses/'.$parent.'/datasets';
			$id = $dd['id'];
	
			$json = json_encode($dataset, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
			$file = '.tmp/datasets/dataset_'.$id.'.json';
			file_put_contents($file, $json);

			$dd['AUTH'] = true;
			$dd['POST'] = true;
			$dd['FILE'] = $file;
			$dv['api'] = 'api/dataverses/'.$parent.'/datasets';

			$rst = $DataverseAPI->curlExec($dd);
			$rsp = json_decode($rst,true);

			if (isset($rsp['status']))
				{
					$sx = $rsp['data']['persistentId'];
					return $sx;
				} 
			else
				{
					$sx = 'ERRO: ???';
					return $sx;
				}
			if (!isset($rsp['status'])) { return ""; }
			$sx = bsmessage(h($rsp['status']).$rsp['message'],1);
			return $sx;
			exit;
			
			$sta = $rsp['status'];			
			return $sx;
			}
}
