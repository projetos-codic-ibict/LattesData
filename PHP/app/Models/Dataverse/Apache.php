<?php

namespace App\Models\Dataverse;

use CodeIgniter\Model;

class Apache extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'checklists';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    function index($d1, $d2, $d3)
    {

        $sx = h('Checklist', 1);
        if (strlen($d1) > 0) {
            $sx .= h('dataverse.Apache2', 4);
        }

        $cmd = '';

        $file = false;
        switch ($d1) {
            default:
                $sx .= $this->menu();
                $sx .= $this->apache();
                break;
        }
        return $sx;
    }

    function menu()
    {
        $sx = 'Menu';

        $item = array();
        $item['postgresql'] = 'postgresql_install';
        $item['solr'] = 'solr_install';
        $item['DOI'] = 'doi_install';
        $item['email'] = 'email_install';

        return $sx;
    }

    function config()    
    {

        $sx  = '
        Crie o arquivo de configuração do Apache2:<br>
        <pre>nano /etc/apache2/sites-avaliable/config.conf</pre>
        com o conteúdo:
        <pre>
                    <VirtualHost *:81>
                        ServerAdmin renefgj@gmail.com
                        ServerName pocdadosabertos.inep.rnp.br
                        ServerAlias 20.197.236.31
                        DocumentRoot /data/LattesData/PHP/public
                        <Directory "/data/LattesData/PHP/public">
                            Require all granted
                        </Directory>
                    </VirtualHost>
        </pre>

        Habilite o site:<br>
        <pre>a2ensite config.conf</pre>
        <br>
        <pre>service apache2 restart</pre>
                    ';        
    }



    function apache($d1, $d2, $d3)
    {
        $sx = h('dataverse.Apache2', 1);
        $sx .= 'PROXY para apache';

        $code = '
                    <Location />
                            ProxyPass http://localhost:8080/
                            SetEnv force-proxy-request-1.0 1
                            SetEnv proxy-nokeepalive 1
                    </Location>
        
                    <Location /config>
                            ProxyPass http://localhost:81
                            SetEnv force-proxy-request-1.0 1
                            SetEnv proxy-nokeepalive 1
                    </Location>';

        $code .= cr();
        $code .= '
                    <VirtualHost *:81>
                        ServerAdmin renefgj@gmail.com
                        ServerName pocdadosabertos.inep.rnp.br
                        ServerAlias 20.197.236.31
                        DocumentRoot /data/LattesData/PHP/public
                        <Directory "/data/LattesData/PHP/public">
                            Require all granted
                        </Directory>
                    </VirtualHost>
                    ';
        $code = troca($code, '<', '&lt;');
        $code = troca($code, chr(13), '<br>');
        $sx .= '<tt>' . $code . '</tt>';

        $sx .= '<pre>
                    # Example commands that demonstrate how to run Payara Server on the "special" ports < 1024
                    #
                    # iptables -t nat -A PREROUTING -p tcp -m tcp --dport 80 -j REDIRECT --to-ports 8080
                    # iptables -t nat -A PREROUTING -p udp -m udp --dport 80 -j REDIRECT --to-ports 8080
                    </pre>';

        return $sx;
    }
}
