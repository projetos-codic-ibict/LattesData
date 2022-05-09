<?php

namespace App\Models\Dataverse;

use CodeIgniter\Model;

class Index extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'indices';
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

	function java()
		{
			$sx = breadcrumbs();
			$sx .= '<h2>Java</h2>';
			$sx .= '<br>';
			$sx .= '<p>Debian</p>';
			$sx .= '<code>apt update<br>';
			$sx .= '<code>sudo yum install java-11-openjdk</code>';
			$sx .= '<br>';
			$sx .= '<br>';
			$sx .= '<p>Ubuntu 20.04</p>';
			$sx .= '<code>apt install default-jdk</code>';
			$sx .= '<br>';
			$sx .= '<br>';
			$sx .= '<p>Ver a versão instalada</p>';
			$sx .= '<code>java -version</code>';
			$sx .= '<code>openjdk version "11.0.14" 2022-01-18<br>
			OpenJDK Runtime Environment (build 11.0.14+9-post-Debian-1deb10u1)<br>
			OpenJDK 64-Bit Server VM (build 11.0.14+9-post-Debian-1deb10u1, mixed mode, sharing)</code>';
			return $sx;
		}

		function payara()
		{
			$sx = breadcrumbs();
			$sx .= '<h2>Payara</h2>';
			$sx .= '<br>';
			$sx .= '<p>Ubuntu 20.04</p>';
			$sx .= '
			<code>
			echo "Create User Dataverse"<br>
			useradd dataverse -m<br>
			<br>
			echo "Accesse Dataverse Home Directory"<br>
			cd /home/dataverse<br>
			mkdir /home/dataverse/install<br>
			<br>
			echo "Download Payara"<br>
			wget https://s3-eu-west-1.amazonaws.com/payara.fish/Payara+Downloads/5.2021.6/payara-5.2021.6.zip<br>
			unzip payara-5.2021.6.zip<br>
			mv payara5 /usr/local<br>
			<br>
			echo "Change Permissions"<br>
			chown -R root:root /usr/local/payara5<br>
			chown dataverse /usr/local/payara5/glassfish/lib<br>
			chown -R dataverse:dataverse /usr/local/payara5/glassfish/domains/domain1<br>
			</code>

			&nbsp;<br>
			<h4>Service Payara</h4>
			<p>Para ativar o Payara como serviço no Ubuntu</p>
			<code>
			export PAYARA=/usr/local/payara5/glassfish/bin/<br>
			$PAYARA/asadmin create-service --serviceuser payaraadmin domain1<br>
			systemctl daemon-reload<br>
			systemctl start payara_domain1.service<br>
			systemctl enable payara_domain1.service
			</code>
			<br>&nbsp;
			<code>
			Comandos do serviço<br>
			service payara_domain1 status<br>
			service payara_domain1 start<br>
			service payara_domain1 stop<br>
			service payara_domain1 restart<br>
			</code>
			<br>&nbsp;
			<p>Source: <a href="https://blog.payara.fish/running-payara-server-as-a-service-added-support-for-systemd-on-linux">https://blog.payara.fish/running-payara-server-as-a-service-added-support-for-systemd-on-linux</a></p>
			<br>&nbsp;
			<br>&nbsp;';
			return $sx;
		}
		
	function postgres()
		{
			$sx = '';
			$sx .= '<h4>Para instalar no Ubuntu</h4>';
			$sx .= '<code>apt install postgresql</code>';
			$sx .= '<br>&nbsp;';			
			$sx .= '<p>Alterar a linhha</p>';
			$sx .= '<code>nano /etc/postgresql/<b>11</b>/main/postgresql.conf</code>';
			$sx .= '<pre>
			#listen_addresses = \'localhost\' 
    		<i><b>para </b></i>
			listen_addresses = \'*\' libera para todas as conexões
			port = 5432</pre>';

			$sx .= '<p>e no arquivo pg_hba alterar a linhha</p>';
			$sx .= '<code>nano /etc/postgresql/<b>11</b>/main/pg_hba.conf</code>';
			$sx .= '<pre>
			host    all             all             127.0.0.1/32            md5
    		<i><b>para </b></i>
			host    all             all             127.0.0.1/32            trust
			</pre>';

			return $sx;
		}

	function index($d1,$d2,$d3,$d4,$d5='')
		{
			$sx = '';
			$sx = breadcrumbs();
			switch($d1)
				{
					case 'r':
						$sx .= '<code>
						sudo apt install r-base
						</code>
						
						<pre>
						install.packages("R2HTML", repos="https://cloud.r-project.org/", lib="/usr/lib64/R/library" )
						install.packages("rjson", repos="https://cloud.r-project.org/", lib="/usr/lib64/R/library" )
						install.packages("DescTools", repos="https://cloud.r-project.org/", lib="/usr/lib64/R/library" )
						install.packages("Rserve", repos="https://cloud.r-project.org/", lib="/usr/lib64/R/library" )
						install.packages("haven", repos="https://cloud.r-project.org/", lib="/usr/lib64/R/library" )
						</pre>
						';
						break;
					case 'jq':
						$sx .= '<code>
						 apt install jq<br>
						<br><i>ou</i><br><br>
						cd /usr/bin<br>
						wget http://stedolan.github.io/jq/download/linux64/jq<br>
						chmod +x jq<br>
						jq --version<br>
						</code>';
						break;					
					case 'imagemagick':
						$sx .= '<code>apt install imagemagick</code>';
						break;
					case 'postgres':
						$sx .= $this->postgres();
						break;
					case 'java':
						$sx .= $this->java();
						break;
					case 'payara':
						$sx .= $this->payara();
						break;						
					case 'install':
						$Install = new \App\Models\Dataverse\Install();
						$sx = $Install->index($d2,$d3,$d4,$d5);
						break;	
						
					case 'ingest':
						$Ingest = new \App\Models\Dataverse\Ingest();
						$sx = $Ingest->index($d2,$d3,$d4,$d5);
						break;						

					case 'external_tools':
						$Dataview = new \App\Models\Dataverse\Dataview();
						$sx = $Dataview->index($d2,$d3,$d4,$d5);
						break;
					case 'pa':
						$PA_Schema = new \App\Models\Dataverse\PA_Schema();
						$sx = $PA_Schema->index($d2,$d3,$d4,$d5);
						break;
					case 'pave':
						$PA_SchemaExternal = new \App\Models\Dataverse\PA_SchemaExternal();
						$sx = $PA_SchemaExternal->index($d2,$d3,$d4,$d5);
						break;							
					case 'embargo':
						$Embargo = new \App\Models\Dataverse\Embargo();
						$sx = $Embargo->index($d2,$d3,$d4,$d5);
						break;	
					case 'checklist':
						$Checklist = new \App\Models\Dataverse\Checklist();
						$sx .= $Checklist->index($d2,$d3,$d4);
						break;																
					case 'customize':
						$Customize = new \App\Models\Dataverse\Customize();
						$sx .= $Customize->index($d2,$d3,$d4);
						break;					
					case 'doi':
						$DOI = new \App\Models\Dataverse\DOI();
						$sx = $DOI->index($d2,$d3,$d4);
						break;
					case 'solr':
						$sx = $this->solr($d2,$d3,$d4);
						break;
					case 'token':
						$sx .= $this->setToken();
						break;						
					case 'server':
						$sx .= $this->setURL();
						break;
					case 'system':
						$sx .= $this->system();
						break;						
					case 'licences':
						$sx .= $this->licences($d1,$d2,$d3,$d4);
						break;
					case 'settings':
						$sx .= $this->settings($d1,$d2,$d3,$d4);
						break;
					case 'apache':
						$sx .= $this->apache($d1,$d2,$d3,$d4);
						break;
					case 'email':
						$sx .= $this->email($d1,$d2,$d3,$d4);
						break;						
					case 'users_login':
						$sx .= $this->users_login($d1,$d2,$d3,$d4);
						break;
					default:
						$sb = $this->show_structure();
						$sb .= $this->dir_structure();

						$sa = h(lang('dataverse.main_menu'),4);
						$sa .= $this->menu();
						

						$sx = bsc($sa,3).bsc($sb,9);
				}
			return $sx;
		}
	function dir_structure()
		{
			$sx = '';
			$sx .= '<ul style="list-style:none;">';
			$sx .= '<li>/var/www/dataverse/</li>';
			$sx .= '<li>/var/www/dataverse/build</li>';
			$sx .= '</ul>';
			return $sx;
		}
	function show_structure()
		{
			$sx = '<img src="'.URL.'img/structure/diagram.png'.'" class="img-fluid">xx';
			return $sx;
		}
	function menu()
		{

				$menu['#INSTALL'] = '<h5><b>'.lang('dataverse.DataverseInstall').'</b></h5>';
				$menu[PATH.MODULE.'dataverse/install'] = lang('dataverse.DataverseInstalling');
				$menu[PATH.MODULE.'dataverse/install/upgrade'] = lang('dataverse.DataverseUpgrade');

				$menu['#SETTINGS'] = '<h5><b>'.lang('dataverse.Settings').'</b></h5>';
				$menu[PATH.MODULE.'dataverse/server'] = lang('dataverse.SetServer') . ': <b>'.$this->server().'</b>';
				$menu[PATH.MODULE.'dataverse/token'] = lang('dataverse.SetToken') . ': <b>'.$this->token().'</b>';

				$menu['#CHECKLIST'] = '<h5><b>'.lang('dataverse.Checklist').'</b></h5>';
				$menu[PATH.MODULE.'dataverse/checklist'] = lang('dataverse.Checklist');

				
				$menu[PATH.MODULE.'dataverse/system'] = lang('dataverse.Custom_system');

				$menu['#S'] = '<h5><b>'.lang('dataverse.System').'</b></h5>';
				$menu[PATH.MODULE.'dataverse/email'] = lang('dataverse.Custom_Email');
				$menu[PATH.MODULE.'dataverse/solr'] = lang('dataverse.Solr');
				$menu[PATH.MODULE.'dataverse/settings'] = lang('dataverse.Settings');	
				$menu[PATH.MODULE.'dataverse/licences'] = lang('dataverse.Licences');
				$menu[PATH.MODULE.'dataverse/users_login'] = lang('dataverse.Users_login');
				$menu[PATH.MODULE.'dataverse/doi'] = lang('dataverse.DOI_settings');
				$menu[PATH.MODULE.'dataverse/apache'] = lang('dataverse.Apache-Proxy');

				$menu['#P'] = '<h5><b>'.lang('dataverse.Parametrizations').'</b></h5>';
				$menu[PATH.MODULE.'dataverse/embargo'] = lang('dataverse.Embargo');

				$menu['#C'] = '<h5><b>'.lang('dataverse.Gadget').'</b></h5>';
				$menu[PATH.MODULE.'dataverse/customize'] = lang('dataverse.Customize');
				$menu[PATH.MODULE.'dataverse/external_tools'] = lang('dataverse.ExternalTools');
				$menu[PATH.MODULE.'dataverse/pa'] = lang('dataverse.PA');
				$menu[PATH.MODULE.'dataverse/pave'] = lang('dataverse.PA_External');	

				$menu['#INGEST'] = '<h5><b>'.lang('dataverse.Ingest').'</b></h5>';
				$menu[PATH.MODULE.'dataverse/ingest/file'] = lang('dataverse.Ingest_File');

			$sx = menu($menu);
			return $sx;
		}
	function settings()
		{
			$sx = '';
			$url = $this->server();
			$url .= '/api/admin/settings';
			$sx .= bs(bsc('curl '.$url,12));
			$txt = read_link($url);
			$txt = (array)json_decode($txt);
			if (isset($txt['data']))
			{
				$txt = (array)$txt['data'];
				$sa = '';
				foreach($txt as $key => $value)
					{
						$sa .= bsc($key,3,'text-end small fst-italic');
						$sa .= bsc($value,9);
					}
			} else {
				$sa = bsmessage('Erro de acesso '.$url,3);
				$sa .= anchor(PATH.MODULE.'dataverse/server',lang('dataverse.Settings'));
			}
			$sx .= bs($sa);
			return $sx;
		}
	function licences($d1,$d2,$d3)
		{
			$Licences = new \App\Models\Dataverse\Licences();
			$sx = h('dataverse.Licences',1);
			$sx .= $Licences->getLicences($d1,$d2,$d3);
			return $sx;
		}

	function system()
		{
			$sx = '
			cd /home/dataverse/<br>
			nano start<br>

			export PAYARA=/usr/local/payara5/glassfish<br>
			echo "Starting Payara 5..."<br>
			$PAYARA/bin/asadmin start-domain<br>
			<br>
			<hr>
			nano stop<br>
			export PAYARA=/usr/local/payara5/glassfish<br>
			echo "PAYARA Stoping..."<br>
			$PAYARA/bin/asadmin stop-domain<br>
			<br>
			<hr>
			nano restart<br>
			echo "Restarting Payara...."<br>
			/home/dataverse/stop<br>
			/home/dataverse/start<br>
			<br>
			<hr>
			chmod 777 start<br>
			chmod 777 stop<br>
			chmod 777 restart<br>
			';
			return $sx;
		}

		function email($d1,$d2,$d3)
		{
			$sx = h('dataverse.email',1);
			$sx .= h($d2,4);
			switch($d2)
				{
					case 'sendnotification':
						$sx .= '<p>Habilita notificações por e-mail</p>';
						$sx .= '<code>e>curl -X PUT -d true http://localhost:8080/api/admin/settings/:SendNotificationOnDatasetCreation</code>';
						break;
					case 'system_email':
						$sx .= '<p>Para nomear o Replay do e-mail.</p>';
						$sx .= '<code>curl -X PUT -d \'LattesData &lt;lattesdata@cnpq.br>\' http://localhost:8080/api/admin/settings/:SystemEmail</code>';
						$sx .= '<p>e também no arquivo domain.xml verificar o siteUrl</p>';
						$sx .= '<code>nano /usr/local/payara5/glassfish/domains/domain1/config/domain.xml</code>';
						$sx .= '<p>Insira sua URL</p>';
						$sx .= '<code>&lt;jvm-options>-Ddataverse.siteUrl=<b>https://lattesdata.cnpq.br.br</b>&lt;/jvm-options></code>';
						break;
					case 'google':
						$sx .= '<p>Para nomear o Replay do e-mail.</p>';
						$sx .= '<p:No arquivo de configuração do servidor domain.xml remova os parametros do mail-resourse e substitua pelos abaixo.</p>';
						$sx .= '
						<code>nano /usr/local/payara5/glassfish/domains/domain1/config/domain.xml</code>
						<code>
						&lt;mail-resource auth="false" host="smtp.gmail.com" from="app.email.dvn@gmail.com" user="app.email.dvn@gmail.com" jndi-name="mail/notifyMailSession"><br>
						&lt;property name="mail.smtp.port" value="465">&lt;/property><br>
						&lt;property name="mail.smtp.socketFactory.fallback" value="false">&lt;/property><br>
						&lt;property name="mail.smtp.socketFactory.port" value="465">&lt;/property><br>
						&lt;property name="mail.smtp.socketFactory.class" value="javax.net.ssl.SSLSocketFactory">&lt;/property><br>
						&lt;property name="mail.smtp.auth" value="true">&lt;/property><br>
						&lt;property name="mail.smtp.password" value="<b>asdkgqogvyecineuzxdtge</b>">&lt;/property><br>
					    &lt;/mail-resource>						
						</code>';
						break;
					case 'faq':
						$sx .= '<p>Problemas com e-mail.</p>';
						$sx .= '<code>./asadmin get server.resources.mail-resource.mail/notifyMailSession.host</code>';
						$sx .= anchor('./asadmin get server.resources.mail-resource.mail/notifyMailSession.host');
						break;
					default:
					$menu[PATH.MODULE.'dataverse/email/system_email'] = lang('dataverse.system_email');
					$menu[PATH.MODULE.'dataverse/email/google'] = lang('dataverse.system_email_google');
					$menu[PATH.MODULE.'dataverse/email/sendnotification'] = lang('dataverse.system_SendNotification');
					$menu[PATH.MODULE.'dataverse/email/faq'] = lang('dataverse.system_faq');
					
					$sx .= menu($menu);		
				}
			return $sx;
		}		

	function users_login($d1,$d2,$d3)
		{
			$sx = h('dataverse.Users_login',1);
			$sx .= h($d2,4);
			switch($d2)
				{
					case 'builtinUsers':
						$sx .= '<p>Para criar uma senha que possibilita criar usuários no sistema viar localhost.</p>';
						$sx .= '<code>curl -X PUT -d <b>builtInS3kretKey</b> http://localhost:8080/api/admin/settings/BuiltinUsers.KEY</code>';
						break;
					case 'allowsignUp':
						$sx .= '<p>Habilida ou desabilita a criação de usuários via UI.</p>';
						$sx .= '<code>curl -X PUT -d \'false\' http://localhost:8080/api/admin/settings/:AllowSignUp</code>';
						break;
					default:
					$menu[PATH.MODULE.'dataverse/users_login/builtinUsers'] = lang('dataverse.BuiltinUsers');
					$menu[PATH.MODULE.'dataverse/users_login/allowsignUp'] = lang('dataverse.AllowSignUp');
					$sx .= menu($menu);		
				}
			return $sx;
		}

		function apache($d1,$d2,$d3)
		{
			$sx = h('dataverse.Apache2',1);
			$sx .= 'PROXY para apache';
			
			$code = '
			<Location />
					ProxyPass http://localhost:8080/
					SetEnv force-proxy-request-1.0 1
					SetEnv proxy-nokeepalive 1
			</Location>

			<Location /config>
					ProxyPass http://localhost:81
					SetEnv force-proxy-request-1.0 1
					SetEnv proxy-nokeepalive 1
			</Location>';

			$code .= cr();
			$code .= '
			<VirtualHost *:81>
				ServerAdmin renefgj@gmail.com
				ServerName pocdadosabertos.inep.rnp.br
				ServerAlias 20.197.236.31
				DocumentRoot /data/LattesData/PHP/public
				<Directory "/data/LattesData/PHP/public">
					Require all granted
				</Directory>
			</VirtualHost>
			';
			$code = troca($code,'<','&lt;');
			$code = troca($code,chr(13),'<br>');
			$sx .= '<tt>'.$code.'</tt>';

			$sx .= '<pre>
			# Example commands that demonstrate how to run Payara Server on the "special" ports < 1024
			#
			# iptables -t nat -A PREROUTING -p tcp -m tcp --dport 80 -j REDIRECT --to-ports 8080
			# iptables -t nat -A PREROUTING -p udp -m udp --dport 80 -j REDIRECT --to-ports 8080
			</pre>';
			
			return $sx;
		}		

	function solr($d1,$d2,$d3)
		{
			$Solr = new \App\Models\Dataverse\Solr();
			$sx = breadcrumbs();
			$sx .= h('dataverse.Solr',1);
			$sx .= $Solr->index($d1,$d2,$d3);
			return $sx;
		}		

	function setURL()
		{
			$sx = form_open();
			$sx .= '<span class="small">'.lang('dataverse.url').'</span>';
			$sx .= '<input type="text" name="url" value="'.$this->server().'" class="form-control">';
			$sx .= '<input type="submit" value="'.lang('dataverse.save').'" class="btn btn-outline-primary">';
			$sx .= form_close();

			$url = get("url");
			if ($url != '')
				{
					$this->server($url);
					$sx .= metarefresh(PATH.MODULE.'dataverse/');					
				}
			return $sx;
		}

	function setToken()
		{
			$sx = form_open();
			$sx .= '<span class="small">'.lang('dataverse.token').'</span>';
			$sx .= '<input type="text" name="token" value="'.$this->token().'" class="form-control">';
			$sx .= '<input type="submit" value="'.lang('dataverse.save').'" class="btn btn-outline-primary">';
			$sx .= form_close();

			$token = get("token");
			if ($token  != '')
				{
					$this->token($token);
					$sx .= metarefresh(PATH.MODULE.'dataverse/');					
				}
			return $sx;
		}		

	function token($url='')
		{
			if ($url != '')
				{
					$_SESSION['dataverse_token'] = $url;
					return $url;
				}
			if (isset($_SESSION['dataverse_token']))
				{
					$url = $_SESSION['dataverse_token'];
				} else {
					$url = '';
				}
			return $url;
		}

	function server($url='')
		{
			if ($url != '')
				{
					$_SESSION['dataverse_url'] = $url;
					return $url;
				}
			if (isset($_SESSION['dataverse_url']))
				{
					$url = $_SESSION['dataverse_url'];
				} else {
					$url = '';
				}
			return $url;
		}
}
