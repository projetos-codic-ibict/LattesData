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

	function index($d1,$d2,$d3)
		{
			$sx = $d1;
			switch($d1)
				{
					case 'schema':
						$sx = $this->updateSchema($d2,$d3);
						break;
					default:
						$menu[PATH.MODULE.'dataverse/solr/schema'] = 'dataverse.Solr.Schema';
						$menu[PATH.MODULE.'dataverse/solr'] = 'dataverse.Solr';
						$sx .= menu($menu);
				}
			return $sx;			
		}

	function updateSchema()
		{			
			$url = 'http://localhost:8080/api/admin/index/solr/schema';			
			$dv = file_get_contents($url);

			$dir = '../.tmp/';
			dircheck($dir.'solr');
			$file = $dir.'solr/schema_api.xml';
			file_put_contents($file,$dv);
			/********************************************************************************************/
			$dir_solr = '/usr/local/solr/solr-8.8.1/server/solr/collection1/conf/';
			$file1 = 'schema.xml';
			$file2 = 'schema_dv_mdb_fields.xml';

			copy($dir_solr.$file1,$dir.'solr/'.$file1);			
			copy($dir_solr.$file2,$dir.'solr/'.$file2);			

			$orig = file_get_contents($file);

			$orig_field = substr($orig,0,strpos($orig,'---'));
			$orig_copy = substr($orig,strpos($orig,'---')+5,strlen($orig_field));

			/******************************************************************** SOLR SCHEMA *****/
			$solr = file_get_contents($dir_solr.$file1);

			/* SUBS */
			$txa = '<!-- Dataverse copyField from http://localhost:8080/api/admin/index/solr/schema -->';
			$txb = '<!-- End: Dataverse-specific -->';
			
			$solr_start = substr($solr,0,strpos($solr,$txa)+strlen($txa)).chr(10);
			$solr_end = substr($solr,strpos($solr,$txb),strlen($solr));
		
			/******************************* NEW SOLR FILE */
			$solr = $solr_start . $orig_copy . $solr_end;

			/*********************************************************** SOLR DATAVERSE SCHEMA *****/
			$file = $dir_solr.$file2;
			$dv = '<fields>'.chr(10);
			$dv .= $orig_field;
			$dv .= '</fields>';

			/*********************************************************** RESULT ********************/
			dircheck($dir.'solr');
			$file = $dir.'solr\\schema.xml';
			file_put_contents($file,$solr);
			$file = $dir.'solr\\schema_dv_mdb_fields.xml';
			file_put_contents($file,$dv);
			$sx = 'Exported';
			return $sx;
		}
}
