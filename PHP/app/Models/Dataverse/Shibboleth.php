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

    function index($d1,$d2,$d3)
        {
			$sx = '';
			$sx .= $this->form();
			$sb = bsc('',8);
			$sa = bsc($this->menu(), 4);
            switch($d1)
                {
					case 'metadata':
						$sb = h('Metadata',2);
						$sb .= $this->metadata($d2, $d3);
						$sb = bsc($sb,8);
					break;

					case 'discofeed':
						$sb = h('DiscoFeed', 2);
						$sb .= $this->discofeed($d2, $d3);
						$sb = bsc($sb, 8);
					break;

					default:

                }
			$sx = bs(bsc($sx,12)).bs($sa.$sb);
            return $sx;
        }

	function samples()
		{
			$sx = '';
			$sx .= '<h1>Shibboleth</h1>';
			$sx .= '<h2>Metadata</h2>';
			$sx .= '<p>URL: '.base_url(PATH.'shibboleth/metadata').'</p>';
			$sx .= '<h2>DiscoFeed</h2>';
			$sx .= '<p>URL: '.base_url(PATH.'shibboleth/discofeed').'</p>';
			return $sx;
		}

	function metadata()
		{
			$url = $this->url(). 'Metadata';
			$sx = read_link($url);
			$sx = troca($sx,'<','&lt;');
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
		$sx .= form_input(array('name'=>'url','id'=>'url','class'=>'form-control','value'=>$vlr));
		$sx .= 'ex: https://vitrinedadosabertos.rnp.br/Shibboleth.sso/';
		$sx .= '<br>';
		$sx .= '<br>';
		$sx .= form_submit(array('name'=>'submit','value'=>'Submit'));
		$sx .= form_close();
		return $sx;
	}

	function menu()
		{
			$url = $this->url();
			if ($url != '')
				{
					$sx = '<ul>';
					$sx .= '<li>'.anchor(PATH.MODULE. 'dataverse/shibboleth/discofeed','DiscoFeed').'</li>';
					$sx .= '<li>'.anchor(PATH . MODULE . 'dataverse/shibboleth/metadata','Metadata').'</li>';
					$sx .= '</ul>';
				} else {
					$sx = '';
				}
			return $sx;
		}
}
