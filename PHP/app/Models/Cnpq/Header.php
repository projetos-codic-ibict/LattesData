<?php

namespace App\Models\Cnpq;

use CodeIgniter\Model;

class Header extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'headers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [];

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


    function header()
        {
            $sx = '';
            $sx .= view('header/head');
            $sx .= '<body>';
            return $sx;
        }

    function footer()
        {
            $file = '/var/www/dataverse/branding/custom-footer.html';
            if (file_exists($file)) {
                $header = file_get_contents($file);
            } else {
                $file = '../../Dataverse/cnpq/branding/custom-footer.html';
                if (file_exists($file)) {
                    $header = file_get_contents($file); 
                } else {
                    $header = 'HEADER NOT FOUND';
                }
            }
            return $header;
        }        
}
