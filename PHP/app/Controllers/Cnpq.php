<?php

namespace App\Controllers;

helper(['boostrap', 'url', 'graphs', 
        'sisdoc_forms', 'form', 'nbr', 'sessions',
        'database']);
define("URL", getenv('app.baseURL'));
define("PATH", getenv('app.baseURL'));
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
        $Lattes = new \App\Models\Lattes\LattesData();
        $DEPOSITO = $header->form();

        $sx .= view('header/head');
        $sx .= $header->header();

        
        /* Valida Processo */
        if (isset($_POST['process']) and (strlen($_POST['process']) > 0))
            {
                $proc = $_POST['process'];
                $proc = $Lattes->padroniza_processo($proc);

                $txt = $proc[1];
                $txt .= h($proc[0],5);

                $txt .= $Lattes->show_metadate($proc);

            } else {
                $txt = h('Deposite seus dados');
                $txt .= '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse ac rhoncus mi. Maecenas luctus sapien velit. Etiam eget dolor mollis, porta leo eu, dignissim ligula. Vestibulum porttitor tempus consequat. Ut tellus urna, convallis et risus nec, tincidunt placerat dui. Curabitur non lectus aliquam, iaculis erat vel, molestie nisl. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras vel mollis augue, in imperdiet ipsum. Donec maximus efficitur ex, vel laoreet massa euismod ut. Aliquam porttitor mollis odio dapibus auctor. Donec at dui et justo eleifend tincidunt. Duis at finibus mi, ac lacinia felis. Mauris et ultrices nulla. Aenean nec luctus elit. Nulla facilisi.</p>';
                $txt .= '<p>Sed placerat lacus id sagittis consectetur. Ut vitae sodales mi. Donec vel quam sed elit mattis elementum. Nunc feugiat odio a sem rhoncus, at consequat enim interdum. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris aliquam ultrices erat ac pharetra. Phasellus dictum nisi quis nunc euismod, sit amet blandit elit condimentum.</p>';
                $txt .= '<p>Donec eleifend ante eu fringilla lacinia. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum mattis ac orci vitae faucibus. Fusce cursus, ex et scelerisque commodo, tellus dui euismod felis, eu vestibulum ipsum libero eu libero. Cras in lacus et felis molestie cursus. Maecenas tempus nisl ac lacus interdum dignissim non ut velit. Suspendisse pellentesque metus arcu. Nulla facilisi. Quisque ac porta leo, sit amet porta sem. Vivamus aliquet, lacus non commodo efficitur, lacus nulla rhoncus quam, ac lobortis enim leo eu diam. Aenean accumsan ullamcorper fringilla. Nulla dapibus id quam ut condimentum. In tincidunt orci et eleifend varius.</p>';
                $txt .= '<div style="height: 200px;"></div>';
                $txt .= '<p>20203121198</p>';
            }


        $sC = bsc($txt,8,'" style="margin-top: 20px;');
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
