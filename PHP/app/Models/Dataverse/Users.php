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

    function createUser()
        {
            /* \c codic                         */
            /* select * from authenticateduser; */

            echo "Create User"

            $us = array();
            $us['affiliation'] = 'UFRGS';
            $us['createdtime'] = date("Y-m-d H:i:s");
            $us['deactivated'] = false;
            //$us['deactivatedtime'] = '';
            $us['email'] = 'rene.gabriel@ufrgs.br';
            //$us['emailconfirmed'] = '';
            $us['firstname'] = 'Rene Faustino';
            //$us['lastapiusetime'] = '';
            $us['lastlogintime'] = date("Y-m-d H:i:s");
            $us['lastname'] = 'Gabriel Junior';
            $us['position'] = 'Professor';
            $us['superuser'] = false;
            $us['useridentifier'] = 'renefgjr';

            $this->insert($us);
        }
}
