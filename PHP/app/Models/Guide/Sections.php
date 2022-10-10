<?php

namespace App\Models\Guide;

use __PHP_Incomplete_Class;
use CodeIgniter\Model;

class Sections extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'guide_sections';
	protected $primaryKey           = 'id_sc';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'id_sc', 'sc_father',
		'sc_holder', 'sc_title',
		'sc_content', 'sc_order', 'sc_status',
		'sc_type', 'sc_lang', 'sc_update',

	];

	protected $typeFields        = [
		'hidden', 'sql:id_sc:sc_title:guide_sections',
		'string*', 'string*',
		'text', '[1-99]*', 'sel:1:ativo&0:inativo',
		'hidden', 'op pt-BR:Portugues', 'up',

	];

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

	var $menu = array();

	function index($d2, $d3, $d4)
	{
		$sx = h('Guide Sections', 2);
		$this->path = PATH . MODULE . 'guide/section';
		$this->path_back = PATH . MODULE . 'guide/section';
		switch ($d2) {
			case 'edit_':
				$sx = $this->edit_type($d3);
				break;
			case 'viewid':
				$sx = $this->viewid($d3);
				break;
			case 'edit':
				$this->id = round($d3);
				$sx = $this->edit($d3);
				break;
			case 'table':
				$sx = tableview($this);
				break;
			default:
				$sx .= $this->summary($this);
		}
		return $sx;
	}

	function summary()
		{
			$sx = '';
			$dt = $this->select('min(id_sc) as id')->findAll();
			$sx .= '<a href="' . (PATH . MODULE.'guide/section/table/') . '">'.lang('guide.table_view').'</a>';
			$sx .= $this->viewid($dt[0]['id'],'');
			return $sx;
		}
	function header_item_guide($dt)
		{
			$sx = '';
			$sx .= '<div class="row">';
			$sx .= '<div class="col-md-12">';
			$sx .= '<h1>' . $dt['sc_title'] . '</h1>';
			$sx .= '</div>';
			$sx .= '</div>';
			return $sx;
		}
	function edit_type($id)
		{
			$dt = $this->find($id);
			$sx = $this->header_item_guide($dt);

			$types['1'] = 'html';
			$types['2'] = 'text';
			$types['3'] = 'image';
			$types['4'] = 'youtube';
			$types['5'] = 'code';

			$sx .= h(lang('guide.select_a_type'),4);

			foreach($types as $value=>$desc)
				{
					$link = PATH . MODULE . 'guide/section/edit_/4?set=' . $value;
					$sc = '
						<div class="card" style="width: 100%;">
						<img class="card-img-top" src="'.URL.'img/guide/type_'.$desc.'.png" alt="Card image cap">
						<div class="card-body">
							<h5 class="card-title">'.lang('guide.type_'.$desc).'</h5>
							<p class="card-text">'.lang('guide.card_type_'.$desc).'</p>
							<a href="'. $link.'" class="btn btn-primary" style="width: 100%;">'.lang('guide.select_it').'</a>
						</div>
						</div>';
					$sx .= bsc($sc,3);
				}
			$sx = bs($sx);
			return $sx;
		}


	function nivel($header)
		{
			$t = count(explode(':',$header));
			return $t;
		}

	function viewid($id, $father = '')
	{
		$dt = $this->find($id);
		if ($father == '') {
			$father = $dt['sc_holder'];
		}

		$sx = '<div class="row">';
		$sx .= '<div class="col-md-12">';
		switch($this->nivel($father))
			{
				default:
				$sx .= '['.$this->nivel($father).'] ';
			}
		$type = trim($dt['sc_type']);
		$link = '<a href="'.PATH.MODULE. 'guide/section/edit_'.$type.'/' . $dt['id_sc'] . '">';
		$linka= '</a>';

		$sx .= '<span>' . $link.$dt['sc_title'].$linka . '</span>';
		$sx .= '</div>';
		$sx .= '</div>';

		/* Sub view */
		$dta = $this->where('sc_father', $id)->orderBy('sc_order', 'ASC')->findAll();
		if (count($dta) > 0)
		{
			$sx .= '<ul>';
			for ($r = 0; $r < count($dta); $r++) {
				$father2 = $father . ':' . $dta[$r]['sc_holder'];
				$sx .= '<li>';
				$sx .= $this->viewid($dta[$r]['id_sc'], $father2);
				$sx .= '</li>';
			}
			$sx .= '</ul>';
		}
		return $sx;
	}

	function edit($id)
	{
		$sx = form($this);
		return $sx;
	}
}
