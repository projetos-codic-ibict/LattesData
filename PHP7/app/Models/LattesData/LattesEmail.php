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

	function email_cadastro($dt = array())
	{
		$link = '<a href="https://lattesdata.cnpq.br/">https://lattesdata.cnpq.br/</a>';
		$senha = $dt['password'];
		$email = $dt['email'];
		$userName = $dt['userName'];
		//$sx = '<style> .texto_lattesdata { font-size: 200%; } </style>';
		$sx = '';
		$sx .= '<center>';
		$sx .= '<div style="width: 100; background-color: #f4f4f4; padding: 40px 40px 100px 40px;">';
		$sx .= '** Não responda esse e-mail **';
		$sx .= '<div style="width: 600px; border: 0px solid #555; padding: 10px; font-family: Tahoma, Arial; font-size: 18px;">';
		//$sx .= '<img src="https://lattesdata.cnpq.br/logos/1/logo_lattesdata_mini.png">';
		$sx .= '<img src="cid:$image1">';
		$sx .= '<div style="text-align: left;">';
		$sx .= '<p class="texto_lattesdata">Prezado pesquisador,</p>';
		$sx .= '<p class="texto_lattesdata">Seu projeto foi cadastrado com sucesso no LattesData.</p>';
		$sx .= '<p class="texto_lattesdata">Para continuar o preenchimento dos metadados e realizar o depósito dos arquivos dos seus conjuntos de dados, insira o seu login e senha no seguinte endereço:</p>';
		$sx .= '<p class="texto_lattesdata">Acesso sua Comunidade e Dataset: ' . $link . '</p>';
		$sx .= '<p class="texto_lattesdata">Usuário: ' . $userName . '</p>';
		$sx .= '<p class="texto_lattesdata">E-mail: ' . $email . '</p>';
		$sx .= '<p class="texto_lattesdata">Senha de acesso: ' . $senha . '</p>';
		$sx .= '</div>';
		$sx .= '</div>';
		$sx .= '</div>';
		$sx .= '</center>';
		return $sx;
	}

	function enviar($xemail, $txt, $ass)
	{
		$email = \Config\Services::email();
		$config['mailType'] = 'html';
		$email->initialize($config);
		$email->setFrom('lattesdata@app.ibict.br', 'LattesData');
		$email->setTo($xemail);
		$email->setSubject('[LattesData] Cadastro de Dataset');
		//                     
		$filename = 'img/bg-email-hL3a.jpg';
		if (file_exists($filename)) {
			$email->attach($filename);
			$cid = $email->setAttachmentCID($filename);
			$txt = troca($txt, '$image1', $cid);
		} else {
			echo "Logo not found";
		}
		$email->setMessage($txt);
		$email->send();
		print_r($email->printDebugger());
	}
}
