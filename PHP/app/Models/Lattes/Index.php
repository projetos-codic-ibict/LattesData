<?php

namespace App\Models\Lattes;

use CodeIgniter\Model;

class Index extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'indices';
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

	function index($d1,$id)
		{
			switch($d1)
				{
					case 'findid':
						$sx = $this->findId($d1,$id);
					break;
				}
				return $sx;
		}
	function findId($txt,$id)
		{
				$LattesId = new \App\Models\Lattes\LattesId();
				$dt = $LattesId->LattesFindID($txt);

				if (count($dt))
					{
						if (count($dt) == 1)
							{
								print_r($dt);
								exit;
							}
					}
		}
}
