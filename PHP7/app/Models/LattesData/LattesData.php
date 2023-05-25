<?php

namespace App\Models\LattesData;

use CodeIgniter\Model;

class LattesData extends Model
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
	var $FileDayValid = 3;
	var $status = 0;
	var $alias = '';

	function create_user($proto, $dt)
	{
		$sx = bsicone('process') . ' Criando usuário';
		$du = $dt['identificadoresPessoa'];
		if (!isset($dt['nomePessoa'])) {
			$nome = 'sem nome da silva';
		} else {
			$nome = $dt['nomePessoa'];
		}
		$nomep = nbr_author($nome, 1);

		$firstname = mb_strtolower(substr($nomep, strpos($nomep, ',') + 1, strlen($nomep)));
		$lastname = mb_strtolower(substr($nomep, 0, strpos($nomep, ',')));
		$firstname = nbr_author($firstname, 7);
		$lastname = nbr_author($lastname, 7);

		$email = $dt['emailContato'];

		/***************** AFILIAÇÃO */
		$aff = (array)$dt['instituicoes'];
		if (isset($aff[0])) {
			$affn = (array)$aff[0];
			$sigla = $affn['siglaMacro'];
			$inst = $affn['nomeMacro'];
			if ($inst == '') {
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
		for ($r = 0; $r < count($aff); $r++) {
			$affn = (array)$aff[$r];
			$idp_type = $affn['tipo'];
			$idp_value = $affn['identificador'];
			$ids[$idp_type] = $idp_value;
		}

		$dv['firstName'] = $firstname;
		$dv['lastName'] = $lastname;
		$dv['userName'] = troca($email, '@', '-');
		$dv['affiliation'] = $inst;
		$dv['position'] = 'Research';
		$dv['email'] = $email;
		$dv['password'] = substr(md5($dv['firstName'].$dv['lastName'].'LattesData'),0,10);
		$sx = bsicone('process') . ' Criando Usuário Dataverse<br>';
		$sx .= CreateUser($dv);

		$LattesEmail = new \App\Models\LattesData\LattesEmail();
		$txt = $LattesEmail->email_cadastro($dv);
		$ass = '[LattesData] - Cadastro de Usuário';
		$email_copia = 'renefgj@gmail.com';

		/**************** Enviar e-mail */
		//$sx .= $LattesEmail->enviar($email, $txt, $ass);
		//$sx .= $LattesEmail->enviar($email_copia, $txt, $ass);
		return $sx;
	}

	function getContent($dt, $name)
	{
		$value = '';
		foreach ($dt as $field => $value) {
			if (is_array($value)) {
				$value = $this->getContent($value, $name);
				if ($value != '') {
					return $value;
				}
			} else {
				if (trim((string)$field) == trim($name)) {
					return $value;
				}
			}
		}
		return '';
	}
	/*************************************** CRIAR DATAVERSE */
	function create_dataverse_provinience($proc, $parent, $dt)
	{
		$parent = 'chamadasCNPQ';
		/* *********************/
		$chamada = $dt['chamada']['sigla'];
		$chamada_nome = $dt['chamada']['nome'];
		$alias = troca($chamada, ' ', '');
		$alias = troca($alias, '-', '');
		$alias = troca($alias, '/', '_');

		switch ($chamada) {
			default:
				$chamada .= ' - ' . $chamada_nome;
		}
		$dd['alias'] = $alias;
		$dd['name'] = $chamada;
		$contact[0]['contactEmail'] = 'lattesdata@cnpq.br';
		$dd['dataverseContacts'] = $contact;
		$dd['affiliation'] = 'CNPq';
		$dd['description'] = $chamada;
		$dd['dataverseType'] = 'LABORATORY';
		$sx = bsicone('process') . ' Criando Edital Dataverse';
		$sx .= '<br>' . CreateDataverse($dd, $parent);
		$this->alias = $dd['alias'];
		return $sx;
	}


	/*************************************** CRIAR DATAVERSE */
	function limpaTitle($n)
		{
			$sx = '';
			$n = ascii($n);

			for($r=0;$r < strlen($n);$r++)
				{
					$c = $n[$r];
					if (ord($c) <= 128)
						{
							$sx .= $c;
						}
				}

			return $sx;
		}
	function create_dataverse($proc, $parent, $dt)
	{
		$procX = substr($proc, 0, 4) . 'CNPq' . substr($proc, 4, strlen($proc));
		$PROTO = $this->getContent($dt, 'numeroProcesso');
		$dataverse_title = $this->getContent($dt, 'titulo');

		$dd['alias'] = $procX;
		$dd['name'] = $dataverse_title . ' (' . $PROTO . ')';
		$contact[0]['contactEmail'] = $this->getContent($dt, 'emailContato');
		$dd['dataverseContacts'] = $contact;
		$dd['affiliation'] = $this->getContent($dt['instituicoes'], 'nome');
		$dd['description'] = $this->getContent($dt['projeto'], 'resumo');
		$dd['dataverseType'] = 'LABORATORY';

		$sx = bsicone('process') . ' Criando Comunidade Dataverse';
		$dt = array();

		$rsp = CreateDataverse($dd, $parent);
		$sx .= '<br>' . $rsp;
		if (strpos($rsp,'already exists'))
			{
				$link = 'https://lattesdata.cnpq.br';
				$sx .= '<div class="alert alert-danger" role="alert">Conjunto de Dados (Dataset) já existe!<br>';
				$sx .= 'Acesse o LattesData com seu login e senha, caso tenha esquecido solicite reenvio de senha</p>';
				$sx .= '<a href="'.$link.'">'.$link.'</a>';
				$sx .= '</div>';
				$this->alias = $dd['alias'];
				$this->status = '100';
			} else {
				$this->alias = $dd['alias'];
				$this->status = '200';
			}
		return $sx;
	}
	function create_dataset($dt,$parent)
	{
		$DV = $this->getDataset($dt);
		$DV['alias'] = $dt['numeroProcesso'];

		pre($DV);

		$sx = bsicone('process') . ' Criando Conjunto de Dados<br>';
		$sx .= CreateDataset($DV,$this->alias);
		return $sx;
	}

function getDataset($dt, $user=0)
    {
        $DV = array();
        /* Protocolo */
        $DV['protocol'] = 'doi';
        $DV['authority'] = getenv('DOI');
        $DV['publisher'] = 'CNPq LattesData';

        $DV['publicationDate'] = date("Y-m-d");
        //$DV['metadataLanguage'] = "undefined";

        $projeto = (array)$dt['projeto'];

        /* Licence */
        //$DV['datasetVersion']['license']['name'] = 'CC BY';
        $DV['datasetVersion']['license']['name'] = 'CC BY 4.0';
        $DV['datasetVersion']['license']['uri'] = 'http://creativecommons.org/licenses/by/4.0';

        //$DV['authority'] = getenv('DOI');
        //$DV['identifier'] = troca($dt['numeroProcesso'],'/','');
        //$DV['identifier'] = substr($DV['identifier'],0,strpos($DV['identifier'],'-'));

        $DV['datasetVersion']['fileAccessRequest'] = false;

        /* Citation */
        $fld = array();

        /*************** Title */
        $title = $projeto['titulo'];
        $fields = array('typeName' => 'title', 'multiple' => false, 'value' => $title, 'typeClass' => 'primitive');
        array_push($fld, $fields);

        /********************* Authors */

        $name = $dt['nomePessoa'];
        $email = $dt['emailContato'];
        $author = array();
        $aff = 'Desconhecida';

		for ($z=0;$z < count($dt['instituicoes']);$z++)
			{
				$line = $dt['instituicoes'][$z];
				$aff = $line['nome'];
			}


        $auth = array();
        $auth['authorName']['typeName'] = 'authorName';
        $auth['authorName']['multiple'] = false;
        $auth['authorName']['typeClass'] = 'primitive';
        $auth['authorName']['value'] = $name;

        $auth['authorAffiliation']['typeName'] = 'authorAffiliation';
        $auth['authorAffiliation']['multiple'] = false;
        $auth['authorAffiliation']['typeClass'] = 'primitive';
        $auth['authorAffiliation']['value'] = $aff;

		$auth3 = array($auth);

        $fields = array('typeName' => 'author', 'multiple' => true, 'typeClass' => 'compound', 'value' => $auth3);
        array_push($fld, $fields);

        /********************** Contact */
        $auth = array();
        $auth['datasetContactName']['typeName'] = 'datasetContactName';
        $auth['datasetContactName']['multiple'] = false;
        $auth['datasetContactName']['typeClass'] = 'primitive';
        $auth['datasetContactName']['value'] = $name;

        $auth['datasetContactAffiliation']['typeName'] = 'datasetContactAffiliation';
        $auth['datasetContactAffiliation']['multiple'] = false;
        $auth['datasetContactAffiliation']['typeClass'] = 'primitive';
        $auth['datasetContactAffiliation']['value'] = $aff;

        $auth['datasetContactEmail']['typeName'] = 'datasetContactEmail';
        $auth['datasetContactEmail']['multiple'] = false;
        $auth['datasetContactEmail']['typeClass'] = 'primitive';
        $auth['datasetContactEmail']['value'] = $email;

        $auth3 = array($auth);
        $fields = array('typeName' => 'datasetContact', 'multiple' => true, 'typeClass' => 'compound', 'value' => $auth3);
        array_push($fld, $fields);

        /*************** dsDescription */
        $resumo = $projeto['resumo'];
        $abstact = array();
        $abstact['dsDescriptionValue']['typeName'] = 'dsDescriptionValue';
        $abstact['dsDescriptionValue']['multiple'] = false;
        $abstact['dsDescriptionValue']['typeClass'] = 'primitive';
        $abstact['dsDescriptionValue']['value'] = $resumo;

        $auth3 = array($abstact);
        $fields = array('typeName' => 'dsDescription', 'multiple' => true, 'typeClass' => 'compound', 'value' => $auth3);
        array_push($fld, $fields);

		/******************** Description Date */
		$date = date("Y-m-d");
        $fields = array('typeName' => 'dsDescriptionDate', 'multiple' => false, 'typeClass' => 'primitive', 'value' => $date);
		array_push($fld, $fields);

		$key = $dt['palavrasChave'];
		$key = troca($key,'.',';');
		$key = troca($key,',',';');
		$key = explode(";",$key);
		$keyws = array();
		for ($z=0;$z < count($key);$z++)
			{
				$term = trim($key[$z]);
				$term = nbr_title($term);
		        $keyw['keywordValue']['typeName'] = 'keywordValue';
		        $keyw['keywordValue']['multiple'] = false;
		        $keyw['keywordValue']['typeClass'] = 'primitive';
		        $keyw['keywordValue']['value'] = $term;
				array_push($keyws,$keyw);
			}
		$fields = array('typeName' => 'keyword', 'multiple' => true, 'typeClass' => 'compound', 'value' => $keyws);
   		array_push($fld, $fields);

		/* Vigência */
		$vg_ini = sonumero($dt['dataInicioVigencia']);
		$vg_fim = sonumero($dt['dataTerminoVigencia']);
		$vg_ini = substr($vg_ini,4,4).substr($vg_ini,2,2).substr($vg_ini,0,2);
		$vg_fim = substr($vg_fim,4,4).substr($vg_fim,2,2).substr($vg_fim,0,2);
        $fields = array('typeName' => 'timePeriodCoveredStart', 'multiple' => false, 'typeClass' => 'primitive', 'value' => $date);
		array_push($fld, $fields);
        $fields = array('typeName' => 'timePeriodCoveredEnd', 'multiple' => false, 'typeClass' => 'primitive', 'value' => $date);
		array_push($fld, $fields);
		//pre($fld);
        /*
			$abs3['dsDescriptionValue'] = array($abstact);
			$fields = array('typeName'=>'dsDescription','multiple'=>true,'typeClass'=>'compound','value'=>$abs3);
			array_push($fld,$fields);
			*/


        /*************** productionDate */
        /*
			$productionDate = sonumero($dt['dataInicioVigencia']);
			$productionDate = substr($productionDate,6,4).'-'.substr($productionDate,3,2).'-'.substr($productionDate,0,2);
			$fields = array('typeName'=>'productionDate','multiple'=>false,'value'=>$productionDate,'typeClass'=>'primitive');
			array_push($fld,$fields);
			*/


        /********************* subject */
        $key = $dt['palavrasChave'];
        $key = troca($key, ';', ',');
        $key = troca($key, '.', ',');
        $value = array('Medicine, Health and Life Sciences');
        $fields = array('typeName' => 'subject', 'multiple' => true, 'typeClass' => 'controlledVocabulary', 'value' => $value);
        array_push($fld, $fields);

        /* Depositor */
        $fields = array('typeName' => 'depositor', 'multiple' => false, 'typeClass' => 'primitive', 'value' => $name);
        array_push($fld, $fields);

        /* Depositor */
        $date = date("Y-m-d");
        $fields = array('typeName' => 'dateOfDeposit', 'multiple' => false, 'typeClass' => 'primitive', 'value' => $date);
        array_push($fld, $fields);

        /* Grant */
        $name = "CNPq";
        $fields = array('typeName' => 'grantNumberAgency', 'multiple' => false, 'typeClass' => 'primitive', 'value' => $name);
        array_push($fld, $fields);

        $name = $dt['numeroProcesso'];
        $fields = array('typeName' => 'grantNumberValue', 'multiple' => false, 'typeClass' => 'primitive', 'value' => $name);
        array_push($fld, $fields);


        $DV['datasetVersion']['metadataBlocks']['citation']['displayName'] = "Citation Metadata";
        $DV['datasetVersion']['metadataBlocks']['citation']['name'] = "citation";
        $DV['datasetVersion']['metadataBlocks']['citation']['fields'] = $fld;
        return $DV;
    }

	function sendemail_user()
	{
		$sx = bsicone('process') . ' Enviando e-mail para usuário<br>';
		//$sx .= '<font class="text-danger">Falha no envio (SMTP Erro)</font>';
		$sx .= '<font class="text-success">Sucesso no envio das informações</font>';
		return $sx;
	}

	function API_getFileCnpq($id)
	{
		if (strlen($id) == 11) {
			//$token = '1bff0ead-c76f-371e-8f47-e56d6b0a024c';
			//$url = "https://api.cnpq.br/lattes-data/v1/processos/".$id;
			$token = getenv('token_lattes');
			$url = getenv('url_lattes');

			/*********************************** BRAPCI */
			if (strpos($url, 'brapci')) {
				/***************** Brapci */
				$BrapciAPI = new \App\Models\LattesData\BrapciAPI();
				jslog('Extrator: LattesExtrator');
				$js = $BrapciAPI->get($id);
				if (strlen($js) > 0) {
					$file = $this->temp_file($id);
					file_put_contents($file, $js);
					jslog('Saved: ' . $file);
				} else {
					jslog('Erro save file: ' . $id);
				}
			} else {
				/***************** CNPQ */
				$LattesExtrator = new \App\Models\LattesData\LattesExtrator();
				jslog('Extrator: Brapci');
			}
			return true;
		} else {
			return false;
		}
	}

	function temp_file($id = '')
	{
		dircheck('.tmp');
		dircheck('.tmp/LattesData');
		$file = '.tmp/LattesData/' . $id . '.json';
		return $file;
	}

	function cachedAPI($id = '')
	{
		$file = $this->temp_file($id);

		if (file_exists($file)) {
			$dt = filemtime($file);
			$date1 = date_create(date("Y-m-d"));
			$date2 = date_create(date("Y-m-d", $dt));
			$diff = date_diff($date1, $date2);
			$dias = $diff->format("%a");

			if ($dias > $this->FileDayValid) {
				return false;
			}
			return true;
		}
		return false;
	}

	function dv($p = '')
	{
		$nr = substr($p, 4, 6);
		$ye = substr($p, 0, 4);
		$dv = substr($p, 10, 1);

		/***************************************************************************
		 * Ex: 309985/2013-7
		 * nr   | 3 | 0 | 9 | 9 | 8 | 5 | 2 | 0 | 1 | 3 |
		 * mult | 9 | 8 | 7 | 6 | 5 | 4 | 0 | 0 | 3 | 2 |
		 * soma |27 | 0 |63 |54 |40 |20 | 0 | 0 | 3 | 6 | => Soma: 213
		 *
		 * DV = $soma % 11 => 4
		 * DV = 11 - $soma - 11 (7)
		 * Se DV = 10 ou DV == 1 => DV = 0
		 ***************************************************************************/

		$m = array(9, 8, 7, 6, 5, 4, 0, 0, 3, 2);

		/**************** Campos inválidos */
		if (strlen($p) != 11) {
			//echo '<br>ERRO:' . $p;
			return false;
		}

		/*************** Prepara Número */
		$pp = $nr . $ye;
		$sum = 0;
		for ($r = 0; $r < strlen($pp); $r++) {
			$sum += round($pp[$r]) * $m[$r];
		}
		$DV = $sum % 11;
		$DV = 11 - $DV;
		if ($DV > 9) {
			$DV = 0;
		}
		if ($dv == $DV) {
			return true;
		} else {
			return false;
		}
	}

	function padroniza_processo($p = '')
	{
		///ex: CNPq processo 573710/2008-2
		//174760/2008-2
		//padronizado: 20085737102
		$dig = array(9, 8, 7, 6, 5, 4, 0, 0, 3, 2);
		if (strpos($p, '/') > 0) {
			$p = substr($p, strpos($p, '/') + 1, 4) .
				substr($p, 0, 6) .
				substr($p, strlen($p) - 1, 1);
		}

		/* Valida anos */
		$year = round(substr($p, 0, 4));
		$p = sonumero($p);
		$erro = 0;
		if (($year < 1980) and ($year > date("Y"))) {
			$erro = 1;
		}
		if (!$this->dv($p)) {
			$erro = 2;
		}
		return array($p, $erro);
	}

	function show_metadate($proc)
	{
		$proto = $proc[0];
		/******************** Recupera nome do arquivo */
		$cached = $this->cachedAPI($proto);

		/************************************ GET API CNPq */
		if (!$cached) {
			jslog('API CNPq ' . $proto);
			$file = $this->API_getFileCnpq($proto);
		} else {
			jslog('Cache CNPq ' . $proto);
		}
		$file = $this->temp_file($proto);
		if (file_exists($file)) {
			$dt = file_get_contents($file);
			$dt = json_decode($dt, true);
			$dt = (array)$dt;
		} else {
			echo "OPS - Arquivo não encontrado";
			echo '<br>' . $file;
			exit;
		}

		if (!isset($dt['numeroProcesso']))
			{
				$sx = bsmessage('Problema ao processar '.substr($proto,4,6).'/'.substr($proto,0,4).'-'.substr($proto,10,1));
				$sx .= file_get_contents($file);
				return $sx;
			}

		/************ Processo */
		$sx = '<span>Processo</span>' . chr(13);
		$sx .= '<p style="font-size: 150%"><b>' . $dt['numeroProcesso'] . '</b></p>' . chr(13);

		/************ Metadados */
		$sx .= '<span>Título do projeto</span>' . chr(13);
		$sx .= '<p style="font-size: 150%"><b>' . $dt['projeto']['titulo'] . '</b></p>' . chr(13);

		/************** Modalidade */
		$sx .= '<span>Modalidade</span>' . chr(13);
		$sx .= '<p style="font-size: 130%">' . $dt['modalidade']['codigo'] . ' - ' . $dt['modalidade']['nome'] . '</p>' . chr(13);

		/************** Chamda */
		$sx .= '<span>Chamada</span>' . chr(13);
		$sx .= '<p style="font-size: 130%">' . $dt['chamada']['sigla'] . ' - ' . $dt['chamada']['nome'] . '</p>' . chr(13);

		/************** Chamada */
		$email = $dt['emailContato'];
		$email_mask = substr($email, 0, strpos($email, '@'));
		$email_mask = substr($email, 0, strlen($email_mask) / 2) . str_repeat('*', strlen($email_mask) / 2);
		$email_domi = '@' . substr($email, strlen($email_mask) + 1);
		$email_domi = troca($email_domi,'@@','@');
		//$email_mask = str_repeat('*',strlen($email_mask));
		$sx .= '<span>Pesquisador Responsável</span>' . chr(13);
		$sx .= '<p style="font-size: 130%">' . $dt['nomePessoa'] . '<br/>' . chr(13);
		$sx .= 'e-mail: ' . $email_mask . $email_domi . '</p>' . chr(13);

		/************** Instituições */
		$sx .= '<span>Vinculo(s) institucional(is)</span>' . chr(13);
		$inst = array();
		for ($r = 0; $r < count($dt['instituicoes']); $r++) {
			$line = $dt['instituicoes'][$r];
			$inst_name = $line['nome'];
			if (strlen($line['sigla']) > 0) {
				$inst_name = $inst_name . ' (' . $line['sigla'] . ')';
			}
			$inst[$inst_name] = 1;
		}
		foreach ($inst as $nome => $ok)
			$sx .= '<p style="font-size: 130%">' . $nome . '</p>' . chr(13);

		$sx .= '<br/><br/>' . chr(13);

		$chk = md5($proto . date("Ymd"));
		$parent = 'lattesdata';
		if (isset($_POST['confirm'])) {
			$sx .= '<div style="font-size: 130%">';
			$sx .= $this->create_user($proto, $dt) . '<br>';
			$sx .= $this->create_dataverse_provinience($proto, '', $dt) . '<br>';
			$sx .= $this->create_dataverse($proto, $this->alias, $dt) . '<br>';
			switch ($this->status) {
				case '200':
					$sx .= $this->create_user($proto, $dt) . '<br>';
					$sx .= $this->create_dataset($dt,$this->alias) . '<br>';
					$sx .= $this->sendemail_user($proto) . '<br>';
					break;
			}
			$sx .= '</div>';
		} else {
			$sx .= form_open(URL);
			$sx .= form_hidden('process', $proto);
			$sx .= form_hidden('confirm', $chk);
			$sx .= form_submit('action', 'Confirmar criação do Dataset para depósito (Dataverse)', 'class="btn btn-primary"');
			$sx .= form_close();
		}

		$sx .= '<br/><br/>' . chr(13);

		return $sx;
	}

	function filename($process = '')
	{
		$file = ".tmp/datasets/dataset_" . $process . '.json';
		return $file;
	}
}
