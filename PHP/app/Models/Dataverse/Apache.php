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

        $sx .= '<pre>
        sudo a2enmod ssl
        sudo a2enmod proxy
        sudo a2enmod proxy_http
        sudo a2enmod proxy_balancer
        sudo a2enmod lbmethod_byrequests
        </pre>';

        $sx .= '
        <pre>
        &lt;IfModule mod_ssl.c>
            &lt;VirtualHost *:443>
                ServerName <b class="text-danger">vitrinedadosabertos.rnp.br</b>
                ServerAdmin <b class="text-danger">renefgj@gmail.com</b>
                DocumentRoot "/var/www/html/"

                #PROXY

                ##################### Branding para o DVN
                ProxyPass /dvn !
                Alias "/dvn/" "/var/www/dataverse/branding/"

                &lt;Directory "/var/www/dataverse/branding/">
                        Options Indexes FollowSymLinks MultiViews
                        AllowOverride None
                        Order allow,deny
                        allow from all
                        Require all granted
                &lt;/Directory>

                #################### Proxy para o Dataverse View - veja ....
                ProxyPass /dataview !
                Alias "/dataview/" "/var/www/DataView/public/"
                &lt;Directory "/var/www/DataView/public/">
                    Options Indexes FollowSymLinks MultiViews
                    AllowOverride None
                    Order allow,deny
                    allow from all
                    Require all granted
                &lt;/Directory>

                ##################### Configurações para o Shibboleth #####################
                ProxyPass /Shibboleth.sso !
                ProxyPassMatch ^/shibboleth-ds !

                # pass everything else to Payara
                ProxyPass / ajp://localhost:8009/
                ProxyPass / http://localhost:8080/

                &lt;Location /shib.xhtml>
                    AuthType shibboleth
                    ShibRequestSetting requireSession 1
                    require valid-user
                &lt;/Location>
        &lt;/VirtualHost>
        </pre>
        ';

        $sx .= "Outra opção é direcionar a porta 80 para a 8080 diretamente pelo iptables";
        $sx .= '<pre>
                    # Example commands that demonstrate how to run Payara Server on the "special" ports < 1024
                    #
                    # iptables -t nat -A PREROUTING -p tcp -m tcp --dport 80 -j REDIRECT --to-ports 8080
                    # iptables -t nat -A PREROUTING -p udp -m udp --dport 80 -j REDIRECT --to-ports 8080
                    </pre>';

        return $sx;
    }
}