<?php

namespace App\Models\Dataverse;

use CodeIgniter\Model;

class Ingest extends Model
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
            switch($d1)
                {
                    case 'file':
                        $files = new \App\Models\Dataverse\Files();
                        $sx = $files->form();   
                        $file_name = get("file_name");
                        $doi = get("doi");

                        if (($file_name != '') and ($doi != ''))
                            {
                                $dataverse = $_SESSION['dataverse_url'];
                                $api_key = $_SESSION['dataverse_token'];
                                $sx = $files->DataverseFileAdd($dataverse,$api_key,$doi,$file_name);
                            }
                }
            return $sx;
        }
}
