<?php

namespace App\Models\Dataverse;

use CodeIgniter\Model;

class Users extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'authenticateduser';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id','affiliation','createdtime',
        'deactivated','deactivatedtime','email',
        'emailconfirmed','firstname','lastapiusetime',
        'lastlogintime','lastname','position',
        'superuser','useridentifier'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];


    function createJson($dt)
    {
        $dd['firstName'] = '';
        $dd['lastName'] = '';
        $dd['userName'] = '';
        $dd['affiliation'] = '';
        $dd['position'] = 'Research';
        $dd['email'] = '';
        $json = json_encode($dd);
        echo $json;
        exit;
    }

    function createUser($us)
        {
            /*
            $us['firstName'] = $firstname;
            $us['lastName'] = $lastname;
            $us['userName'] = troca($email,'@','-');
            $us['affiliation'] = $inst;
            $us['position'] = 'Research';
            $us['email'] = $email;            
            */

            $json = json_encode($us);
            $pass = md5(date("YmdHis"));
            $KEUY = getenv('BUILTIN_USERS_KEY');
            $SERVER_URL = getenv('DATAVERSE_URL');

            $cmd = 'curl -d \''.$json.'\' -H "Content-type:application/json" ';
            $cmd .= '"'.$SERVER_URL.'/api/builtin-users?password='.$pass.'&key='.$KEUY.'"';
            echo '<pre>'.$cmd.'</pre>';
        }

   
}
