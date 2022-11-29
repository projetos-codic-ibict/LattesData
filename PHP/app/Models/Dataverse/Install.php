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
					case 'backup':
						$sx .= $this->backup();
						break;
					case 'reindex':
						$sx .= $this->reindex();
						break;
					case 'restore':
						$sx .= $this->restore();
						break;
					case 'upgrade':
						$sx .= $this->upgrade();
						break;
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
					case 'domain':
						$sx .= '<h1>Edit Domain.xml</h1>';
						$sx .= '<pre>nano /usr/local/payara5/glassfish/domains/domain1/config/domain.xml</pre>';
						$sx .= '<pre>&lt;jvm-options>-Ddataverse.siteUrl=http://${dataverse.fqdn}:8080&lt;/jvm-options></pre>';
						$sx .= 'para<br>&nbsp;';
						$sx .= '<pre>&lt;jvm-options>-Ddataverse.siteUrl=http://seudominio.com.br&lt;/jvm-options></pre>';
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
						$menu[PATH.MODULE.'dataverse/install/domain'] = lang('dataverse.DataverseDomain');

						$sx .= menu($menu);
				}
			return $sx;
		}
		function backup()
			{
				$sx = '';

				$sx .= '<h1>Postgres</h1>';
				$sx .= '
<p>Criar área para guardar os Backup</p>
<pre>
sudo su
echo "Cria pastas de destino Backup do Postgres"
export BACKUP=/home/dataverse/payara
mkdir $BACKUP/backup
mkdir $BACKUP/backup/postgres
chown postgres $BACKUP/backup/postgres

su postgres
cd $BACKUP/backup/postgres
pg_dump dvndb > dvndb.sql
exit
</pre>';

			$sx .= '<h1>Dataverse / Payara</h1>';
			$sx .= '
<pre>
export PAYARA=/usr/local/payara5/glassfish
echo "Iniciar Backup"
$PAYARA/bin/asadmin stop-domain
$PAYARA/bin/asadmin backup-domain --backupdir /home/dataverse/payara domain1
$PAYARA/bin/asadmin start-domain
ls $backup -ls
echo "Backup do Postgres finalizado
</pre>";
			';

				return $sx;
			}

		function restore()
			{
				$sx = '';
				$sx .= '<h1>Dataverse / Payara</h1>';
				$sx .= 'Remova o diretório domain1';
				$sx .= '
				<pre>

echo "#################### REMOVER A INSTALAÇÃO ATUAL DO PAYARA ###"
export PAYARA=/usr/local/payara5/glassfish
$PAYARA/bin/asadmin stop-domain
echo "Romovendo o diretório domain1"
rm -rf $PAYARA/domains/domain1

echo "#################### DESCOMPACTANDO ARQUIVO DE BACKUP ###"
export BACKUP=/home/dataverse/payara/domain1
export FILE=domain1_2022_11_28_v00001.zip
cd $BACKUP/
mkdir domain
cd domain
echo "Descompactando o backup"
unzip ../$FILE
cd ..

echo "#################### MOVENTO ARQUIVO ###"
export DESTINO=/usr/local/payara5/glassfish/domains/domain1
mkdir $DESTINO
mv domain/* $DESTINO
ls $DESTINO -ls

echo "#################### REMOVENDO BANCO DE DADOS ATUAL ###"
su postgres
psql
drop database dvndb;
\q

echo "#################### RESTAURANDO BANCO DE DADOS ###"
su postgres
psql dvndb < /home/dataverse/payara/dvndb.sql
exit

su postgres
psql -d dvndb
GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public to dvnapp;
GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public to dvnapp;
GRANT ALL PRIVILEGES ON ALL FUNCTIONS IN SCHEMA public to dvnapp;
\q


echo "#################### INICIANDO O PAYARA ###"
$PAYARA/bin/asadmin star-domain

				</pre>';

				$sx .= '<h1>Postgres</h1>';

				$sx .= '<h1>SOLR</h1>';

				return $sx;
			}

		function reindex()
			{
				$sx = '
<h1>Reindexando o SOLR</h1>
<pre>
curl http://localhost:8080/api/admin/index/status
curl http://localhost:8080/api/admin/index/clear-orphans
curl http://localhost:8080/api/admin/index/clear
curl http://localhost:8080/api/admin/index
curl -X DELETE http://localhost:8080/api/admin/index/timestamps
curl http://localhost:8080/api/admin/index/continue
</pre>
				';
				return $sx;
			}

		function upgrade()
			{
				$ver = '5.10.1';
				$sx = '<h1>Dataverse Upgrade</h1>';
				$sx .= h('GitHub - Dataversee',4);
				$sx .= '<p>Para atualizar o Dataverse siga a instruções descritas:</p>';
				$sx .= '<pre><a href="https://github.com/IQSS/dataverse/releases" target="_blank">https://github.com/IQSS/dataverse/releases</a></pre>';
				$sx .= '<p>Resumo dos comandos para atualização:</p>';
				$cmd = '';

				$cmd .= 'echo "Baixando a última versão atualizada"'.cr();
				$cmd .= 'cd /home/dataverse/install'.cr();
				$cmd .= 'wget https://github.com/IQSS/dataverse/releases/download/v'.$ver.'/dataverse-'.$ver.'.war'.cr();
				$cmd .= cr();

				$cmd .= 'echo "Verificando a versão instalada"'.cr();
				$cmd .= 'export PAYARA=/usr/local/payara5'.cr();

				$cmd .= 'echo "Mostrando a versão instaada"'.cr();
				$cmd .= '$PAYARA/bin/asadmin list-applications'.cr();
				$cmd .= '<span style="color: green">Resultado: <i>dataverse-5.10 &lt;ejb, health, metrics, openapi, web></i></span>'.cr();

				$cmd .= cr();
				$cmd .= 'echo "Removendo a versão instalada"'.cr();
				$cmd .= '$PAYARA/bin/asadmin undeploy dataverse<-version>'.cr();
				$cmd .= './stop'.cr();
				$cmd .= cr();

				$cmd .= 'rm -rf $PAYARA/glassfish/domains/domain1/generated'.cr();
				$cmd .= './start'.cr();
				$cmd .= 'echo "Instalando a nova versão"'.cr();
				$cmd .= '$PAYARA/bin/asadmin deploy dataverse'.$ver.'.war'.cr();
				$cmd .= './restart'.cr();

				$sx .= '<pre>'.$cmd.'</pre>';

				return $sx;
			}

}
