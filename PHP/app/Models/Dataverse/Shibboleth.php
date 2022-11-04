<?php

namespace App\Models\Dataverse;

use CodeIgniter\Model;

class Shibboleth extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'files';
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

	function index($d1, $d2 = '', $d3 = '')
	{
		echo "OK - $d1 - $d2 - $d3";

		$sx = '';
		$sa = $this->menu();
		$sb = '';

		switch ($d1) {
			case 'install.so':
				$sb = $this->install($d2);
				break;
			case 'keygen':
				$sb = $this->install_keygen($d2);
				break;
			case 'metadata':
				$sb = h('Metadata', 2);
				$sb .= $this->metadata($d2, $d3);
				$sb = bsc($sb, 8);
				break;

			case 'discofeed':
				$sb = h('DiscoFeed', 2);
				$sb .= $this->discofeed($d2, $d3);
				$sb = bsc($sb, 8);
				break;

			case 'test':
				$sb = '';
				$sb .= $this->form();
				$sx = bs(bsc($sx, 12)) . bs($sa . $sb);
				$sx .= bs(bsc($this->samples(), 12));
				break;

			default:

				break;
		}
		$sx = bs(bsc($sa, 4) . bsc($sb, 8));
		return $sx;
	}

	function install_ubuntu()
		{
			$url = PATH. 'home/dataverse/apache';
			$sx = '';
			$sx .= '<tt>apt install shibboleth-sp-utils</tt><br>';
			$sx .= '<tt>echo "Modulo Shibboleth do Apache</tt><br>';
			$sx .= '<tt>apt install libapache2-mod-shib</tt> ou<br>';
			$sx .= '<tt>apt install libapache2-mod-shib2</tt> ou<br>';
			$sx .= '<p>Configura o Apache conforme '.anchor($url,'CONFIG APACHE2'). '</p>';

			$sx .= '<hr>Para testar: https://venus.brapci.inf.br/Shibboleth.sso/DiscoFeed<hr>';


			return $sx;
		}

	function shibboleth_config()
		{
			$sx = '';
			$sx .= '<tt>shib-keygen</tt><br>';
			$sx .= '{
    "id":"shib",
    "factoryAlias":"shib",
    "enabled":true
}';
			$sx .= 'curl -X POST -H \'Content-type: application/json\' --upload-file shibAuthProvider.json http://localhost:8080/api/admin/authenticationProviders';
			$sx .= '<tt>Criar subpastas</tt><br>';
			$sx .= '<tt></tt>';
			$sx .= '
			<pre>
			{
    		"id":"shib",
    		"factoryAlias":"shib",
    		"enabled":true
			}
			</pre>';
			$sx .= 'Alterar o arquivo';
			$sx = '
			<pre>
			<ApplicationDefaults entityID="https://dataverse.example.edu/sp" REMOTE_USER="eppn" attributePrefix="AJP_">
			</pre>';

			$sx .= '
			 <pre>
			 <MetadataProvider type="XML" validate="true"
		        url="https://samltest.id/saml/idp"
		        backingFilePath="/etc/shibboleth/SAMLtest.xml">
			 </MetadataProvider>
			 </pre>

			 <pre>
			        <AttributeExtractor type="XML" validate="true" reloadChanges="false" path="attribute-map.xml"/>
					<AttributeResolver type="Query" subjectMatch="true"/>
					<AttributeFilter type="XML" validate="true" path="attribute-policy.xml"/>
					<CredentialResolver type="File" use="signing"
						key="sp-key.pem" certificate="cert/sp-cert.pem"/>
					<CredentialResolver type="File" use="encryption"
						key="sp-key.pem" certificate="cert/sp-cert.pem"/>
			</pre>

			 <MetadataProvider type="XML" validate="true"
        			url="https://samltest.id/saml/idp"
        			backingFilePath="/etc/shibboleth/cache/SAMLtest.xml">
			</MetadataProvider>

			';
		return $sx;
		}

	function install_keygen()
	{
		$url = URL . 'asset/keygen.zip';
		$sx = '';

		$sx .= 'Crie uma pasta no diretório Shibboleth<br>';
		$sx .= '<tt>mkdir /etc/shibboleth/cert</tt>';
		$sx .= '<br>';
		$sx .= '<br>';

		$sx .= '<p>Baixe o script gerador de KEYGEN para o Dataverse ' . anchor($url, 'KEYGEN.sh') . ' na pasta cert</p>';
		$sx .= '<tt> cd /etc/shibboleth/cert</tt><br>';
		$sx .= '<tt> wget '.$url. '</tt><br>';

		$sx .= '<br>';

		$sx .= '<p>Descompacte o arquivo keygen.zip</br>';
		$sx .= '<tt> unzip keygen.zip</tt>' . '</p>';

		return $sx;
	}

	function install($d2)
		{
			if ($d2 == '1') { return $this->install_ubuntu(); }

			$url = PATH . MODULE . 'dataverse/shibboleth/install.so/';
			$sx = 'Install';

			$sx .= '<ol class="bullet_round">';
			$sx .= '<li class="bullet_round"><a href="'.$url.'1'. '">Ubuntu 22.04 ou 20.04</a></li>';
			$sx .= '</ol>';

			$sx .= '
			<style>
			ol.bullet_round {
				list-style: none;
				counter-reset: my-awesome-counter;
				}
			li.bullet_round {
				counter-increment: my-awesome-counter;
				margin: 0.25rem;
			}
			li.bullet_round::before {
				content: counter(my-awesome-counter);
				background: #662974;
				width: 2rem;
				height: 2rem;
				border-radius: 50%;
				display: inline-block;
				line-height: 2rem;
				color: white;
				text-align: center;
				margin-right: 0.5rem;
				}
			</style>';
			return $sx;
		}

	function samples()
	{
		$sx = '';
		$sx .= '<h4>Samples</h4>';
		$sx .= '<span onclick="set(\'https://vitrinedadosabertos.rnp.br/Shibboleth.sso/\');">VitrineDadosAbertos</span>';
		$sx .= '<br><span onclick="set(\'https://vitrinedadosabertos-dev.rnp.br/Shibboleth.sso/\');">VitrineDadosAbertos-DEV</span>';
		$sx .= '<br><span onclick="set(\'https://idp3.cafeexpresso.rnp.br/idp/shibboleth\');">CafeExpresso IDP3</span>';
		$sx .= '<br><span onclick="set(\'https://cafe.ufra.edu.br/idp/shibboleth\');">UFRA</span>';
		$sx .= '<br><span onclick="set(\'https://samltest.id/saml/idp\');">SAMLteste</span>';

		$sx .= '<script>';
		$sx .= ' function set(v) {';
		$sx .= ' $("#url").val(v);';
		$sx .= ' }';
		$sx .= '</script>';
		return $sx;
	}

	function metadata()
	{
		$url = $this->url() . 'Metadata';
		$sx = read_link($url);
		$sx = troca($sx, '<', '&lt;');
		pre($sx);
		exit;
	}

	function discofeed()
	{
		$url = $this->url() . 'DiscoFeed';
		$sx = read_link($url);
		pre($sx);
		exit;
	}

	function url()
	{
		$vlr = get("url");
		if ($vlr != '') {
			$_SESSION['url_shib'] = $vlr;
		} else {
			if (isset($_SESSION['url_shib'])) {
				$vlr = $_SESSION['url_shib'];
			}
		}
		return $vlr;
	}

	function form()
	{
		$vlr = $this->url();
		$sx = form_open('');
		$sx .= h('url Shibboleth', 4);
		$sx .= form_input(array('name' => 'url', 'id' => 'url', 'class' => 'form-control', 'value' => $vlr));
		$sx .= 'ex: https://vitrinedadosabertos.rnp.br/Shibboleth.sso/';
		$sx .= '<br>';
		$sx .= '<br>';
		$sx .= form_submit(array('name' => 'submit', 'value' => 'Submit'));
		$sx .= form_close();
		return $sx;
	}

	function menu()
	{
		$menu['#Instalar'] = '';
		$menu[PATH. 'home/dataverse/shibboleth/install.so'] = lang('lattes.shibboleth.install');
		$menu[PATH . 'home/dataverse/shibboleth/keygen'] = lang('lattes.shibboleth.keygen');
		$sx = menu($menu);

		$url = $this->url();
		if ($url != '') {
			$sx .= '<ul>';
			$sx .= '<li>' . anchor(PATH . MODULE . 'dataverse/shibboleth/discofeed', 'DiscoFeed') . '</li>';
			$sx .= '<li>' . anchor(PATH . MODULE . 'dataverse/shibboleth/metadata', 'Metadata') . '</li>';
			$sx .= '</ul>';
		}
		return $sx;
	}
}
