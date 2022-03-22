<?php

namespace App\Models\Dataverse;

use CodeIgniter\Model;

class Licences extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'licences';
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

	function index()
		{
			
		}

	function getLicences($d1,$d2,$d3)
		{
			$Dataverse = new \App\Models\Dataverse\Index();
			$url = $Dataverse->server();
			$url .= 'api/licenses';

			$sx = h($url,6);
			$txt = file_get_contents($url);
			$txt = (array)json_decode($txt,true);

			$link = '<a href="'.PATH.MODULE.'dataverse/licences/0/add">'.lang('dataverse.licence_add').'</a>';
			$sx .= bsc($link,12);

			$sx .= bsc(lang('dataverse.license'),2);
			$sx .= bsc(lang('dataverse.shortDescription'),6);
			$sx .= bsc(lang('dataverse.icone'),1);
			$sx .= bsc(lang('dataverse.active'),1);
			$sx .= bsc(lang('dataverse.default'),1);
			$sx .= bsc(lang('dataverse.trash'),1);

			

			if (isset($txt['data']))
				{
					$data = (array)$txt['data'];
					for ($r=0;$r < count($data);$r++)
						{
							$line = (array)$data[$r];
							$sx .= bsc($line['name'].'<sup> ID:'.$line['id'].'</sup>',2);
							$sx .= bsc($line['shortDescription'].'<br><i>'.anchor($line['uri'],$line['uri']).'</i>',6);
							$sx .= bsc('<img src="'.$line['iconUrl'].'" class="img-fluid">',1);
							if ($line['active']==1)
								{
									$link = '<a href="'.PATH.MODULE.'dataverse/licences/'.$line['id'].'/setdesactive">';
									$linka = '</a>';
									$sx .= bsc($link.bsicone('on').$linka,1);
								} else {
									$link = '<a href="'.PATH.MODULE.'dataverse/licences/'.$line['id'].'/setactive">';
									$linka = '</a>';									
									$sx .= bsc($link.bsicone('off').$linka,1);
								}
							if ($line['isDefault']==1)
								{
									$sx .= bsc(bsicone('on'),1);
								} else {
									$link = '<a href="'.PATH.MODULE.'dataverse/licences/'.$line['id'].'/setdefault">';
									$linka = '</a>';
									$sx .= bsc($link.bsicone('off').$linka,1);
								}

							$link = '<a href="'.PATH.MODULE.'dataverse/licences/'.$line['id'].'/trash">';
							$linka = '</a>';
							$sx .= bsc($link.bsicone('trash').$linka,1);								
							
						}
				}
			$cmd = '';
			switch($d3)
				{
					case 'add':
						$link = '<a href="https://guides.dataverse.org/en/5.10/installation/config.html#id117" target="_blank">'.'add-license.json'.'</a>';
						$cmd = '';
						$cmd .= 'export API_TOKEN=xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx'.'<br>';
						$cmd .= '<br>';
						$cmd .= 'export SERVER_URL='.$Dataverse->server().'<br>';
						$cmd .= '<br>';
						$cmd .= 'curl -X POST -H \'Content-Type: application/json\' -H X-Dataverse-key:$API_TOKEN --data-binary @'.$link.' $SERVER_URL/api/licenses';
						break;
					case 'trash':
						$cmd = '';
						$cmd .= 'export API_TOKEN=xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx'.'<br>';
						$cmd .= '<br>';
						$cmd .= 'export SERVER_URL='.$Dataverse->server().'<br>';
						$cmd .= 'export ID='.$d2.'<br>';
						$cmd .= '<br>';
						$cmd .= 'curl -X DELETE -H X-Dataverse-key:$API_TOKEN $SERVER_URL/api/licenses/$ID';
						break;
					case 'setdefault':
						$cmd = '';
						$cmd .= 'export API_TOKEN=xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx'.'<br>';
						$cmd .= '<br>';
						$cmd .= 'export SERVER_URL='.$Dataverse->server().'<br>';
						$cmd .= 'export STATE=true'.'<br>';
						$cmd .= 'export ID='.$d2.'<br>';
						$cmd .= 'curl -X PUT -H \'Content-Type: application/json\' -H X-Dataverse-key:$API_TOKEN $SERVER_URL/api/licenses/default/$ID'.'<br>';
						break;
					case 'setactive':						
						$cmd = '';
						$cmd .= 'export API_TOKEN=xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx'.'<br>';
						$cmd .= '<br>';
						$cmd .= 'export SERVER_URL='.$Dataverse->server().'<br>';
						$cmd .= 'export STATE=true'.'<br>';
						$cmd .= 'export ID='.$d2.'<br>';
						$cmd .= 'curl -X PUT -H \'Content-Type: application/json\' -H X-Dataverse-key:$API_TOKEN $SERVER_URL/api/licenses/$ID/:active/$STATE'.'<br>';
						break;
					case 'setdesactive':
						$cmd = '';
						$cmd .= 'export API_TOKEN=xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx'.'<br>';
						$cmd .= '<br>';
						$cmd .= 'export SERVER_URL='.$Dataverse->server().'<br>';						
						$cmd .= 'export STATE=false'.'<br>';
						$cmd .= 'export ID='.$d2.'<br>';
						$cmd .= 'curl -X PUT -H \'Content-Type: application/json\' -H X-Dataverse-key:$API_TOKEN $SERVER_URL/api/licenses/$ID/:active/$STATE'.'<br>';
						break;						
					default:
						$sx .= bsc('Command: '.$d3,12);
				}
			if (strlen($cmd) > 0)
				{
					$sx .= bsc(h('dataverse.bash',4).'<pre>'.$cmd.'</pre>',12);
				}
			return $sx;
		}		
}
