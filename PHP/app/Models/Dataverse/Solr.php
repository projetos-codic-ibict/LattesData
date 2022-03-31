<?php

namespace App\Models\Dataverse;

use CodeIgniter\Model;

class Solr extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'solrs';
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

	function index($d1, $d2, $d3)
	{
		$sx = $d1;
		switch ($d1) {
			case 'schema':
				$sx = $this->readSchema($d2, $d3);
				break;

			case 'solrDV':
				$sx = $this->readDVSchema($d2, $d3);
				break;

			case 'schema_export':
				$sx = $this->updateSchema($d2, $d3);
				break;
			default:
				$menu[PATH . MODULE . 'dataverse/solr/schema'] = 'dataverse.Solr.Schema.Read';
				$menu[PATH . MODULE . 'dataverse/solr/schema_export'] = 'dataverse.Solr.Schema.Export';
				$menu[PATH . MODULE . 'dataverse/solr/solrDV'] = 'dataverse.SolrDV';
				$menu[PATH . MODULE . 'dataverse/solr'] = 'dataverse.Solr';
				$sx .= menu($menu);
		}
		return $sx;
	}

	function readDVSchema()
	{
		$dir = '/usr/local/solr/';
		$d = scandir($dir);
		pre($d);
	}

	function readSchema()
	{
		$Dataverse = new \App\Models\Dataverse\index();
		//$url = $Dataverse->server();
		$url = 'http://localhost:8080';
		$api = $url . '/api/admin/index/solr/schema';


		dircheck('../.tmp/');
		$file = '../.tmp/schema_dv.xml';

		$orig = file_get_contents($api);
		file_put_contents($file, $orig);
		$sx = 'Read schema from ' . $api . '<br>';

		$sx .= '<hr><pre>' . troca($orig, '<', '&lt;') . '</pre>';
		return $sx;
	}

	function updateSchema()
	{
		$dir = '../.tmp/';
		$file = $dir . 'schema_dv.xml';

		if (file_exists($file)) {
			echo "OK";
		} else {
			echo "ERRO";
			exit;
		}

		$orig = file_get_contents($file);
		//$txt = troca($txt,'<','&lt;');
		//$orig = explode(chr(10),$orig);
		$orig_field = substr($orig, 0, strpos($orig, '---'));
		$orig_copy = substr($orig, strpos($orig, '---') + 5, strlen($orig_field));

		/******************************************************************** SOLR SCHEMA *****/
		$file = $dir . 'schema.xml';
		$solr = file_get_contents($file);

		/* SUBS */
		$txa = '<!-- Dataverse copyField from http://localhost:8080/api/admin/index/solr/schema -->';
		$txb = '<!-- End: Dataverse-specific -->';

		$solr_start = substr($solr, 0, strpos($solr, $txa) + strlen($txa)) . chr(10);
		$solr_end = substr($solr, strpos($solr, $txb), strlen($solr));

		/******************************* NEW SOLR FILE */
		$solr = $solr_start . $orig_copy . $solr_end;

		/*********************************************************** SOLR DATAVERSE SCHEMA *****/
		$file = $dir . 'schema_dv_mdb_fields.xml';
		$dv = '<fields>' . chr(10);
		$dv .= $orig_field;
		$dv .= '</fields>';

		/*********************************************************** RESULT ********************/
		dircheck($dir . 'solr');
		$file = $dir . 'solr\\schema.xml';
		file_put_contents($file, $solr);
		$file = $dir . 'solr\\schema_dv_mdb_fields.xml';
		file_put_contents($file, $dv);
		$sx = 'Exported';
		return $sx;
	}
}
