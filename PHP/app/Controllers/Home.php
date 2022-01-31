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
        $sx .= view('welcome_message');
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

    function processo()
    {
        $txt = '';
        if (isset($_GET['process'])) {
            $id = $_GET['process'];
            $LattesData = new \App\Models\Lattes\LattesData();

            $did = $LattesData->padroniza_processo($id);

            if ($did[1] != 0) {
                $txt = view('welcome_message');
            } else {
                $txt = '<div class="container"><div class="col-12">' . $LattesData->process($did) . '</div></div>';
            }
        }

        $sx = '';
        $sx .= view('header/head');
        $sx .= view('header/navbar');
        $sx .= $txt;
        $sx .= view('header/footer');

        return $sx;
    }
}
