<?php

namespace App\Models\LattesData;

use CodeIgniter\Model;

class LattesEmail extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'lattesdatas';
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

    function email_cadastro($email='',$dt=array())
        {
            $link = '';
			$senha = '';
            $sx = '';
            $sx .= '<center>';
			$sx .= '<div style="width: 100; background-color: #f4f4f4; padding: 40px 40px 100px 40px;">';
            $sx .= '<div style="width: 600px; border: 1px solid #555; padding: 10px; font-family: Tahoma, Arial">';
            //$sx .= '<img src="https://lattesdata.cnpq.br/logos/1/logo_lattesdata_mini.png">';
            $sx .= '<img src="cid:$image1">';
            $sx .= '<div style="text-align: left; font-size: 200%;">';
            $sx .= '<p>Prezado pesquisador,</p>';
            $sx .= '<p>Seu projeto foi cadastrado com sucesso no LattesData.</p>';
            $sx .= '<p>Para continuar o preenchimento dos metadados e realizar o depósito dos arquivos dos seus conjuntos de dados, insira o seu login e senha no seguinte endereço:</p>';
            $sx .= '<p>Acesso sua Comunidade e Dataset: '.$link.'</p>';
            $sx .= '<p>Usuário: '.$email.'</p>';
            $sx .= '<p>Senha de acesso: '.$senha.'</p>';
            $sx .= '</div>';
            $sx .= '</div>';
			$sx .= '</div>';
            $sx .= '</center>';
            return $sx;
        }

}