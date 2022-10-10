<?php

namespace App\Models\Guide;

use __PHP_Incomplete_Class;
use CodeIgniter\Model;

class Index extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'guide';
	protected $primaryKey           = 'id_g';
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

	function index($d1,$d2,$d3,$d4)
		{
			switch($d1)
				{
					case 'section':
						$GuiaSection = new \App\Models\Guide\Sections();
						$sx = $GuiaSection->Index($d2,$d3,$d4);
						break;
					default:
						$sx = h('Guide',1);
						$sx .= $this->menu();
						$sx = bs(bsc($sx,12));
						$sx .= $d1.' '.$d2.' '.$d3;
						break;
				}
			return $sx;
		}

	function menu()
		{
			$menu = array();
			$menu['#guide.InstallPreparation'] = 'Install Preparation';
			$menu[PATH. MODULE.'guide/section/'] = lang('guide.sections');
			return menu($menu);
		}
}
