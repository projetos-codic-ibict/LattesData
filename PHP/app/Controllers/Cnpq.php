<?php

namespace App\Controllers;

helper(['boostrap', 'url', 'graphs', 
        'sisdoc_forms', 'form', 'nbr', 'sessions',
        'database']);
define("URL", getenv('app.baseURL'));
define("PATH", getenv('app.baseURL').'index.php/');
define("MODULE", 'home/');

$this->session = \Config\Services::session();
$language = \Config\Services::language();

class Home extends BaseController
{
    public function index()
        {
            $sx = '';
            $sx .= view('header/head');
            $sx .= view('header/navbar');
            $sx .= bs(bsc('<div class="mb-5"></div>',12));
            $sx .= bs(bsc('<div class="mb-5"></div>',12));
            $sx .= bs(
                    bsc('',3).
                    bsc('<img src="'.URL.'/img/logo_lattesdata.png" width="100%" class="img-fluid">',6)).
                    bsc('',3)
                    ;
            $sx .= bs(bsc('<div class="mb-5"></div>',12));
            $sx .= bs(bsc('<h2 class="text-center text-danger">Under Construction</h2>',12));
            $sx .= '<div style="position: absolute; bottom: 0; left: 5;">';
            $sx .= '<a href="'.PATH.MODULE.'dataverse" style="text-decoration: none;"><span style="color: white;" class="ms-2">tt</span></a>';
            $sx .= '</div>';
            return $sx;
        }

    public function inport()
    {
        $sx = '';
        $sx .= view('header/head');
        $sx .= view('header/navbar');
        if (isset($_GET['process'])) {
            $id = $_GET['process'];
            $LattesData = new \App\Models\Lattes\LattesData();
            $did = $LattesData->padroniza_processo($id);

            if ($did[1] != 0) {
                $data = array();
                switch($did[1])
                    {
                        case 2:
                            $erro = 'Identificador do processo incorreto, use 000000/0000-0';
                            break;
                        case 1:
                            $erro = 'Erro desconhecido';
                            break;
                    }
                $data['erro'] = $erro;
                $txt = view('welcome_message',$data);
            } else {
                $txt = '<div class="container"><div class="col-12">' . $LattesData->process($did) . '</div></div>';
            }
        } else {
            $txt = view('welcome_message');
        }
        $sx .= $txt;
        $sx .= view('header/footer');
        return $sx;
    }

    function cab($tp='')
        {
            $sx = '';
            switch($tp)
                {
                    case 'footer':
                        $sx = '';
                        break;
                    default:
                        $sx .= view('header/head');
                        $sx .= view('header/navbar');
                        break;        
                }
            return $sx;
        }

    function about()
    {
        $sx = '';
        $sx .= view('header/head');
        $sx .= view('header/navbar');
        //$sx .= view('welcome_message');
        $sx .= view('header/footer');
        return $sx;
    }
}
