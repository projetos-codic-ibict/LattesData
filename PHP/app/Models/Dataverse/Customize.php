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
					case 'copyright':
						$sx .= '<code>curl -X PUT -d ", CNPq/Ibict" http://localhost:8080/api/admin/settings/:FooterCopyright</code>';
						break;

					case 'Languages':
						$tlang = '';
						$subdir = array('en_US','pt_BR','es','fr','de','it','pt','ru','zh');
						$lang_n = array('English','Português','Espanhol','Frances','Alemão','Italiano','Português','Russo','Chinês');
						$langs = array('en','br');
						$default = 'br';
						$cmd = '';
						$cmd .= 'mkdir /var/www/dataverse/'.cr();
						$cmd .= 'mkdir /var/www/dataverse/langBundles/'.cr();
						$cmd .= 'mkdir /var/www/dataverse/langTmp/'.cr();
						$cmd .= 'mkdir /var/www/dataverse/langTmp/pt_BR'.cr();
						$cmd .= 'mkdir /var/www/dataverse/langTmp/source'.cr();
						$cmd .= 'echo "Baixando atualizações"'.cr();
						$cmd .= 'echo "==>Portugues"'.cr();
						$cmd .= 'cd /var/www/dataverse/langTmp/pt_BR/'.cr();
						$cmd .= 'rm * -r'.cr();
						$cmd .= 'wget https://github.com/RNP-dadosabertos/dataverse-language-packs/archive/develop.zip'.cr();;
						$cmd .= 'unzip develop.zip'.cr();
						
						$cmd .= 'echo "==>Outros Idiomas"'.cr();
						$cmd .= 'cd /var/www/dataverse/langTmp/source/'.cr();
						$cmd .= 'rm * -r'.cr();
						$cmd .= 'wget https://github.com/GlobalDataverseCommunityConsortium/dataverse-language-packs/archive/refs/heads/develop.zip'.cr();
						$cmd .= 'unzip develop.zip'.cr();

						$cmd .= 'echo "Copiando os arquivos necessários"'.cr();
						$cmd .= 'rm /var/www/dataverse/langTmp/*.properties'.cr();
						$cmd .= 'rm /var/www/dataverse/langTmp/*.zip'.cr();
						$files = array(
								'astrophysics$lg.properties',
								'biomedical$lg.properties',
								'BuiltInRoles$lg.properties',
								'Bundle$lg.properties',
								'citation$lg.properties',
								'geospatial$lg.properties',
								'journal$lg.properties',
								'MimeTypeDetectionByFileExtension$lg.properties',
								'MimeTypeDisplay$lg.properties',
								'MimeTypeFacets$lg.properties',
								'socialscience$lg.properties',
								'ValidationMessages$lg.properties',
						);
						for ($r=0;$r < count($langs);$r++)
							{
								echo cr();
								$cmd .= "======================== Copy files =".$langs[$r].cr();
								$n_subdir = $subdir[$r];
								if ($langs[$r] == 'br')
									{
										$dir = '/var/www/dataverse/langTmp/pt_BR/dataverse-language-packs-develop/'.$n_subdir;
										$out = '/var/www/dataverse/langTmp/';
									} else {
										$dir = '/var/www/dataverse/langTmp/source/dataverse-language-packs-develop/'.$n_subdir;
										$out = '/var/www/dataverse/langTmp/';
									}
								if (strlen($tlang) > 0) { $tlang .= ', ';}
								$tlang .= '{"locale":"'.$langs[$r].'","title":"'.$lang_n[$r].'"}';
								for ($f=0;$f < count($files);$f++)
									{	
										$xlang = $langs[$r];							
										if (($xlang == $default))
										{
											$xlang = '';
										} else {
											$xlang = '_'.$xlang;
										}

										$ori = $dir .'/'.$files[$f];
										$ori = troca($ori,'$lg',$xlang);

										$des = $out .$files[$f];										
										$des = troca($des,'$lg',$xlang);

										$cmd .= "cp $ori $des".cr();
									}
							}
						$cmd .=  cr();
						$cmd .= 'echo "===>Preparing ZIP FILE"'.cr();
						$cmd .= 'cd /var/www/dataverse/langTmp/'.cr();
						$cmd .= 'rm *.zip'.cr();
						$cmd .= 'zip languages.zip *.properties'.cr();
						$cmd .= 'export PAYARA=/usr/local/payara5/glassfish'.cr();
						$cmd .= '$PAYARA/bin/asadmin create-jvm-options \'-Ddataverse.lang.directory=/var/www/dataverse/langBundles\''.cr();
						$cmd .= '$PAYARA/bin/asadmin stop-domain'.cr();
						$cmd .= '$PAYARA/bin/asadmin start-domain'.cr();
						$cmd .= 'curl http://localhost:8080/api/admin/datasetfield/loadpropertyfiles -X POST --upload-file languages.zip -H "Content-Type: application/zip"';
						$cmd .= cr();
						$cmd .= 'curl http://localhost:8080/api/admin/settings/:Languages -X PUT -d \'['.$tlang.']\''.cr();

						$cmd .= 'echo "===>Definindo o idoma principal do Dataverse"'.cr();
						$cmd .= 'cd /var/www/dataverse/langBundles'.cr();
						$cmd .= 'cp *.properties /usr/local/payara5/glassfish/domains/domain1/applications/dataverse/WEB-INF/classes/propertyFiles/.'.cr();
						$cmd .= '$PAYARA/bin/asadmin stop-domain'.cr();
						$cmd .= '$PAYARA/bin/asadmin start-domain'.cr();
						$cmd .= cr();
						$cmd .= 'echo "FIM DA ATUALIZAÇÂO"'.cr();
						$cmd .= cr();
						break;
					case 'sitemap':
						$cmd .= 'curl -X POST http://localhost:8080/api/admin/sitemap';
						$sx .= 'Result in: '.cr();
						$sx .= '/usr/local/payara5/glassfish/domains/domain1/docroot/sitemap/sitemap.xml';
						break;
					case 'googleanalytics':
						$cmd = 'https://guides.dataverse.org/en/latest/installation/config.html?highlight=google%20analytics';
						break;
						
					case 'homePage':
						$sx .= $this->homepage();
						break;

					case 'homeFooter':
						$cmd .= 'mkdir /var/www/dataverse/'.cr();
						$cmd .= 'mkdir /var/www/dataverse/branding/'.cr();
						$cmd .= 'echo "See sample <a href="https://guides.dataverse.org/en/latest/_downloads/0f28d7fe1a9937d9ef47ae3f8b51403e/custom-homepage.html">homepage"</a>'.cr();
						$cmd .= 'curl -X PUT -d \'/var/www/dataverse/branding/custom-homepage.html\' http://localhost:8080/api/admin/settings/:HomePageCustomizationFile'.cr();
						$cmd .= cr();
						$cmd .= 'echo "Remove Custom Page"'.cr();
						$cmd .= 'curl -X PUT -d \'/var/www/dataverse/branding/custom-footer.html\' http://localhost:8080/api/admin/settings/:FooterCustomizationFile'.cr();
						$PATH = '/var/www/dataverse/branding/';
						break;						

					case 'logo':
						$cmd .= 'echo "Grave o logo na pasta abaixo:"'.cr();
						$cmd .= 'echo "/usr/local/payara5/glassfish/domains/domain1/docroot/logos/navbar/$file"'.cr();
						$cmd .= 'curl -X PUT -d \'/logos/navbar/$file\' http://localhost:8080/api/admin/settings/:LogoCustomizationFile';
						$file = true;
						$PATH = '/usr/local/payara5/glassfish/domains/domain1/docroot/logos/navbar/';
						break;

					case 'css':
						$cmd .= $this->css();
						break;						

					case 'homeFooter':
						$cmd .= 'echo "Alterar no Rodape os dados sobre Copyright &copy"'.cr();
						$cmd .= 'echo "/usr/local/payara5/glassfish/domains/domain1/docroot/logos/navbar/$file"'.cr();
						$cmd .= 'curl -X PUT -d ", CNPq/Ibict" http://localhost:8080/api/admin/settings/:FooterCopyright';
						$PATH = '/usr/local/payara5/glassfish/domains/domain1/docroot/logos/navbar/';
						break;	
					case 'DOIX':
						$cmd .= 'curl -X PUT -d http://dataverse.example.edu http://localhost:8080/api/admin/settings/:NavbarAboutUrl';
						break;


					default:				
					$menu[PATH.MODULE.'dataverse/customize/logo'] = 'dataverse.customize_logo';
					$menu[PATH.MODULE.'dataverse/customize/homePage'] = 'dataverse.customize_homepage';
					$menu[PATH.MODULE.'dataverse/customize/homeHeader'] = 'dataverse.customize_header';
					$menu[PATH.MODULE.'dataverse/customize/homeFooter'] = 'dataverse.customize_footer';
					$menu[PATH.MODULE.'dataverse/customize/Languages'] = 'dataverse.customize_language';
					$menu[PATH.MODULE.'dataverse/customize/googleanalytics'] = 'dataverse.customize_GoogleAnalytics';
					$menu[PATH.MODULE.'dataverse/customize/sitemap'] = 'dataverse.customize_sitemap';
					$menu[PATH.MODULE.'dataverse/customize/css'] = 'dataverse.customize_css';
					$menu[PATH.MODULE.'dataverse/customize/copyright'] = 'dataverse.customize_FooterCopyright';
					//:NavbarAboutUrl
					//:NavbarGuidesUrl
					//:GuidesBaseUrl
					//:GuidesVersion
					//:NavbarSupportUrl
					//:MetricsUrl
					//:MaxFileUploadSizeInBytes
					//:ZipDownloadLimit
					//:TabularIngestSizeLimit

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
			//$cmd = troca($cmd,chr(10),'<br>');
			$sx .= '<pre>'.$cmd.'</pre>';
			return $sx;
		}	

		function homepage()
			{
				$cmd = '';
						$cmd .= 'mkdir /var/www/dataverse/'.cr();
						$cmd .= 'mkdir /var/www/dataverse/branding/'.cr();
						$cmd .= 'echo "See sample <a href="https://guides.dataverse.org/en/latest/_downloads/0f28d7fe1a9937d9ef47ae3f8b51403e/custom-homepage.html">homepage"</a>'.cr();
						$cmd .= 'curl -X PUT -d \'/var/www/dataverse/branding/custom-homepage.html\' http://localhost:8080/api/admin/settings/:HomePageCustomizationFile'.cr();
						$cmd .= cr();
						$cmd .= 'echo "Remove Custom Page"'.cr();
						$cmd .= 'curl -X DELETE http://localhost:8080/api/admin/settings/:HomePageCustomizationFile'.cr();
						$PATH = '/var/www/dataverse/branding/';

				$sx = '';
				$sx .= h('dataverse.HomePage');
				$sx .= form_open_multipart();
				$sx .= form_upload('userfile');
				$sx .= form_submit(array('name'=>'submit','value'=>lang('dataverse.upload')));
				$sx .= form_close();

				if (isset($_FILES['userfile']['name']))
					{
						$file = $_FILES['userfile']['tmp_name'];
						$name = $_FILES['userfile']['name'];
						$type = $_FILES['userfile']['type'];
						if ($type == 'text/html')
							{
								$file2 = '/var/www/dataverse/branding/cnpq_homepage.html';
								if (file_exists($file2))
									{
										unlink($file2);
									}

								move_uploaded_file($file,'/var/www/dataverse/branding/cnpq_homepage.html');
								$sx .= bsmessage('Uploaded - Move:' .$file.' to /var/www/dataverse/branding/css/custom-stylesheet.css');
							} else {
								$sx .= bsmessage('File not HTML - ['.$type.']',3);
							}
					}
				$sx .= '<pre>'.$cmd.'</pre>';

				return $sx;
			}

		function css()
			{
				$sx = '';
				$sx .= h('dataverse.StyleCSS');
				$sx .= form_open_multipart();
				$sx .= form_upload('userfile');
				$sx .= form_submit(array('name'=>'submit','value'=>lang('dataverse.upload')));
				$sx .= form_close();

				if (isset($_FILES['userfile']['name']))
					{
						$file = $_FILES['userfile']['tmp_name'];
						$name = $_FILES['userfile']['name'];
						$type = $_FILES['userfile']['type'];
						if ($type == 'text/css')
							{
								$file2 = '/var/www/dataverse/branding/custom-stylesheet.css';
								if (file_exists($file2))
									{
										unlink($file2);
									}								
								move_uploaded_file($file,'/var/www/dataverse/branding/custom-stylesheet.css');
								$sx .= bsmessage('Uploaded - Move:' .$file.' to /var/www/dataverse/branding/css/custom-stylesheet.css');
							} else {
								$sx .= bsmessage('File not CSS',3);
							}
					}				

				return $sx;
			}
}
