<?php

namespace App\Models\Dataverse;

use CodeIgniter\Model;

class DOI extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'dois';
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
			$sx = h('DOI',1);
			if (strlen($d1) > 0)
				{
					$sx .= h('dataverse.doi_'.$d1,4);
				}
			
			$cmd = '';
			switch($d1)
				{
					case 'doi_FilePID':
						$cmd .= 'echo "Atribui DOI para cada dataset, sem gerar para arquivos"'.cr();
						$cmd .= 'curl -X PUT -d \'false\' http://localhost:8080/api/admin/settings/:FilePIDsEnabled'.cr();
						$cmd .= 'echo "Atribui DOI para cada arquivo"'.cr();
						$cmd .= 'curl -X PUT -d \'true\' http://localhost:8080/api/admin/settings/:FilePIDsEnabled'.cr();
						break;
					case 'shoulder':
						$cmd .= 'export $PREFIX=PRE'.cr();
						$cmd .= 'curl -X PUT -d "$PREFIX/" http://localhost:8080/api/admin/settings/:Shoulder';
						break;
					case 'protocol':
						$cmd .= 'curl -X PUT -d doi http://localhost:8080/api/admin/settings/:Protocol';
						break;
					case 'authority':
						$cmd .= 'curl -X PUT -d 10.80102 http://localhost:8080/api/admin/settings/:Authority';
						break;						
					case 'fake':
						$cmd = 'curl http://localhost:8080/api/admin/settings/:DoiProvider -X PUT -d FAKE_DOI_PROVIDER=true';						
						break;
					default:				
					$menu[PATH.MODULE.'dataverse/doi/fake'] = 'dataverse.doi_fake';
					$menu[PATH.MODULE.'dataverse/doi/shoulder'] = 'dataverse.doi_shoulder';
					$menu[PATH.MODULE.'dataverse/doi/doi_FilePID'] = 'dataverse.doi_dataset';
					$menu[PATH.MODULE.'dataverse/doi/protocol'] = 'dataverse.protocol';
					$menu[PATH.MODULE.'dataverse/doi/authority'] = 'dataverse.authority';
					
					
					$sx .= menu($menu);
					break;
				}

			//curl http://localhost:8080/api/admin/settings/:DoiProvider -X PUT -d FAKE_DOI_PROVIDER=true			
			$sx .= '<pre>'.troca($cmd,chr(10),'<br>').'</pre>';
			return $sx;
		}
}
