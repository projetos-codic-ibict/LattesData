<?php

namespace App\Controllers;

helper(['boostrap', 'url', 'graphs', 
        'sisdoc_forms', 'form', 'nbr', 'sessions',
        'database']);
define("URL", getenv('app.baseURL'));
define("PATH", getenv('app.baseURL').'index.php/');
define("MODULE", 'cnpq/');

$this->session = \Config\Services::session();
$language = \Config\Services::language();

class Cnpq extends BaseController
{

    public function index()
        {
            $sx = '';
            $sx .= $this->inport();
            return $sx;
        }

    
    public function inport()
    {
        $sx = '';
        $COL1 = '';
        $header = new \App\Models\Cnpq\Header();
        $DEPOSITO = $header->form();

        $sx .= view('header/head');
        $sx .= $header->header();

        $txt = '20203121198';

        $sC = bsc($txt,8);
        $sC .= bsc($DEPOSITO,4);

        $sx .= bs($sC);

        $sx .= $header->footer();
        return $sx;
    }

    function about()
    {
        $sx = '';
        $sx .= view('header/head');
        $sx .= view('header/navbar');
        
        $sx .= view('header/footer');
        return $sx;
    }
}
