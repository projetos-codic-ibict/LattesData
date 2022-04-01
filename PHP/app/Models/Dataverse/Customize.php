<?php

namespace App\Models\Dataverse;

use CodeIgniter\Model;

class Customize extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'customizes';
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
					$sx .= h('dataverse.customize_'.$d1,4);
				}
			
			$cmd = '';
			$file = false;
			switch($d1)
				{
					case 'homePage':
						$cmd .= 'mkdir /var/www/dataverse/'.cr();
						$cmd .= 'mkdir /var/www/dataverse/branding/'.cr();
						$cmd .= 'echo "See sample <a href="https://guides.dataverse.org/en/latest/_downloads/0f28d7fe1a9937d9ef47ae3f8b51403e/custom-homepage.html">homepage"</a>'.cr();
						$cmd .= 'curl -X PUT -d \'/var/www/dataverse/branding/custom-homepage.html\' http://localhost:8080/api/admin/settings/:HomePageCustomizationFile'.cr();
						$cmd .= 'echo "Remove Custom Page"'.cr();
						$cmd .= 'curl -X DELETE http://localhost:8080/api/admin/settings/:HomePageCustomizationFile'.cr();
						$PATH = '/var/www/dataverse/branding/';
						break;

					case 'logo':
						$cmd .= 'echo "Grave o logo na pasta abaixo:"'.cr();
						$cmd .= 'echo "/usr/local/payara5/glassfish/domains/domain1/docroot/logos/navbar/$file"'.cr();
						$cmd .= 'curl -X PUT -d \'/logos/navbar/$file\' http://localhost:8080/api/admin/settings/:LogoCustomizationFile';
						$file = true;
						$PATH = '/usr/local/payara5/glassfish/domains/domain1/docroot/logos/navbar/';
						break;
					default:				
					$menu[PATH.MODULE.'dataverse/customize/logo'] = 'dataverse.customize_logo';
					$menu[PATH.MODULE.'dataverse/customize/homePage'] = 'dataverse.customize_homepage';
					$PATH = '/var/www/dataverse/branding/';
					$sx .= menu($menu);
					break;
				}
			if ($file)
			{
				$sf = form_open_multipart();
				$sf .= form_upload('userfile');
				$sf .= form_submit(array('name'=>'submit','value'=>lang('dataverse.upload')));
				$sf .= form_close();
				$sx .= bsc($sf,12,'mt-2 mb-2 p-5 border border-info');

				/********************************************************** FILE */
				if (isset($_FILES['userfile']['name']))
					{
						$file = $_FILES['userfile']['tmp_name'];
						$name = $_FILES['userfile']['name'];
						$type = $_FILES['userfile']['type'];

						$p = explode('/',$PATH);

						$dir = '';
						for ($r=0;$r < count($p);$r++)
							{
								$dir .= $p[$r].'/';
								$dir = troca($dir,'//','/');
								if (!is_dir($dir))
									{
										mkdir($dir);
									}
							}
						move_uploaded_file($file,$PATH.$name);						
						$sx .= bsmessage('Uploaded - Move:' .$file.' to '.$PATH.$name.'<br>'.$PATH.$name,1);
						$cmd = troca($cmd,'$file',$name);
					}
				
			}
			$sx .= '<pre>'.troca($cmd,chr(10),'<br>').'</pre>';
			return $sx;
		}	
}
