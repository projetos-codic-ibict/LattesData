<?php

namespace App\Models\Lattes;

use CodeIgniter\Model;

class LattesId extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'lattesids';
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

	function LattesFindID($q='Name for query')
	{
		$dt = array();
		$file = '../_csv/CVlattesASCII.csv';
		if (!file_exists(($file)))		
			{
				echo 'File no found - '.$file;
				exit;
			}
		if (strpos($q,',') > 0)
			{
				$q = nbr_author($q,7);
			}
		$handle = fopen($file, "r");
		$q = mb_strtoupper(ascii($q));
		$dt['query'] = $q;
		$tot = 0;
		$rst = array();
		if ($handle) {
			while (($line = fgets($handle)) !== false) {
				// process the line read.
				if (strpos(' ' . $line, $q)) {
					$d = explode(';', $line);
					$rst[$d[1]] = $d[0];
					//$dt['result'][$d[0]] = $d[1];
					$tot++;
					if ($tot > 10)
						{
							$dt = array();
							$dt['erro'] = 101;
							$dt['descript'] = lang('api.multiple_results');
						}
				}
			}
			fclose($handle);
		}
		$dt['result'] = $rst;
		return $dt;
	}	

	function xxLattesFindID($id)
		{
			$tela = '';
			$Api = new \App\Models\Api\Endpoints();
			$AuthorityNames = new \App\Models\Authority\AuthorityNames();

			$dt = $AuthorityNames->find($id);
			$name = trim($dt['a_prefTerm']);

			$dta = $Api->LattesFindID($name);

			if (isset($dta['result']))
				{
					$dtc = $dta['result'];
					if (count($dtc) == 1)
						{
							$data['id_a'] = $dt['id_a'];
							foreach($dtc as $id_lattes=>$name)
								{
									$data['a_lattes'] = $id_lattes;
									$sql = "update ".$this->table." set a_lattes = '".$id_lattes."' where id_a = ".$dt['id_a'];

									$this->query($sql);
									$tela .= metarefresh(base_url(PATH.MODULE.'/index/viewid/'.$dt['id_a']));
								}
						} else {
							echo '<pre>';
							print_r($dta);
							echo '</pre>';
							exit;
						}
				}
			return $tela;
		}	
}
