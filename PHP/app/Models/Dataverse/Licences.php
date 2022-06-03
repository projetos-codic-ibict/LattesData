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

	function licenses()
		{
			$url = 'https://guides.dataverse.org/en/5.10/_downloads/f150dcadc1fc593122640b9b68d8c620/';
			
			

			$dt['CC0'] = 'https://guides.dataverse.org/en/5.10/_downloads/f150dcadc1fc593122640b9b68d8c620/licenseCC0-1.0.json';
			$dt['CC-BY-4.0'] = 'https://guides.dataverse.org/en/5.10/_downloads/4272abc51bfe651a0a0a2d7b6551df55/licenseCC-BY-4.0.json';
			$dt['CC-BY-SA-4.0'] = 'https://guides.dataverse.org/en/5.10/_downloads/333b4643bcb5b4ab72b4db8e94a5f3a3/licenseCC-BY-SA-4.0.json';
			$dt['CC-BY-NC-4.0'] = 'https://guides.dataverse.org/en/5.10/_downloads/62ab2ded1364d7e074e284b1f1450dcc/licenseCC-BY-NC-4.0.json';
			$dt['CC-BY-NC-SA-4.0'] = 'https://guides.dataverse.org/en/5.10/_downloads/71bbbcd33186634934801f88edf265f9/licenseCC-BY-NC-SA-4.0.json';
			$dt['CC-BY-ND-4.0'] = 'https://guides.dataverse.org/en/5.10/_downloads/92fd9ab655dfb6f39e9fb59c0e8c58be/licenseCC-BY-ND-4.0.json';
			$dt['CC-BY-NC-ND-4.0'] = 'https://guides.dataverse.org/en/5.10/_downloads/7de196929c5db0a7075073183204d3e9/licenseCC-BY-NC-ND-4.0.json';
			return $dt;
		}	


	function getLicences($d1,$d2,$d3)
		{
			$cmd = '';
			$Dataverse = new \App\Models\Dataverse\Index();
			$url = $Dataverse->server();
			$url .= '/api/licenses';

			$sx = h($url,6);
			$txt = file_get_contents($url);
			$cmd = 'curl '.$url;
			$txt = (array)json_decode($txt,true);

			$link = '<a href="'.PATH.MODULE.'dataverse/licences/0/add" class="btn btn-outline-primary">'.lang('dataverse.licence_add').'</a>';
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
			
			switch($d3)
				{
					case 'add':
						$sx .= h(lang('dataverse.licence_add'),4);
						$sx .= $this->select_licence_type();
						break;

					case 'addx':
						$dt = $this->licenses();
						$link = '<a href="https://guides.dataverse.org/en/5.10/installation/config.html#id117" target="_blank">'.'add-license.json'.'</a>';
						
						$F = explode('/',$dt[$d2]);

						$url = $dt[$d2];
						
						$FILE = $F[count($F)-1];
						$FILE = '@'.$FILE;
						$cmd .= '<br>';
						$cmd .= 'echo \'************************** Remove json files\'';
						$cmd .= '<br>';
						$cmd .= 'rm *.json -f';
						$cmd .= '<br>';
						$cmd .= 'echo \'************************** Download the licence in Dataverse Source Repository\'';
						$cmd .= '<br>';
						$cmd .= 'wget '.$url;
						$cmd .= '<br>';
						$cmd .= 'echo \'************************** Set variables\'';
						$cmd .= '<br>';
						$cmd .= 'export API_TOKEN='.$Dataverse->token();
						$cmd .= '<br>';
						$cmd .= 'export SERVER_URL='.troca($Dataverse->server(),'//','/');
						$cmd .= '<br>';
						$cmd .= 'export FILE='.$FILE;
						$cmd .= '<br>';
						$cmd .= 'echo \'************************** Send configurations\'';
						$cmd .= '<br>';
						$cmd .= 'curl -X POST -H \'Content-Type: application/json\' -H X-Dataverse-key:$API_TOKEN --data-binary $FILE $SERVER_URL/api/licenses';
						$cmd .= '<br>';
						$cmd .= 'echo \'************************** End proccess\'';
						$cmd .= '<br>';
						break;
					case 'trash':
						$cmd = '<br>';
						$cmd .= 'export API_TOKEN='.$Dataverse->token();
						$cmd .= '<br>';
						$cmd .= 'export SERVER_URL='.$Dataverse->server().'<br>';
						$cmd .= 'export ID='.$d2.'<br>';
						$cmd .= 'curl -X DELETE -H X-Dataverse-key:$API_TOKEN $SERVER_URL/api/licenses/$ID';
						$cmd .= '<br>';
						break;
					case 'setdefault':
						$cmd = '<br>';
						$cmd .= 'export API_TOKEN='.$Dataverse->token();
						$cmd .= '<br>';
						$cmd .= 'export SERVER_URL='.$Dataverse->server().'<br>';
						$cmd .= 'export STATE=true'.'<br>';
						$cmd .= 'export ID='.$d2.'<br>';
						$cmd .= 'curl -X PUT -H \'Content-Type: application/json\' -H X-Dataverse-key:$API_TOKEN $SERVER_URL/api/licenses/default/$ID'.'<br>';
						$cmd .= '<br>';
						break;
					case 'setactive':						
						$cmd = '<br>';
						$cmd .= 'export API_TOKEN='.$Dataverse->token();
						$cmd .= '<br>';
						$cmd .= 'export SERVER_URL='.$Dataverse->server().'<br>';
						$cmd .= 'export STATE=true'.'<br>';
						$cmd .= 'export ID='.$d2.'<br>';
						$cmd .= 'curl -X PUT -H \'Content-Type: application/json\' -H X-Dataverse-key:$API_TOKEN $SERVER_URL/api/licenses/$ID/:active/$STATE'.'<br>';
						$cmd .= '<br>';
						break;
					case 'setdesactive':
						$cmd = '<br>';
						$cmd .= 'export API_TOKEN='.$Dataverse->token();
						$cmd .= '<br>';
						$cmd .= 'export SERVER_URL='.$Dataverse->server().'<br>';						
						$cmd .= 'export STATE=false'.'<br>';
						$cmd .= 'export ID='.$d2.'<br>';
						$cmd .= 'curl -X PUT -H \'Content-Type: application/json\' -H X-Dataverse-key:$API_TOKEN $SERVER_URL/api/licenses/$ID/:active/$STATE'.'<br>';
						$cmd .= '<br>';
						break;						
					default:
						$sx .= bsc('Command: '.$d3,12);
				}
			if (strlen($cmd) > 0)
				{
					$sx .= bsc(h('dataverse.bash',4).'<code>'.$cmd.'</code>',12);
				}
			return $sx;
		}
	function select_licence_type()
		{
			$dt = $this->licenses();
			$sx = h(lang('dataverse.licence_type'),4).'<br>';
			$sx .= '<ul>';
			foreach($dt as $licence => $url)
				{
					$link = '<a href="'.PATH.MODULE.'dataverse/licences/'.$licence.'/addx">';
					$linka = '</a>';
					$sx .= '<li>'.$link.$licence.$linka.'</li>';
				}
			$sx .= '</ul>';
			$sx = bs(bsc($sx,12));
			return $sx;
		}	
}
