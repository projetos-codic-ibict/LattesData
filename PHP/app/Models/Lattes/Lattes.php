<?php

namespace App\Models\Lattes;

use CodeIgniter\Model;

class Lattes extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'lattes';
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

	function link($dt)
		{
			$link1 = '';
			if ($dt['a_lattes'] > 0)
			{
				$link = 'http://lattes.cnpq.br/' . trim($dt['a_lattes']);
				$link1 = '<a href="' . $link . '" target="_new' . $dt['a_lattes'] . '">';
				$link1 .= '<img src="' . base_url('img/icones/lattes.png') . '" style="height: 50px">';
				$link1 .= '</a>';
			} else {
				//http://brapci3/index.php/res/admin/authority/findid/1
				if ($dt['a_brapci'] > 0)
					{
						$link = PATH.MODULE.'/admin/authority/findid/'.$dt['id_a'];
						$link1 = '<a href="' . $link . '" target="_new' . $dt['a_lattes'] . '">';
						$link1 .= 'Busca <img src="' . base_url('img/icones/lattes.png') . '" style="height: 50px">';
						$link1 .= '</a>';
					}
			}
			return $link1;
		}
}
