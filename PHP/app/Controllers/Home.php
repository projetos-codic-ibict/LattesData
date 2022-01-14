<?php

namespace App\Controllers;

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
        }
}
