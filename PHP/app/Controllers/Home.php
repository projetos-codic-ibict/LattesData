<?php

namespace App\Controllers;

helper(['boostrap', 'url', 'graphs', 'sisdoc_forms', 'form', 'nbr','sessions']);
define("URL",'http://lattesdata/');
class Home extends BaseController
{
    public function index()
    {
        $sx = '';
        $sx .= view('header/head');
        $sx .= view('header/navbar');
        $sx .= view('welcome_message');
        $sx .= view('header/footer');
        return $sx;
    }

    function processo()
        {
            $id = $_GET['process'];
            $LattesData = new \App\Models\Lattes\LattesData();
            $INCts = new \App\Models\Lattes\INCTs();

            $id = $INCts->padroniza_processo($id);

            $sx = '';
            $sx .= view('header/head');
            $sx .= view('header/navbar');
            $sx .= '<div class="container"><div class="col-12">'.$LattesData->process($id).'</div></div>';
            $sx .= view('header/footer');
            return $sx;
        }
}
