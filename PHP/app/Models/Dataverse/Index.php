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

	function index($d1,$d2,$d3,$d4,$d5='')
		{
			$sx = '';
			$sx = breadcrumbs();
			switch($d1)
				{
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
						$sx .= h(lang('dataverse.main_menu'),4);
						$sx .= $this->menu();
				}
			return $sx;
		}
	function menu()
		{
			if (strlen($this->server()))
			{
				$menu[PATH.MODULE.'dataverse/server'] = lang('dataverse.SetServer') . ': <b>'.$this->server().'</b>';
				$menu[PATH.MODULE.'dataverse/token'] = lang('dataverse.SetToken') . ': <b>'.$this->token().'</b>';
				$menu[PATH.MODULE.'dataverse/licences'] = lang('dataverse.Licences');
				$menu[PATH.MODULE.'dataverse/users_login'] = lang('dataverse.Users_login');
				$menu[PATH.MODULE.'dataverse/doi'] = lang('dataverse.DOI_settings');
				$menu[PATH.MODULE.'dataverse/customize'] = lang('dataverse.Customize');
				$menu[PATH.MODULE.'dataverse/email'] = lang('dataverse.Custom_Email');
				$menu[PATH.MODULE.'dataverse/solr'] = lang('dataverse.Solr');
				$menu[PATH.MODULE.'dataverse/settings'] = lang('dataverse.Settings');	
				$menu[PATH.MODULE.'dataverse/pa'] = lang('dataverse.PA');
				$menu[PATH.MODULE.'dataverse/pave'] = lang('dataverse.PA_External');
				$menu[PATH.MODULE.'dataverse/embargo'] = lang('dataverse.Embargo');
				$menu[PATH.MODULE.'dataverse/apache'] = lang('dataverse.Apache-Proxy');
			} else {
				$menu[PATH.MODULE.'dataverse/server'] = lang('dataverse.SetServerDefine');
			}
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
			$txt = (array)$txt['data'];
			$sa = '';
			foreach($txt as $key => $value)
				{
					$sa .= bsc($key,3,'text-end small fst-italic');
					$sa .= bsc($value,9);
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

		function email($d1,$d2,$d3)
		{
			$sx = h('dataverse.email',1);
			$sx .= h($d2,4);
			switch($d2)
				{
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
					default:
					$menu[PATH.MODULE.'dataverse/email/system_email'] = lang('dataverse.system_email');
					$menu[PATH.MODULE.'dataverse/email/google'] = lang('dataverse.system_email_google');
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
			
			return $sx;
		}		

	function solr($d1,$d2,$d3)
		{
			$Solr = new \App\Models\Dataverse\Solr();
			$sx = h('dataverse.Solr',1);
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
