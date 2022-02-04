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

    function createUser($dt)
        {
            /* \c codic                         */
            /* select * from authenticateduser; */

            /* CREATE USER lattesdata SUPERUSER INHERIT CREATEDB CREATEROLE;
            /* ALTER USER lattesdata PASSWORD 'senha'; */

            $nome = $dt['nomePessoa'];
            $nomep = nbr_author($nome,1);
     
            $firstname = mb_strtolower(substr($nomep,strpos($nomep,',')+1,strlen($nomep)));
            $lastname = mb_strtolower(substr($nomep,0,strpos($nomep,',')));
            $firstname = nbr_author($firstname,7);
            $lastname = nbr_author($lastname,7);

            $email = $dt['emailContato'];

            /***************** AFILIAÇÃO */
            $aff = (array)$dt['instituicoes'];
            if (isset($aff[0]))
                {
                    $affn = (array)$aff[0];
                    $sigla = $affn['siglaMacro'];
                    $inst = $affn['nomeMacro'];
                    if ($inst == '')
                        {
                            $sigla = $affn['sigla'];
                            $inst = $affn['nome'];
                        }
                } else {
                    $sigla = '';
                    $inst = '';
                }
            /**************** Identificadores */
            $aff = (array)$dt['identificadoresPessoa'];
            $ids = array();
            for ($r=0;$r < count($aff);$r++)
                {
                    $affn = (array)$aff[$r];
                    $idp_type = $affn['tipo'];
                    $idp_value = $affn['identificador'];
                    $ids[$idp_type] = $idp_value;
                }

            $us = array();
            $us['affiliation'] = $inst;
            $us['createdtime'] = date("Y-m-d H:i:s");
            $us['deactivated'] = false;
            //$us['deactivatedtime'] = '';
            $us['email'] = $email;
            //$us['emailconfirmed'] = '';
            $us['firstname'] = $firstname;
            //$us['lastapiusetime'] = '';
            $us['lastlogintime'] = date("Y-m-d H:i:s");
            $us['lastname'] = $lastname;
            $us['position'] = 'Professor';
            $us['superuser'] = false;
            $us['useridentifier'] = $ids['IDLATTES'];
            $us['new'] = false;

            $dt = $this->where('email',$us['email'])->findAll();
            if (count($dt) == 0)
                {
                    $this->insert($us);
                    $us['new'] = true;
                }
            return $us;            
        }
}
