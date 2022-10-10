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
            $ver = 'v0.22.06.24';
            $sx = '';
            $sx .= view('header/head');
            $sx .= view('header/navbar');
            $sx .= bs(bsc('<div class="mb-5"></div>',12));
            $sx .= bs(bsc('<div class="mb-5"></div>',12));
            $sx .= bs(
                    bsc(h(lang('dataverse.Custom_mode')),12,'text-center'))
                    ;
            $sx .= bs(bsc('<div class="mb-5"></div>',12));
            $sx .= bs(bsc('<h2 class="text-center text-danger">Under Construction</h2>',12));
            $sx .= '<div style="position: absolute; bottom: 0; left: 5;">';
            $sx .= '<a href="'.PATH.MODULE.'dataverse" style="text-decoration: none;"><span style="color: grey;" class="ms-2">config</span></a>';
            $sx .= '</div>';
            $sx .= '<style> a, a.h5 { text-decoration: none; color: #333; } </style>';
            $sx .= '<span style="position: fixed; top: 0; right: 5; font-size: 50%;">'.$ver.'</span>';
            return $sx;
        }

    function navbar()
        {
            $sx = '';
            $sa = '';
            $link = '<a href="'.PATH.MODULE.'dataverse" style="text-decoration: none;">';
            $linka = '</a>';
            $sa .= bsc($link.'<img src="'.URL.'/img/logos/gt-rdpbrasil.png" width="100%" class="img-fluid">'.$linka,2);
            $sa .= bsc(h(lang('dataverse.dataverse_implementation'),3),10,'text-center mt-4');
            $sa .= bsc('<hr>',12);
            $sx .= bs($sa,'fluid');

            return $sx;
        }

    function footer()
        {
            $sx = '<hr>';
            $sx .= '<img src="'.URL.'img/logos/rnp_logo.png" style="height: 60px;">';
            $sx = bs(bsc($sx,12));
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
                        $sx .= $this->navbar();
                        break;
                }
            return $sx;
        }

    function dataverse($d1='',$d2='',$d3='',$d4='')
	{
		$Dataverse = new \App\Models\Dataverse\Index();
		$tela = $this->cab();
		$tela .= bs($Dataverse->index($d1,$d2,$d3,$d4));
		$tela .= $this->cab("footer");
        $tela .= $this->footer();
		return $tela;
	}

    function guide($d1 = '', $d2 = '', $d3 ='', $d4 ='', $d5 = '')
    {
        $Guide = new \App\Models\Guide\Index();
        $tela = $this->cab();
        $tela .= bs($Guide->index($d1, $d2, $d3, $d4));
        $tela .= $this->cab("footer");
        $tela .= $this->footer();
        return $tela;
    }

    function xxdataverse($d1='',$d2='',$d3='',$d4='')
        {
            $PA = new \App\Models\Dataverse\PA_Schema();
            $sx = '';

            $sx .= $PA->index($d1,$d2,$d3,$d4);

            $sx .= view('header/footer');
            return($sx);
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
