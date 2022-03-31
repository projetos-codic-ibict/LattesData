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
					case 'solr':
						$sx = $this->solr($d2,$d3,$d4);
						break;
					case 'solrDV':
						$sx = $this->solrDV($d2,$d3,$d4);
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
					default:
						$sx = $this->menu();
				}
			return $sx;
		}
	function menu()
		{
			if (strlen($this->server()))
			{
				$menu['#C1'] = lang('dataverse.menu.1');
				$menu[PATH.MODULE.'dataverse/server'] = 'dataverse.SetServer' . ': <b>'.$this->server().'</b>';
				$menu[PATH.MODULE.'dataverse/token'] = 'dataverse.SetToken' . ': <b>'.$this->token().'</b>';
				$menu[PATH.MODULE.'dataverse/licences'] = 'dataverse.Licences';
				$menu[PATH.MODULE.'dataverse/solr'] = 'dataverse.Solr';
				$menu[PATH.MODULE.'dataverse/solrDV'] = 'dataverse.SolrDV';				

				$menu[PATH.MODULE.'dataverse/pa'] = lang('dataverse.PerfilApplication');


			} else {
				$menu[PATH.MODULE.'dataverse/server'] = 'dataverse.SetServer';
			}
			$sx = menu($menu);
			return $sx;
		}
	function licences($d1,$d2,$d3)
		{
			$Licences = new \App\Models\Dataverse\Licences();
			$sx = h('dataverse.Licences',1);
			$sx .= $Licences->getLicences($d1,$d2,$d3);
			return $sx;
		}

	function solr($d1,$d2,$d3)
		{
			$Solr = new \App\Models\Dataverse\Solr();
			$sx = h('dataverse.Solr',1);
			$sx .= $Solr->index($d1,$d2,$d3);
			return $sx;
		}	
		
	function solrDV($d1,$d2,$d3)
		{
			$Solr = new \App\Models\Dataverse\Solr();
			$sx = h('dataverse.Solr',1);
			$sx .= $Solr->readDVSchema($d1,$d2,$d3);
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
