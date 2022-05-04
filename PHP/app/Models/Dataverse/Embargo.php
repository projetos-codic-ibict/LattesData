<?php

namespace App\Models\Dataverse;

use CodeIgniter\Model;

class Embargo extends Model
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
		$sx = breadcrumbs();
		switch ($d1) {
			case 'enable':
				$sx .= $this->enable($d2, $d3);
				break;

			default:
				$menu[PATH . MODULE . 'dataverse/embargo/enable'] = 'dataverse.Embargo.enable';
				$sx .= menu($menu);
		}
		return $sx;
	}

	function enable()
	{
		$Dataverse = new \App\Models\Dataverse\index();
		$url = $Dataverse->server();
		$sx = 'curl -X PUT -d 24 '.$url.'/api/admin/settings/:MaxEmbargoDurationInMonths';
		$sx .= '<br>';
		$sx .= '<b>24</b> - ';
		return $sx;
	}
}
