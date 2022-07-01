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
			
			$sx = h(lang('Dataverse.Customize'),1);
			if (strlen($d1) > 0)
				{
					$sx .= h('dataverse.customize_'.$d1,4);
				}
			
			$sx = '';
			
			$file = false;
			switch($d1)
				{
					case 'copyright':
						$sx .= '<code>curl -X PUT -d ", CNPq/Ibict" http://localhost:8080/api/admin/settings/:FooterCopyright</code>';
						break;

					case 'homePageJS':
						$sx .= $this->js();
						break;						

					case 'Languages':
					// Locale - Problemas de idioma
					// https://www.linhadecomando.com/so-linux/linux-instalando-o-locale-pt_br-utf-8
					// Checar o log - BundleUtil
						$tlang = '';
						$tlang .= '{"locale":"en","title":"Idioma Padrão"}';
						$subdir = array('en_US','pt_BR','es','fr','de','it','pt','ru','zh');
						$lang_n = array('English','Português','Espanhol','Frances','Alemão','Italiano','Português','Russo','Chinês');
						$langs = array('us','br');
						//$default_language = 'en';
						$default_language = 'br';
						$sx = '';
						$sx .= 'mkdir /var/www/dataverse/'.cr();
						$sx .= 'mkdir /var/www/dataverse/langBundles/'.cr();
						$sx .= 'mkdir /var/www/dataverse/langTmp/'.cr();
						$sx .= 'mkdir /var/www/dataverse/langTmp/sources/'.cr();
						$sx .= 'mkdir /var/www/dataverse/langTmp/sources/pt_BR'.cr();
						$sx .= 'mkdir /var/www/dataverse/langTmp/sources/All'.cr();
						$sx .= 'echo "Baixando atualizações"'.cr();
						$sx .= 'echo "==>Portugues"'.cr();
						$sx .= 'cd /var/www/dataverse/langTmp/sources/pt_BR/'.cr();
						$sx .= 'rm * -r'.cr();
						$sx .= 'wget https://github.com/RNP-dadosabertos/dataverse-language-packs/archive/develop.zip'.cr();;
						$sx .= 'unzip develop.zip'.cr();
						
						$sx .= 'echo "==>Outros Idiomas"'.cr();
						$sx .= 'cd /var/www/dataverse/langTmp/sources/All/'.cr();
						$sx .= 'rm * -r'.cr();
						$sx .= 'wget https://github.com/GlobalDataverseCommunityConsortium/dataverse-language-packs/archive/refs/heads/develop.zip'.cr();
						$sx .= 'unzip develop.zip'.cr();

						$sx .= 'echo "Copiando os arquivos necessários"'.cr();
						$sx .= 'rm /var/www/dataverse/langTmp/*.properties'.cr();
						$sx .= 'rm /var/www/dataverse/langTmp/*.zip'.cr();
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
						$sd = '';
						for ($r=0;$r < count($langs);$r++)
							{
								$n_subdir = $subdir[$r];
								$sx .= 'echo "======================== Copy files ='.$langs[$r].'--'.$n_subdir.'"'.cr();
								$out = '/var/www/dataverse/langTmp/';
								if ($langs[$r] == 'br')
									{
										$dir = '/var/www/dataverse/langTmp/sources/pt_BR/dataverse-language-packs-develop/'.$n_subdir;
									} else {
										$dir = '/var/www/dataverse/langTmp/sources/All/dataverse-language-packs-develop/'.$n_subdir;
									}
								if (strlen($tlang) > 0) { $tlang .= ', ';}
								$tlang .= '{"locale":"'.$langs[$r].'","title":"'.$lang_n[$r].'"}';
								for ($f=0;$f < count($files);$f++)
									{	
										$xlang = $langs[$r];							
										if (($xlang == $default_language))
										{
											$default = true;
											$xlang = '_'.$xlang;
										} else {
											$default = false;
											$xlang = '_'.$xlang;
										}

										$ori = $dir .'/'.$files[$f];
										$ori = troca($ori,'$lg',$xlang);

										$des = $out .$files[$f];										
										$des2 = troca($des,'$lg','_en');
										$des = troca($des,'$lg',$xlang);

										if ($xlang = 'en_US')
											{
												$ori = troca($ori,'_us','');
											}


										$sx .= "cp $ori $des".cr();

										if ($default)
											{
												$sd .= "cp $ori $des2".cr();
											}
									}
							}
						$sx .= 'echo "======================== Copy default files ="'.cr();
						$sx .= $sd;
						$sx .=  cr();
						$sx .= 'echo "===>Preparing ZIP FILE"'.cr();
						$sx .= 'cd /var/www/dataverse/langTmp/'.cr();
						$sx .= 'rm *.zip'.cr();
						$sx .= 'zip languages.zip *.properties'.cr();
						$sx .= 'export PAYARA=/usr/local/payara5/glassfish'.cr();
						$sx .= '$PAYARA/bin/asadmin create-jvm-options \'-Ddataverse.lang.directory=/var/www/dataverse/langBundles\''.cr();
						$sx .= '$PAYARA/bin/asadmin stop-domain'.cr();
						$sx .= '$PAYARA/bin/asadmin start-domain'.cr();
						$sx .= 'curl http://localhost:8080/api/admin/datasetfield/loadpropertyfiles -X POST --upload-file languages.zip -H "Content-Type: application/zip"';
						$sx .= '$PAYARA/bin/asadmin stop-domain'.cr();
						$sx .= '$PAYARA/bin/asadmin start-domain'.cr();						
						$sx .= cr();

						$sx .= 'echo "===>Definindo so idiomas do Dataverse e suas extensões"'.cr();
						$sx .= 'curl http://localhost:8080/api/admin/settings/:Languages -X PUT -d \'['.$tlang.']\''.cr();

						$sx .= cr();
						$sx = '<pre>'.$sx.'</pre>';
						break;
					case 'sitemap':
						$sx .= 'curl -X POST http://localhost:8080/api/admin/sitemap';
						$sx .= 'Result in: '.cr();
						$sx .= '/usr/local/payara5/glassfish/domains/domain1/docroot/sitemap/sitemap.xml';
						break;
					case 'googleanalytics':
						$sx .= h('dataverse.GoogleAnalytics');
						$sx .= '<p>Crie uma página anlytics-cod.html com o conteúdo:</p>';
						$sx .= '<pre>nano /var/www/dataverse/branding/analytics-code.html</pre>';
						$sx .= '<p>Preencha com o código do analytics</p>';
						$sc = '<!-- Global Site Tag (gtag.js) - Google Analytics -->
<script async="async" src="https://www.googletagmanager.com/gtag/js?id=G-GMQKC98HPT"></script>
<script>
	//<![CDATA[
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag(\'js\', new Date());

	gtag(\'config\', \'G-GMQKC98HPT\');
	//]]>
</script>';
					
						$sc = troca($sc,'<','&lt;');
						//$sc = troca($sc,chr(10),'<br>');
						$sx .= '<pre>'.$sc.'</pre>';
						$sx .= '<p>Parametrize o Dataverse para chamar este código</p>';
						$sx .= '<pre>curl -X PUT -d \'/var/www/dataverse/branding/analytics-code.html\' http://localhost:8080/api/admin/settings/:WebAnalyticsCode</pre>';
						$sx .= '<p>Para mais informações, acesse:</p>';
						$sx .= 'https://guides.dataverse.org/en/latest/installation/config.html?highlight=google%20analytics';
						break;
						
					case 'homePage':
						$sx .= $this->homepage();
						break;

					case 'FooterCustomizationFile':
						$sx .= $this->FooterCustomizationFile();
						break;	
					case 'HeaderCustomizationFile':
						$sx .= $this->HeaderCustomizationFile();
						break;					

					case 'logo':
						$sx .= $this->logo();
						break;

					case 'css':
						$sx .= $this->css();
						break;						

					case 'homeFooter':
						$sx .= 'echo "Alterar no Rodape os dados sobre Copyright &copy"'.cr();
						$sx .= 'echo "/usr/local/payara5/glassfish/domains/domain1/docroot/logos/navbar/$file"'.cr();
						$sx .= 'curl -X PUT -d ", CNPq/Ibict" http://localhost:8080/api/admin/settings/:FooterCopyright';
						$PATH = '/usr/local/payara5/glassfish/domains/domain1/docroot/logos/navbar/';
						break;	

					case 'DOIX':
						$sx .= 'curl -X PUT -d http://dataverse.example.edu http://localhost:8080/api/admin/settings/:NavbarAboutUrl';
						break;
					case 'NavbarAboutUrl':
						$sx .= $this->NavbarAboutUrl();
						break;


					default:				
					$menu[PATH.MODULE.'dataverse/customize/logo'] = lang('dataverse.customize_logo');
					$menu[PATH.MODULE.'dataverse/customize/homePage'] = lang('dataverse.customize_homepage');
					$menu[PATH.MODULE.'dataverse/customize/homePageJS'] = '<ul><li>'.lang('dataverse.customize_homepageJS').'</li></ul>';
					$menu[PATH.MODULE.'dataverse/customize/NavbarAboutUrl'] = lang('dataverse.NavbarAboutUrl');
					$menu[PATH.MODULE.'dataverse/customize/HeaderCustomizationFile'] = lang('dataverse.HeaderCustomizationFile');
					$menu[PATH.MODULE.'dataverse/customize/FooterCustomizationFile'] = lang('dataverse.FooterCustomizationFile');
					$menu[PATH.MODULE.'dataverse/customize/Languages'] = lang('dataverse.customize_language');
					$menu[PATH.MODULE.'dataverse/customize/googleanalytics'] = lang('dataverse.customize_GoogleAnalytics');
					$menu[PATH.MODULE.'dataverse/customize/sitemap'] = lang('dataverse.customize_sitemap');
					$menu[PATH.MODULE.'dataverse/customize/css'] = lang('dataverse.customize_css');
					$menu[PATH.MODULE.'dataverse/customize/copyright'] = lang('dataverse.customize_FooterCopyright');
					
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
						$sx = troca($sx,'$file',$name);
					}
				
			}
			//$sx = troca($sx,chr(10),'<br>');
			return $sx;
		}	

		function js()
			{
				$Dataverse = new \App\Models\Dataverse\Index();
				$url = $Dataverse->server();				

				$cmd = get("action");
				$sx = h('dataverse.customize_homepageJS');
				switch($cmd)
					{
						case 'dataverses':
							$url .= '/api/info/metrics/dataverses';
							echo $url;
							exit;
							$txt = file_get_contents($url);

							$sx .= 'Dataverses: <div id="total_dataverses">0</div>'.cr();
							$sx .= h($url,4);
							$sx .= '<script>'.cr();
							$sx .= 'console.log("Iniciando");'.cr();
							$sx .= 'function getAPI(url) {
									fetch(url)
									.then(function(response) {
										return response.text();
									})
									.then(function(body) {
										const obj = JSON.parse(body);
										alert(body);
										return body;										
									});
									}'.cr();

							$sx .= 'function getAPI2(url,div) {
									fetch(url)
									.then(function(response) {
										return response.text();
									})
									.then(function(body) {
										const obj = JSON.parse(body);
										alert(body);
										return body;										
									});
									}'.cr();

							//$sx .= 'document.getElementById("total_dataverses").innerHTML = obj.data[1].count;'.cr();
							$sx .= 'obj = getAPI2(\''.$url.'\');'.cr();
							$sx .= 'console.log(obj)'.cr();
							$sx .= 'document.getElementById("total_dataverses").innerHTML = obj.data.count;'.cr();
							$sx .= '</script>'.cr();
							return $sx;
							

						break;

						default:
							$opt = array('dataverses','tree');
							for ($r=0;$r < count($opt);$r++)
								{
									$menu[PATH.MODULE.'dataverse/customize/homePageJS?action='.$opt[$r]] = lang('dataverse.customize_homepageJS_'.$opt[$r]);
								}
							
							$sx .= menu($menu);
						break;							
					}

				return $sx;
			}

		function logo()
			{
				$sx = '';
				$logo = 'logo.png';
				if (get("logo") != '') 
					{ $logo = get('logo'); }		
				$sx .= h(lang('dataverse.sample_logo'),2);
				$sx .= '<img src="'.URL.'img/samples/dataverse_logo.png"  class="img-fluid">';
				$sx .= '<span class="small">Imgage height: 50px</span>';

				$sx .= h(lang('dataverse.form_logo'),2,'mt-5');
				$form = '';
				$form .= form_open();
				$form .= '<small>'.lang('dataverse.logo_file').'</small>';
				$form .= '<div class="input-group">';
				$form .= form_input(array('name'=>'logo','style'=>'font-size: 250%;','value'=>$logo,'class'=>'form-control'));
  				$form .= '<div class="input-group-prepend">';
				$form .= '<span class="input-group-text">ex: logo.png</span>';
				$form .= form_submit(array('name'=>'submit','class'=>'btn btn-outline-primary','value'=>lang('dataverse.upload_setname')));
  				$form .= '</div>';
				$form .= '</div>';
				$form .= '</div>';
				$form .= form_close();
				$sx .= bsc($form,12,'shadow p-3 bg-body rounded');
			
				$sx .= h(lang('dataverse.script_logo'),2,'mt-5');
				$sx .= 'Crie o diretório para gravar o arquivo:<br>';
				$sx .= '<pre>';
				$sx .= 'mkdir /usr/local/payara5/glassfish/domains/domain1/docroot/logos/'.cr();
				$sx .= 'mkdir /usr/local/payara5/glassfish/domains/domain1/docroot/logos/navbar/'.cr();
				$sx .= '</pre><br>';
				$sx .= 'Acesse a página onde se localiza o arquivo da logo, ex:<br>';
				$sx .= '<pre>cd /data/LattesData/_Documentation/Icones</pre><br>';
				$sx .= 'Salve o arquivo '.$logo.' no diretorio /usr/local/payara5/glassfish/domains/domain1/docroot/logos/navbar/';
				$sx .= '<br>';
				$sx .= '<pre>';
				$sx .= 'cp '.$logo.' /usr/local/payara5/glassfish/domains/domain1/docroot/logos/navbar/.'.cr();
				$sx .= '</pre>';

				$sx .= 'Ativar configurações de logo:<br>';
				$sx .= '<pre>';
				$sx .= "curl -X PUT -d '/logos/navbar/$logo' http://localhost:8080/api/admin/settings/:LogoCustomizationFile".cr();
				$sx .= '</pre>';
				
				$file = true;
				$PATH = '/usr/local/payara5/glassfish/domains/domain1/docroot/logos/navbar/';
				$sx .= bs($sx);
				return $sx;

			}

			function HeaderCustomizationFile ()
			{
				$sx = '';
				$sx .= '<p>Para incluir personalizações no &lt;header> das páginas.</p>';
				$sx .= '<pre>';
				$sx .= 'mkdir /var/www/dataverse/'.cr();
				$sx .= 'mkdir /var/www/dataverse/branding/'.cr();
				$sx .= '</pre>';
				$sx .= '<p>Crie uma página com o conteúdo em /var/www/dataverse/branding/custom-header.html</p><br>';
				$sx .= '<tt>nano /var/www/dataverse/branding/custom-header.html</tt><br>';
				$sx .= 'echo "See sample <a href="https://guides.dataverse.org/en/latest/_downloads/0f28d7fe1a9937d9ef47ae3f8b51403e/custom-homepage.html">homepage"</a>'.cr();
				$sx .= "<tt>curl -X PUT -d '/var/www/dataverse/branding/custom-header.html' http://localhost:8080/api/admin/settings/:HeaderCustomizationFile</tt>".cr();
				$sx .= '</pre>';
				$sx .= cr();
				$sx .= 'echo "Remove Custom Page"'.cr();
				$sx .= 'curl -X PUT -d \'/var/www/dataverse/branding/custom-header.html\' http://localhost:8080/api/admin/settings/:HeaderCustomizationFile'.cr();
				$PATH = '/var/www/dataverse/branding/';
				return $sx;
			}			

		function FooterCustomizationFile ()
			{
				$sx = '';
				$sx .= '<pre>';
				$sx .= 'mkdir /var/www/dataverse/'.cr();
				$sx .= 'mkdir /var/www/dataverse/branding/'.cr();
				$sx .= '</pre>';
				$sx .= '<p>Crie uma página com o conteúdo em /var/www/dataverse/branding/custom-footer.html</p><br>';
				$sx .= '<tt>nano /var/www/dataverse/branding/custom-footer.html</tt><br>';
				$sx .= 'echo "See sample <a href="https://guides.dataverse.org/en/latest/_downloads/0f28d7fe1a9937d9ef47ae3f8b51403e/custom-homepage.html">homepage"</a>'.cr();
				$sx .= "<tt>curl -X PUT -d '/var/www/dataverse/branding/custom-footer.html' http://localhost:8080/api/admin/settings/:FooterCustomizationFile</tt>".cr();
				$sx .= '</pre>';
				$sx .= cr();
				$sx .= 'echo "Remove Custom Page"'.cr();
				$sx .= 'curl -X PUT -d \'/var/www/dataverse/branding/custom-footer.html\' http://localhost:8080/api/admin/settings/:FooterCustomizationFile'.cr();
				$PATH = '/var/www/dataverse/branding/';
				return $sx;
			}

		function homepage()
			{
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
										$sx .= bsmessage('Delete file exists - '.$file2,3);
										unlink($file2);
									}

								move_uploaded_file($file,'/var/www/dataverse/branding/cnpq_homepage.html');
								$sx .= bsmessage('Uploaded - Move:' .$file.' to /var/www/dataverse/branding/css/custom-stylesheet.css');
							} else {
								$sx .= bsmessage('File not HTML - ['.$type.']',3);
							}
					}
				$sx = '<pre>'.$sx.'</pre>';

				$sx .= '<pre>';
				$sx .= 'mkdir /var/www/dataverse/'.cr();
				$sx .= 'mkdir /var/www/dataverse/branding/'.cr();
				$sx .= 'echo "See sample <a href="https://guides.dataverse.org/en/latest/_downloads/0f28d7fe1a9937d9ef47ae3f8b51403e/custom-homepage.html">homepage"</a>'.cr();
				$sx .= 'curl -X PUT -d \'/var/www/dataverse/branding/custom-homepage.html\' http://localhost:8080/api/admin/settings/:HomePageCustomizationFile'.cr();
				$sx .= '</pre>';
				$sx .= cr();
				$sx .= 'echo "Remove Custom Page"'.cr();
				$sx .= '<tt>curl -X DELETE http://localhost:8080/api/admin/settings/:HomePageCustomizationFile</tt>'.cr();
				$PATH = '/var/www/dataverse/branding/';

				return $sx;
			}

		function NavbarAboutUrl()
			{
				$sx = h(lang('dataverse.NavbarAboutUrl'));
				$sx .= '<p>Criação de uma página persolalizada sobre o Repositório Dataverse</p>';
				$cmd = '';
				$cmd .= 'mkdir /var/www/dataverse//branding'.cr();
				$cmd .= 'cd /var/www/dataverse//branding'.cr();
				$cmd .= 'cp &lt;diretório+pagina> about.html'.cr();
				$cmd .= 'curl -X PUT -d http://'.URL.'dvn/about/ http://localhost:8080/api/admin/settings/:NavbarAboutUrl'.cr();
				$sx .= '<pre>'.$cmd.'</pre>';

				$sx .= 'echo "Configurando o Apache para /drv/';
				$sx .= 'Criar o arquivo /etc/apache2/sites-available/dvn.conf com o comando:';
				$sx .= '<tt>nano /etc/apache2/sites-available/dvn.conf</tt><br>';
				$sx .= 'com o conteúdo:<br>';
				$sx .= '<pre>';
				$sx .= '&lt;VirtualHost *:88>'.cr();
				$sx .= 'ServerName localhost'.cr();
				$sx .= 'DocumentRoot /var/www/dataverse/branding'.cr();
				$sx .= '&lt;/VirtualHost>'.cr();
				$sx .= '</pre>'.cr();
				$sx .= 'Habilita o site no Apache:<br>';
				$sx .= '<tt>a2ensite dvn</tt>';

				$sx .= '<br>';
				$sx .= 'Redirecionar o Proxy no Apache:<br>';
				$sx .= 'Edite o arquivo (ou seu equivalente) /etc/apache2/sites-enabled/000-default.conf com o comando:<br>';
				$sx .= 'Incluindo as linhas:<br>';
				$sx .= '<pre>';
				$sx .= '&lt;Location "/dvn">'.cr();
                $sx .= '	Order Allow,Deny'.cr();
                $sx .= '	Allow from all'.cr();
                $sx .= '	ProxyPass http://127.0.0.1:88'.cr();
                $sx .= '	SetEnv force-proxy-request-1.0 1'.cr();
                $sx .= '	SetEnv proxy-nokeepalive 1'.cr();
				$sx .= '&lt;/Location>'.cr();
				$sx .= '</pre>';

				$sx .= '<br>';
				$sx .= 'Edite o arquivo /etc/apache2/ports.conf com o comando:<br>';
				$sx .= '<tt>nano /etc/apache2/ports.conf</tt><br>';
				$sx .= 'com o conteúdo:<br>';
				$sx .= '<tt>Listen 88</tt> (em uma linha nova abaixo de Listen 80)<br>';

				$sx .= '<br>';
				$sx .= 'Reiniciar o Apache:<br>';
				$sx .= '<tt>service apache2 restart</tt>';


				return $sx;
			}

		function css()
			{
				$sx = '';
				$sx .= h('dataverse.StyleCSS');
				$sx .= '<pre>curl -X PUT -d \'/var/www/dataverse/branding/custom-stylesheet.css\' http://localhost:8080/api/admin/settings/:StyleCustomizationFile</pre>';
				$sx .= '<hr>';
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
										$sx .= bsmessage('Delete file exists - '.$file2,3);
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
