<?php

namespace App\Models\Lattes;

use CodeIgniter\Model;

class LattesExtrator extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'lattesextrators';
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

    function get($id)
        {
			$token = getenv('token_lattes');
			$url = getenv('url_lattes').$id;

            $ch = curl_init($url);

            curl_setopt_array($ch, [
                // Equivalente ao -X:
                CURLOPT_CUSTOMREQUEST => 'GET',
                // Equivalente ao -H:
                CURLOPT_HTTPHEADER => [
                    'auth-token: '.$token,
                ],
                // Permite obter o resultado
                CURLOPT_RETURNTRANSFER => 1,
            ]);

            $file = curl_exec($ch);
            curl_close($ch); 
            echo "=======";  
            echo $file;
            exit; 
        }    
}
