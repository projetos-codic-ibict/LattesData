<?php

namespace App\Models\Dataverse;

use CodeIgniter\Model;

class API extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'apis';
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

	//curl -X PUT -d allow http://localhost:8080/api/admin/settings/:BlockedApiPolicy

	function curlExec($dt)
	{
		$rsp = array();
		$rsp['msg'] = '';

		if ((!isset($dt['url'])) or (!isset($dt['api'])) or (!isset($dt['apikey']))) {
			$sx = "Error: Missing URL, API or API Key";
			$rsp['msg'] = $sx;
		} else {
			$url = $dt['url'] . $dt['api'];
			$apiKey = $dt['apikey'];

			/* Comando */
			$cmd = 'curl ';
			/* APIKEY */
			if (isset($dt['AUTH'])) {
				$cmd .= '-H X-Dataverse-key:' . $apiKey . ' ';
			}

			/* POST */
			if (isset($dt['POST'])) {
				$cmd .= '-X POST ' . $url . ' ';
			}

			/* POST */
			if (isset($dt['FILE'])) {
				if (!file_exists($dt['FILE'])) {
					$rsp['msg'] .= bsmessage('File not found - ' . $dt['FILE'], 3);
				}
				//		$cmd .= '-H "Content-Type: application/json" ';
				$cmd .= '--upload-file ' . realpath($dt['FILE']) . ' ';
			}
			echo '<pre>'.$cmd.'</pre>';
			echo '<hr>';
			$txt = shell_exec($cmd);
			return $txt;
		}
		return 'ops - invalid parametes API.php';
	}
}
