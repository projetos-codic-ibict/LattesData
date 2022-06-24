<?php

namespace App\Controllers;

helper(['boostrap', 'url', 'form', 'nbr', 'sessions','sisdoc_forms','dataverse']);

use App\Controllers\BaseController;
define("URL", getenv("app.baseURL"));

$this->session = \Config\Services::session();

class LattesData extends BaseController
{
    public function index()
    {
        $Header = new \App\Models\LattesData\Header\Headers();
        $Forms = new \App\Models\LattesData\Forms();

        $sx = $Header->index();

        $sa = $Forms->home();
        $sb = $Forms->form();

        $this->util();

        $sx .= bs(bsc($sa,8).bsc($sb,4));

        $sx .= $Header->footer();
        return $sx;
    }

    function util()
        {
            $Header = new \App\Models\LattesData\Header\Headers();
            $Forms = new \App\Models\LattesData\Forms();

            $sx = $Header->index();

            if (isset($_GET['act']))
                {
                    $act = $_GET['act'];
                } else {
                    $act = 'email';
                }
            

            switch($act)
                {
                    case 'email':
                        $LattesEmail = new \App\Models\LattesData\LattesEmail();
                        $sx = $LattesEmail->email_cadastro();

                        $email = \Config\Services::email();
                        $config['mailType']       = 'html';
                        $email->initialize($config);
                        $email->AddEmbeddedImage('img/logo.jpg', 'logo_ref');
                        $email->setFrom('lattesdata@app.ibict.br');
                        $email->setTo('renefgj@gmail.com');
                        $email->setSubject('Formulário de contato');
                        $email->setMessage($sx);                        
                        $email->send();
                        $email->printDebugger();

                    break;

                    default:
                    $sx .= '<ul>';
                    $sx .= '<li>'.anchor(URL.'/util/?act=role').'</li>';
                    $sx .= '<li>'.anchor(URL.'/util/?act=email').'</li>';
                    $sx .= '</ul>';
                }

            return $sx;
        }
}
