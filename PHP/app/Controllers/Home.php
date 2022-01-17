<?php

namespace App\Controllers;

helper(['boostrap', 'url', 'graphs', 'sisdoc_forms', 'form', 'nbr','sessions']);

class Home extends BaseController
{
    public function index()
    {
        return view('welcome_message');
    }

    function processo()
        {
            $id = $_GET['process'];
            echo "Importando processo: ".$id;
            $Dataverse = new \App\Models\Dataverse\Dataverse();
            $Dataverse->test();
            //$Dataverse->getDataverses();
        }
}
