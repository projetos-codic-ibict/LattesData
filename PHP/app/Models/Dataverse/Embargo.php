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

			case 'remove_embargo':
				$sx .= $this->remove_embargo($d2, $d3);
				break;

			default:
				$menu[PATH . MODULE . 'dataverse/embargo/enable'] = 'dataverse.Embargo.enable';
				$menu[PATH . MODULE . 'dataverse/embargo/remove_embargo'] = 'dataverse.Embargo.remove_embargo';
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

	function remove_embargo()
	{
		$Dataverse = new \App\Models\Dataverse\index();
		$url = $Dataverse->server();
		$sx = 'curl -X PUT -d 24 ' . $url . '/api/admin/settings/:MaxEmbargoDurationInMonths';
		$sx .= 'export API_TOKEN=xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx</br>';
		$sx .= 'export SERVER_URL=https: //demo.dataverse.org</br>';
		$sx .= 'export PERSISTENT_IDENTIFIER=doi:10.5072/FK2/7U7YBV</br>';
		$sx .= 'export JSON=\'{"fileIds":[300,301]}</br>\'';
		$sx .= '</br>';
		$sx .= 'curl -H "X-Dataverse-key: $API_TOKEN" -H "Content-Type:application/json" "$SERVER_URL/api/datasets/:persistentId/files/actions/:unset-embargo?persistentId=$PERSISTENT_IDENTIFIER" -d "$JSON"</br>';
		return $sx;
	}
}
