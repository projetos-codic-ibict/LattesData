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

	function management($d1 = '', $d2 = '')
	{
		echo "===" . $d1;
	}

	function CreateDatasets($dd = '', $dataset = '', $parent)
	{
		$sx = h(lang('dataverse.carlos_chagas_cnpq'));
		$DataverseAPI = new \App\Models\Dataverse\API();

		$url = $this->url . 'api/dataverses/' . $parent . '/datasets';
		$id = $dd['id'];

		$json = json_encode($dataset, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
		$file = '.tmp/datasets/dataset_' . $id . '.json';
		file_put_contents($file, $json);

		$dd['AUTH'] = true;
		$dd['POST'] = true;
		$dd['FILE'] = $file;
		$dd['api'] = 'api/dataverses/' . $parent . '/datasets';
		$dd['url'] = $_ENV['DATAVERSE_URL'];
		$dd['apikey'] = $_ENV['DATAVERSE_APIKEY'];


		$rst = $DataverseAPI->curlExec($dd);
		$rsp = json_decode($rst, true);

		if (isset($rsp['status'])) {
			//$email = $dd['user']['email'];
			//$nome = $dd['user']['firstName'].' '.$dataset['user']['lastName'];
			if (isset($rsp['data']['persistentId'])) {
				$DOI = $rsp['data']['persistentId'];
				//$sx .= 'Prezado(a) '.$nome;
				//$sx .= '<p>Foram enviadas instruções para o e-mail '.$$email.'</p>';
				$url = getenv("DATAVERSE_URL") . '/dataset.xhtml?persistentId=' . $DOI . '&version=DRAFT';
				$sx .= 'Link para acesso <a href="' . $url . '"><tt>' . $url . '</tt></a>';
				echo '<pre>';
				print_r($rsp);
			} else {
				pre($rsp);
				$sx .= "ERRO: " . $rsp;
			}
			echo $sx;
			return $sx;
		} else {
			$sx = 'ERRO: FALHA NA CONEXÃO COM O DATAVERSE';
			return $sx;
		}
		if (!isset($rsp['status'])) {
			return "";
		}
		$sx = bsmessage(h($rsp['status']) . $rsp['message'], 1);
		return $sx;
		exit;

		$sta = $rsp['status'];
		return $sx;
	}
}