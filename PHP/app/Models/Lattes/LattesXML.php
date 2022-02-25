<?php

namespace App\Models\Lattes;

use CodeIgniter\Model;

class LattesXML extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'xmls';
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

	function xml($lattes = '')
	{
		$LattesProducao = new \App\Models\Lattes\LattesProducao();

		clog('Harvesting XML');
		$dir = '.tmp/lattes/';
		$file = $dir . '/' . $lattes . '.xml';

		if (file_exists($file)) {
			clog('Harvesting XML - Load File');
			$xml = simplexml_load_file($file);
		} else {
			clog('Harvesting XML - Import from CNPq');
			$this->LattesLoad($lattes, $lattes);
			$xml = simplexml_load_file($file);
		}
		clog('Harvesting XML - End');

		$LattesProducao->producao_xml($xml, $lattes);
		return '';
		return $xml;
		$this->vinculo($xml, $lattes);
		return $xml;
	}

	function LattesLoad($id)
	{		
		$tela = '';
		$url = 'https://brapci.inf.br/ws/api/?verb=lattes&q=' . $id;
		

		$dir = '.tmp';
		dircheck($dir);
		$dir = '.tmp/lattes';
		dircheck($dir);

		$file = $dir . '/' . $id . '.zip';
		$file2 = $dir . '/' . $id . '.xml';

		if (!file_exists(($file2))) {
			$txt = file_get_contents($url);
			file_put_contents($file, $txt);

			$zip = new \ZipArchive;
			$res = $zip->open($file);
			if ($res === TRUE) {
				$zip->extractTo($dir);
				$zip->close();
				unlink($file);
			} else {
				echo '<pre>';
				print_r($txt);
				echo "ERRO Lattes XML";
				exit;
			}
		}
		clog('Lattes Load - End');
		return $tela;
	}

	


	function atuacao_profissiona($xml, $id)
	{
		$RDF = new \App\Models\RDF\RDF();
		$RDFData = new \App\Models\RDF\RDFData();
		$RDFClass = new \App\Models\RDF\RDFClass();


		$xml = (array)$xml;
		$xml = (array)$xml['DADOS-GERAIS'];
		$xml = (array)$xml['ATUACOES-PROFISSIONAIS'];
		$xml = (array)$xml['ATUACAO-PROFISSIONAL'];

		for ($r = 0; $r < count($xml); $r++) {
			$dados = $xml[$r];
			$inst = $dados['NOME-INSTITUICAO'];
			$xmld = (array)$xml[$r];

			$vinculos = (array)$xmld['VINCULOS'];
			if (!isset($vinculos[0])) {
				$vinculos[0] = $vinculos;
			}
			$ai = 999999;
			$af = 0;
			$ano1 = 0;
			$ano2 = 0;
			$nvc = array();

			foreach ($vinculos as $v1 => $vinc) {
				$vinc = (array)$vinc;
				if (isset($vinc['@attributes'])) {
					$xvinc = $vinc['@attributes'];

					$ano1 = round($xvinc['ANO-INICIO'] . strzero($xvinc['MES-INICIO'], 2));
					$ano2 = round($xvinc['ANO-FIM'] . strzero($xvinc['MES-FIM'], 2));
					$tipo = $xvinc['TIPO-DE-VINCULO'];
					$tpv = trim($xvinc['OUTRO-VINCULO-INFORMADO']);
					echo '<br>Tipo>'.$tipo;
					if ($tipo == 'SERVIDOR_PUBLICO') { $tpv = $tipo; }

					if (strlen($tpv) > 0) {
						$nvc[$tpv] = 1;
					}
					if ($ano1 < $ai) {
						$ai = $ano1;
					}
					if ($ano2 > $af) {
						$af = $ano2;
					}
				}
			}

			$ok = 0;
			$et = '';
			foreach ($nvc as $k => $v) {
				if ($k == 'Celetista formal') {
					$ok = 1;
					$et = $k;
				}
				if ($k == 'SERVIDOR_PUBLICO') {
					$ok = 1;
					$et = 'Servidor Público';
				}				
				echo $k . '<br>';
			}

			if ((($ai > 190001) and ($ai < 300001)) and ($ok == 1)) {
				/* Instituição */
				$inst = $dados['NOME-INSTITUICAO'];

				/* Codigo */
				$codo = $dados['CODIGO-INSTITUICAO'];

				if (strlen($codo) > 0) {
					$class = 'frbr:CorporateBody';
					$idc = $RDF->RDP_concept($inst, $class);
					clog('Lattes - Instrituicao - ' . $inst);

					$prop = 'brapci:isCNPqInstCode';
					$idl = $RDFData->literal($idc, $prop, $codo);
					clog('Lattes - InstrituicaoCode - ' . $inst);

					/********************** Vinculo */
					$codo1 = substr($ai, 0, 4) . '-' . substr($ai, 4, 2) . '-01';
					$codo2 = substr($af, 0, 4) . '-' . substr($af, 4, 2) . '-01';

					$name = strzero($idc,10).'-'.strzero($id,8).'-Employee';
					$class = 'frbr:EmploymentRelationship';
					$idv = $RDF->RDP_concept($name, $class);
					clog('Lattes - Vinculo - ' . $name);

					$prop = 'brapci:Date';
					$iddi = $RDF->RDP_concept($codo1, $class);
					clog('Lattes - Admissão - ' . $codo1);

					$prop = 'brapci:employeeType';
					$idet = $RDF->RDP_concept($name, $class);
					clog('Lattes - Vinculo - ' . $et);

					$RDF->propriety($idv, 'brapci:employeeType', $idet);
					$RDF->propriety($id, 'brapci:employeeAffiliatio', $idv);
					$RDF->propriety($idv, 'brapci:employeeCorporateBody', $idc);
					$RDF->propriety($idv, 'brapci:employeeAdmitted', $iddi);

					if ($af > 1900)
					{
						$prop = 'brapci:Date';
						$iddf = $RDF->RDP_concept($codo2, $class);
						clog('Lattes - Demissão - ' . $codo2);
						$RDF->propriety($idv, 'brapci:employeeFired', $iddf);
					}


					echo '<br>==id==' . $id;
					echo '<br>==idv==' . $idv;
				}
			} else {
				echo '<h1>'.$inst.'</h1>';
				print_r($nvc);
			}
		}
	}

	function vinculo($xml, $id)
	{
		$this->atuacao_profissiona($xml, $id);
		exit;
		$RDF = new \App\Models\RDF\RDF();
		$RDFData = new \App\Models\RDF\RDFData();
		$RDFClass = new \App\Models\RDF\RDFClass();

		echo '<pre>';
		print_r($xml);
		echo '</pre>';
		exit;
		$xml = (array)$xml;
		$xml = (array)$xml['DADOS-GERAIS'];
		$xml = (array)$xml['ENDERECO'];
		$xml = (array)$xml['ENDERECO-PROFISSIONAL'];
		$dados = $xml['@attributes'];
		$class = 'brapci:isCNPqInstCode';


		/* Instituição */
		$inst = $dados['NOME-INSTITUICAO-EMPRESA'];
		$class = 'frbr:CorporateBody';
		$idc = $RDF->RDP_concept($inst, $class);
		clog('Lattes - Instrituicao');

		/* Codigo */
		$prop = 'brapci:isCNPqInstCode';
		$codo = $dados['CODIGO-INSTITUICAO-EMPRESA'];
		$idl = $RDFData->literal($idc, $prop, $codo);

		/* Instituição */
		clog('Lattes - Lugar');

		$inst = $dados['PAIS'];
		if (strlen($inst) > 0) {
			$class = 'brapci:Place';
			$id_country = $RDF->RDP_concept($inst, $class);
		}

		$inst = $dados['UF'];
		if (strlen($inst) > 0) {
			$class = 'frbr:Place';
			$id_state = $RDF->RDP_concept($inst, $class);
			$RDF->propriety($id_country, 'brapci:haveState', $id_state);
		}

		$inst = $dados['CIDADE'];
		if (strlen($inst) > 0) {
			$class = 'frbr:Place';
			$id_city = $RDF->RDP_concept($inst, $class);
			$RDF->propriety($id_state, 'brapci:haveCity', $id_city);
		}

		/* Instituição */
		if ($id_city > 0) {
			$RDF->propriety($idc, 'brapci:isPlace', $id_city);
		}

		$inst = $dados['NOME-ORGAO'];
		$class = 'frbr:CorporateBodyDep';
		$idcd = $RDF->RDP_concept($inst, $class);
		//$RDF->propriety($id_country,'brapci:haveCity',$id_state);

		/* Affiliation */
		$inst = 'Affiliation:' . strzero($idc, 8) . '.' . strzero($id, 8);
		$class = 'frbr:Affiliation';
		$id_aff = $RDF->RDP_concept($inst, $class);

		echo 'id==>' . $id;
		echo 'aff==>' . $id_aff;
		$tela = '===>' . $id . '===>' . $id_aff;
		if ($id_aff > 0) {
			$RDF->propriety($id, 'brapci:affiliatedWith', $id_aff);
		}
	}
}
