<?php

namespace App\Models\Dataverse;

use CodeIgniter\Model;

class Files extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = '*';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'id','file_name','doi'
	];
	protected $typeFields        = [
		'hidden','string:100','string:100'
	];

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

	function form()
		{
			$this->path = PATH.MODULE.'dataverse/ingest/file';
			$sx = form($this);
			return $sx;
		}

	function DataverseFileAdd($dataverse,$api_key,$ID='doi:10.5072/FK2/J8SJZB',$file)
		{
			$sx = '';
			$sx .= '<tt>';
			$sx .= "export API_TOKEN=".$api_key.cr();
			$sx .= "export FILENAME='$file'".cr();
			$sx .= "export SERVER_URL=$dataverse".cr();
			$sx .= "export PERSISTENT_ID=$ID".cr();

			$json = 'jsonData={"description":"My description.","directoryLabel":"data/subdir1","categories":["Data"],"restrict":"false"}';
			
			$sx .= 'curl -H X-Dataverse-key:$API_TOKEN -X POST -F file=@$FILENAME -F \'jsonData='.$json.'\' "$SERVER_URL/api/datasets/:persistentId/add?persistentId=$ID"'.cr();;

			$sx .= "curl -H X-Dataverse-key:$api_key -X POST -F file=@$file -F '$json' ";
			$sx .= ' "'.$dataverse.'/api/datasets/:persistentId/add?persistentId=doi:'.$ID.'"'.cr();;
			$sx .= '</tt>';

			$sx = troca($sx,cr(),'<br>');


			return $sx;
		}
}
