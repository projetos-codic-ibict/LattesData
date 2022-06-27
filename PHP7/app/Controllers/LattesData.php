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

        jslog("HELLO");

        if (isset($_GET['act']))
            {
                $this->util();
            }
        

        $sx .= bs(bsc($sa,8).bsc($sb,4));

        jslog("BYE");
        
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
                    $act = 'group';
                }
            
            echo h($act);

            switch($act)
                {
                    case 'group':
                        //$sx = CreateGroup(1,1);
                        break;
                    case 'email':
                        $LattesEmail = new \App\Models\LattesData\LattesEmail();
                        $sx = $LattesEmail->email_cadastro();

                        $email = \Config\Services::email();
                        $config['mailType'] = 'html';
                        $email->initialize($config);
                        $email->setFrom('lattesdata@app.ibict.br','LattesData');
                        $email->setTo('renefgj@gmail.com');
                        $email->setSubject('[LattesData] Cadastro de Dataset'); 
                        //                     
                        $filename = 'img/bg-email-hL3.jpg';
                        if (file_exists($filename))
                            {
                                $email->attach($filename);  
                                $cid = $email->setAttachmentCID($filename);                   
                                $sx = troca($sx,'$image1',$cid);
                            } else { echo "Logo not found"; }
                        $email->setMessage($sx);
                        $email->send();
                        print_r($email->printDebugger());

                    break;

                    default:
                    $sx .= '<ul>';
                    $sx .= '<li>'.anchor(URL.'/util/?act=role').'</li>';
                    $sx .= '<li>'.anchor(URL.'/util/?act=email').'</li>';
                    $sx .= '<li>'.anchor(URL.'/util/?act=group','Criar grupo').'</li>';
                    $sx .= '</ul>';
                }

            return $sx;
        }
}
