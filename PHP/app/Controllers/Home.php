<?php

namespace App\Controllers;

helper(['boostrap', 'url', 'graphs', 'sisdoc_forms', 'form', 'nbr', 'sessions']);
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
                $data = 'ERRO';
                $txt = h('ERRO',1);
                $txt .= view('welcome_message',$data);
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
