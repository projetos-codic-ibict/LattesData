<?php

namespace App\Models\Dataverse;

use CodeIgniter\Model;

class Install extends Model
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
	function index($d1='',$d2='',$d3='')
		{
			$ver = '5.10.1';
			$sx = '';
			$sx .= breadcrumbs();
			switch($d1)
				{
					case 'dvnapp':
						$sx .= '<h1>Dataverse App Install</h1>';
						$sx .= '<code>cd /home/dataverse/install/dvinstall</code><br>';
						$sx .= '<p>Configure os valores padrão de acesso ao Dataverse</p>';
						$sx .= '<code> pico default.config</code><br>';
						$sx .= '<h4>Install Python</h4>';
						$sx .= '';
						$sx .= 'apt install python3-pip<br>';
						$sx .= '';
						break;
					case 'tools':
						$sx .= '<h1>Tools</h1>';
						$sx .= anchor(PATH.MODULE.'dataverse/system',msg('dataverse.system_facilities'));
						break;
					case 'access':
						$sx .= '<code>
						URL: http://localhost:8080<br>
						username: dataverseAdmin<br>
						password: admin<br>
						</code>';
						break;
					case 'download':
						$sx .= '<h2>Install Dataverse</h2>';
						$sx .= '<p>Documentação</p>';
						$sx .= '<a href="https://guides.dataverse.org/en/latest/installation/installation-main.html">https://guides.dataverse.org/en/latest/installation/installation-main.html</a>';
						$sx .= '<br>&nbsp;';
						$sx .= '<h2>Criando usuário Dataverse</h2>';
						$sx .= '<code>useradd dataverse -m</code>';
						$sx .= '<br>&nbsp;';
						$sx .= '<h2>Download the Dataverse</h2>';
						$sx .= anchor('https://github.com/IQSS/dataverse/releases');

						$sx .= '<code>mkdir /home/dataverse/install/<br>
						cd /home/dataverse/install<br>
						<br>						
						wget https://github.com/IQSS/dataverse/releases/download/'.$ver.'/dvinstall.zip<br>
						unzip dvinstall.zip<br>
						</code>
						';
						break;
					case 'pre':
						$sx .= '<h2>Install Dataverse</h2>';
						$sx .= '<ul>';
						$sx .= '<li>';
						$sx .= '<a href="https://guides.dataverse.org/en/latest/installation/prerequisites.html">Prerequisites</a>';

						$sx .= '<ul>';
						$sx .= '<li><a href="'.PATH.MODULE.'dataverse/java">Java</a></li>';
						$sx .= '<li><a href="'.PATH.MODULE.'dataverse/payara">Payara</a></li>';
						$sx .= '<li><a href="'.PATH.MODULE.'dataverse/postgres">PostgreSQL</a></li>';
						$sx .= '<li><a href="'.PATH.MODULE.'dataverse/solr">Solr</a></li>';
						$sx .= '<li><a href="'.PATH.MODULE.'dataverse/imagemagick">ImageMagick</a></li>';
						$sx .= '<li><a href="'.PATH.MODULE.'dataverse/jq">JQ</a></li>';
						$sx .= '<li><a href="'.PATH.MODULE.'dataverse/r">R Server</a></li>';
						$sx .= '</ul>';
						
						$sx .= '</li>';
						$sx .= '</ul>';
						break;
					default:
						$menu[PATH.MODULE.'dataverse/install/download'] = lang('dataverse.DownloadDataverse');
						$menu[PATH.MODULE.'dataverse/install/pre'] = lang('dataverse.PrepraringInstall');
						$menu[PATH.MODULE.'dataverse/install/tools'] = lang('dataverse.DataverseTools');
						$menu[PATH.MODULE.'dataverse/install/dvnapp'] = lang('dataverse.DataverseDvnapp');						
						$menu[PATH.MODULE.'dataverse/install/access'] = lang('dataverse.DataverseAccess');
						$sx .= menu($menu);
				}
			return $sx;
		}

}
