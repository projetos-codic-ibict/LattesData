<h1>Apache 2</h1>
$apt install apache2

Habilitando os MODs do proxy
$sudo a2enmod ssl
$sudo a2enmod proxy
$sudo a2enmod proxy_http
$sudo a2enmod proxy_balancer
$sudo a2enmod lbmethod_byrequests

$nano /etc/apache2/sites-avaliables/lattesdata.conf

<VirtualHost *:81>
       ServerAdmin renefgj@gmail.com
       ServerName <DNS do Site, ex: www.brapci.inf.br>
       DocumentRoot /data/html/Brapci3.0/public/
       <Directory "/data/html/LattesData/public/">
           Require all granted
       </Directory>
</VirtualHost>


       <Location /config>
               ProxyPass localhost:81>
               SetEnv force-proxy-request-1.0 1
               SetEnv proxy-nokeepalive 1
       </Location>