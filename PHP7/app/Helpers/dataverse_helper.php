<?php
/*
@author: Rene Faustino Gabriel Junior <renefgj@gmail.com>
@version: 0.22.06.21
*/

function role($user,$dataverse,$role)
    {

    }
function CreateGroup($alias='',$dd=array())
    {
        $nome = "Fulano de Tal";
        //description
        //displayName
        //aliasInOwner
        $alias = '2020CNPq3121198';
        $dd = array();
        $dd['description'] = 'Grupo de Pesquisa - Lider '.$nome;
        $dd['displayName'] = 'DVGR '.$nome;
        $dd['aliasInOwner'] = 'renefjg';

        dircheck('.tmp');
        dircheck('.tmp/group');
        $file = '.tmp/group/group-'.troca($dd['displayName'],'/','-').'.json';
        file_put_contents($file, json_encode($dd, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        $SERVER_URL = getenv("DATAVERSE_URL");

        $cmd = 'curl -X POST -d '.$file.' ';
        $cmd .= ' -H "Content-type:application/json" ';
        $cmd .= $SERVER_URL.'/api/dataverses/'.$alias.'/groups';
        $cmd .= '&key='.getenv('BUILTIN_USERS_KEY');

        jslog($cmd);
        $txt = shell_exec($cmd);
        jslog($txt);
        $sx = '<span class="text-success">Usuário criado</span>';
        return $sx;                


    }

/*********************************************************************************** Create Dataverse */    
function CreateUser($dd)
    {
        $SERVER_URL = getenv("DATAVERSE_URL");
        $NEWUSER_PASSWORD = substr(md5($dd['firstName'].$dd['lastName'].date("Ymd")),0,10);
        dircheck('.tmp');
        dircheck('.tmp/users');
        $file = '.tmp/users/user-'.troca($dd['userName'],'/','-').'.json';
        file_put_contents($file, json_encode($dd, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        $url = getenv("DATAVERSE_URL");

        $cmd = 'curl -d @"'.$file.'" ';
        $cmd .= ' -H "Content-type:application/json" ';
        $cmd .= $SERVER_URL.'api/builtin-users?';
        $cmd .= 'password='.$NEWUSER_PASSWORD;
        $cmd .= '&key='.getenv('BUILTIN_USERS_KEY');

        jslog($cmd);
        $txt = shell_exec($cmd);
        jslog($txt);
        $sx = '<span class="text-success">Usuário criado</span>';
        return $sx;
    }        

/******************************************************************************************* Trata ERRO */    
function dataverseError($rsp)
    {
        $sx = '';
        if (isset($rsp['status']))
            {
                $status = $rsp['status'];
                switch($status)
                    {
                        case 'ERROR':
                            $sx = '<span class="text-danger">'.$rsp['message'].'</span>';
                            break;
                        default:
                            $sx .= '<span class="text-success">Sucesso na criação</span>';
                    }
            }
        return $sx;
    }

/*
    "alias": "2012CNPq303364",
    "name": "MECANISMOS DE REPARA\u00c7\u00c3O DE DNA ASSOCIADOS COM LES\u00d5ES CITOT\u00d3XICAS INDUZIDAS POR AGENTES ANTITUMORAIS",
    "dataverseContacts": [
        {
            "contactEmail": "pegas@cbiot.ufrgs.br"
        }
    ],
    "affiliation": "Universidade Federal do Rio Grande do Sul",
    "description": "Vide projeto anexo",
    "dataverseType": "LABORATORY"
*/        

/*********************************************************************************** Create Dataverse */    
function CreateDataverse($dd,$PARENT='')
    {
        dircheck('.tmp');
        dircheck('.tmp/dataverse');
        $file = '.tmp/dataverse/dataverse-'.troca($dd['alias'],'/','-').'.json';
        file_put_contents($file, json_encode($dd, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        $url = getenv("DATAVERSE_URL");
        
        $dd['AUTH'] = true;
        $dd['POST'] = true;
        $dd['FILE'] = $file;
        $dd['url'] = $url;
        $dd['api'] = 'api/dataverses/'.$PARENT;
        $dd['apikey'] = getenv('DATAVERSE_APIKEY');
        $dd['FILE'] = $file;

        $rsp = DataverseCurlExec($dd);

        /******************************** Retorno */
        $rsp = (array)json_decode($rsp);
        $sx = dataverseError($rsp);
        return $sx;
    } 

/*********************************************************************************** Create Dataverse */    
function CreateDataset($dd,$PARENT='')
    {
        dircheck('.tmp');
        dircheck('.tmp/dataset');
        $file = '.tmp/dataset/dataset-'.troca($dd['alias'],'/','-').'.json';
        file_put_contents($file, json_encode($dd, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        $url = getenv("DATAVERSE_URL");
        
        $dd['AUTH'] = true;
        $dd['POST'] = true;
        $dd['FILE'] = $file;
        $dd['url'] = $url;
        $dd['api'] = 'api/dataverses/'.$PARENT.'/datasets';
        $dd['apikey'] = getenv('DATAVERSE_APIKEY');
        $dd['FILE'] = $file;


        $rsp = DataverseCurlExec($dd);

        /******************************** Retorno */
        $rsp = (array)json_decode($rsp);
        $sx = dataverseError($rsp);
        return $sx;

        $msg = (string)$rsp['json'];
        $msg = (array)json_decode($msg);

        if (!isset($msg['status']))
            {
                return lang('Response empty');
            }
        $sta = trim((string)$msg['status']);
        switch($sta)
            {
                case 'OK':
                    $sx = '<span class="color: green">Conjunto de dados '.$dd['alias'].'criado</span>';
                break;
                
                case 'ERROR':
                    $sx = '<pre style="color: red;">'; 
                    $sx .= $msg['message'];	
                    $sx .= '<br>Dataverse Name: <b>'.$dd['alias'].'</b>';
                    $sx .= '<br><a href="'.'dataverse/'.$PARENT.'" target="_blank">'.$url.'/'.$PARENT.'</a>';
                    $sx .= '</pre>';
                    break;
            }
        return $sx;
    }

    /******************************************************************************************* Execute CURL */    
    function DataverseCurlExec($dt)
	{
		$rsp = array();
		$rsp['msg'] = '';

		if ((!isset($dt['url'])) or (!isset($dt['api'])) or (!isset($dt['apikey']))) {
			$sx = "Error: Missing URL, API or API Key";
			pre($dt);
			$sx .= '<br>url='.$dt['url'];
			$rsp['msg'] = $sx;
		} else {
			$url = $dt['url'] . $dt['api'];
			$apiKey = $dt['apikey'];

			/* Comando */
			$cmd = 'curl ';
			/* APIKEY */
			if (isset($dt['AUTH'])) {
				$cmd .= '-H X-Dataverse-key:' . $apiKey . ' -H Content-type:application/json ';
			}

			/* POST */
			if (isset($dt['POST'])) {
				$cmd .= '-X POST ' . $url . ' ';
			}

			/* POST */
			if (isset($dt['FILE'])) {
				if (!file_exists($dt['FILE'])) {
					$rsp['msg'] .= bsmessage('File not found - ' . $dt['FILE'], 3);
				}
				//		$cmd .= '-H "Content-Type: application/json" ';
				$cmd .= '--upload-file "' . ($dt['FILE']) . '" ';
			}
			jslog($cmd);
			$txt = shell_exec($cmd);
			jslog($txt);
			return $txt;
		}
		return $sx;
	}    