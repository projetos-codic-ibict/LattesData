<?php

namespace App\Controllers;

helper(['boostrap', 'url', 'graphs', 
        'sisdoc_forms', 'form', 'nbr', 'sessions',
        'database']);
define("URL", getenv('app.baseURL'));
class Home extends BaseController
{
    public function index()
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
