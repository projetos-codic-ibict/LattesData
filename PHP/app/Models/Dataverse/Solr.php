<?php

namespace App\Models\Dataverse;

use CodeIgniter\Model;

class Solr extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'solrs';
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

	function index($d1, $d2, $d3)
	{
		$sx = $d1;
		switch ($d1) {

			case 'install':
				$sx .= $this->installSolr();
				break;
			case 'schema':
				$sx = $this->readSchema($d2, $d3);
				break;

			case 'solrDV':
				$sx = $this->readDVSchema($d2, $d3);
				break;

			case 'schema_export':
				$sx = $this->updateSchema($d2, $d3);
				break;
			default:
				$menu[PATH . MODULE . 'dataverse/solr/install'] = 'dataverse.Solr.Install';
				$menu[PATH . MODULE . 'dataverse/solr/schema'] = 'dataverse.Solr.Schema.Read';
				$menu[PATH . MODULE . 'dataverse/solr/schema_export'] = 'dataverse.Solr.Schema.Export';
				$menu[PATH . MODULE . 'dataverse/solr/solrDV'] = 'dataverse.SolrDV';
				$menu[PATH . MODULE . 'dataverse/solr'] = 'dataverse.Solr';
				$sx .= menu($menu);
		}
		return $sx;
	}

	function installSolr()
		{
			$ver = '8.11.1';
			$dataverse_install = '/home/dataverse/install/dvinstall/';
		$sx = '<h2>Instalando Solr</h2>';
		$sx .= '<p>Instalando Solr</p>';
		$sx .= '<code>
		echo "Adicionando Usuário SOLR"<br>		
		<code>
		useradd solr -m</br><br>

		echo "Criando Diretório Solr"<br>
		mkdir /usr/local/solr<br><br>

		echo "Atribuindo permissão"<br>
		chown solr:solr /usr/local/solr</code><br><br>
		
		echo "Mudando para o usuário SOLR"<br>
		su solr<br>
		cd /usr/local/solr<br><br>
		echo "Baixando Solr"<br>
		wget https://archive.apache.org/dist/lucene/solr/'.$ver.'/solr-'.$ver.'.tgz<br>
		tar xvzf solr-'.$ver.'.tgz<br><br>
		echo "Acessando a pastar de instalação do SOLR"<br>
		cd solr-'.$ver.'<br><br>

		echo "Definindo as configurações Internas do SOLR"<br>
		cp -r server/solr/configsets/_default server/solr/collection1<br><br>

		echo "Copiando padrões de configuração do Dataverse para o SOLR"<br>
		cp '.$dataverse_install.'schema*.xml /usr/local/solr/solr-'.$ver.'/server/solr/collection1/conf<br>
		cp '.$dataverse_install.'solrconfig.xml /usr/local/solr/solr-'.$ver.'/server/solr/collection1/conf<br>
		echo "name=collection1" > /usr/local/solr/solr-8.11.1/server/solr/collection1/core.properties<br>
		</code><br>

		<h4>Configurando o Jetty</h4>
		<p>No arquivo jetty.xml alterar o parametro HedaerSize<br>
		<code>nano /usr/local/solr/solr-'.$ver.'/server/etc/jetty.xml</code>
		</p>

		<p>Alterar a linha<br>
		<code>&lt;Set name="requestHeaderSize">&lt;Property name="solr.jetty.request.header.size" default="<b>8192</b>" />&lt;/Set></code>
		</p>
		<p>para<br>
		<code>&lt;Set name="requestHeaderSize">&lt;Property name="solr.jetty.request.header.size" default="<b>102400</b>" />&lt;/Set></code>
		</p>
		
		<h4>Configurando os limits</h4>
		<p>
		<code>nano /etc/security/limits.conf</code>
		<br>&nbsp;
		Incluir os valores abaixo<br>
		<pre>
		solr soft nproc 65000
		solr hard nproc 65000
		solr soft nofile 65000
		solr hard nofile 65000
		</pre>
		</p>


		<p>Para testar: sudo bash solr-'.$ver.'/bin/install_solr_service.sh solr-'.$ver.'.tgz</p>
		<p>https://www.vultr.com/docs/install-apache-solr-on-ubuntu-20-04/</p>
		';


		$sx .= '<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;';

		return $sx;
		}

	function readDVSchema()
	{
		$dir = '/usr/local/solr/';
		$d = scandir($dir);

		foreach($d as $id=>$dire)
			{
				if (is_dir($dir.$dire))
					{
						$comp = 'server/solr/collection1/conf/';	
						$file = $dir.$dire.'/conf/schema.xml';
					}
			}

		pre($d);
	}

	function readSchema()
	{
		$Dataverse = new \App\Models\Dataverse\index();
		//$url = $Dataverse->server();
		$url = 'http://localhost:8080';
		$api = $url . '/api/admin/index/solr/schema';


		dircheck('../.tmp/');
		$file = '../.tmp/schema_dv.xml';

		$orig = file_get_contents($api);
		file_put_contents($file, $orig);
		$sx = 'Read schema from ' . $api . '<br>';

		$sx .= '<hr><pre>' . troca($orig, '<', '&lt;') . '</pre>';
		return $sx;
	}

	function updateSchema()
	{
		$dir = '../.tmp/';
		$file = $dir . 'schema_dv.xml';

		if (file_exists($file)) {
			echo "OK";
		} else {
			echo "ERRO2";
			exit;
		}

		$orig = file_get_contents($file);
		//$txt = troca($txt,'<','&lt;');
		//$orig = explode(chr(10),$orig);
		$orig_field = substr($orig, 0, strpos($orig, '---'));
		$orig_copy = substr($orig, strpos($orig, '---') + 5, strlen($orig_field));

		/******************************************************************** SOLR SCHEMA *****/
		$file = $dir . 'schema.xml';
		$solr = file_get_contents($file);

		/* SUBS */
		$txa = '<!-- Dataverse copyField from http://localhost:8080/api/admin/index/solr/schema -->';
		$txb = '<!-- End: Dataverse-specific -->';

		$solr_start = substr($solr, 0, strpos($solr, $txa) + strlen($txa)) . chr(10);
		$solr_end = substr($solr, strpos($solr, $txb), strlen($solr));

		/******************************* NEW SOLR FILE */
		$solr = $solr_start . $orig_copy . $solr_end;

		/*********************************************************** SOLR DATAVERSE SCHEMA *****/
		$file = $dir . 'schema_dv_mdb_fields.xml';
		$dv = '<fields>' . chr(10);
		$dv .= $orig_field;
		$dv .= '</fields>';

		/*********************************************************** RESULT ********************/
		dircheck($dir . 'solr');
		$file = $dir . 'solr\\schema.xml';
		file_put_contents($file, $solr);
		$file = $dir . 'solr\\schema_dv_mdb_fields.xml';
		file_put_contents($file, $dv);
		$sx = 'Exported';
		return $sx;
	}
}
