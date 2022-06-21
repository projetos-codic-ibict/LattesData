<?php

namespace App\Models\LattesData\Header;

use CodeIgniter\Model;

class Headers extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = '*';
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

    function Index()
    {
        $sx = '<header>' . chr(13) . chr(10);
        $sx .= '<title>LattesData - Deposito de Datasets</title>' . chr(13) . chr(10);
        $sx .= '<script src="' . URL . '/dvn/jquery.js"></script>' . chr(13) . chr(10);
        $sx .= '<script src="' . URL . '/dvn/bootstrap.min.js"></script>' . chr(13) . chr(10);
        $sx .= '<link type="text/css" rel="stylesheet" href="' . URL . '/dvn/bootstrap.min.css" />' . chr(13) . chr(10);
        $sx .= '</header>' . chr(13) . chr(10);

        $files = array('custom-header.html', 'custom-stylesheet.css');

        for ($r = 0; $r < count($files); $r++) {
            $ff = $files[$r];
            $file = '/var/www/dataverse/branding/' . $ff;
            if (file_exists($file)) {
                $sx .= file_get_contents($file);
            } else {
                $file = '../../Dataverse/cnpq/branding/' . $ff;
                if (file_exists($file)) {
                    if (strpos($ff, '.css') > 0) {
                        $sx .= '<style>' . chr(13) . chr(10) . file_get_contents($file) . chr(13) . chr(10) . '</style>' . chr(13) . chr(10);
                    } else {
                        $sx .= file_get_contents($file) . chr(13) . chr(10);
                    }
                } else {
                    $sx .= 'HEADER NOT FOUND';
                }
            }
        }
        $sx .= '<div class="container" style="height:100px">asdasd</div>' . chr(13) . chr(10);
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
