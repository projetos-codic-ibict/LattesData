<h1>Instalação do Apache/Proxy</h1>
<tt>apt-get install apache2</tt>

<h2>Installl PHP</h2>
<code>
sudo apt-get update
sudo apt-get install apache2 php libapache2-mod-php
sudo apt-get install php-soap php-xml php-curl php-opcache php-gd php-sqlite3 php-mbstring php-intl
sudo apt install php php-json
</code>

Configuração do arquivo 000-default.conf

<code>
       &lt;Location /dvn>
               ProxyPass http://143.54.112.86:81/
               SetEnv force-proxy-request-1.0 1
               SetEnv proxy-nokeepalive 1
       &lt;/Location>
       &lt;Location /config>
               ProxyPass http://143.54.112.86:82/
               SetEnv force-proxy-request-1.0 1
               SetEnv proxy-nokeepalive 1
       &lt;/Location>
       &lt;Location /deposito>
               ProxyPass http://143.54.112.86:81/
               SetEnv force-proxy-request-1.0 1
               SetEnv proxy-nokeepalive 1
       &lt;/Location>

</code>

<h2>Habilitando os MODs do proxy</h2>
<code>sudo a2enmod ssl</code>
<code>sudo a2enmod proxy</code>
<code>sudo a2enmod proxy_http</code>
<code>sudo a2enmod proxy_balancer</code>
<code>sudo a2enmod lbmethod_byrequests</code>

<h3>Criando Serviços - DVN</h3>

Aquivo /etc/apache2/sites-avaliable/dvn.conf
<tt>pico /etc/apache2/sites-available/dvn.conf</tt>
<tt>mkdir /var/www/dataverse</tt>
<tt>mkdir /var/www/dataverse/branding</tt>
<pre>

&lt;VirtualHost *:81>
       ServerAdmin renefgj@gmail.com
       ServerName pocdadosabertos.inep.rnp.br
       ## ServerAlias 20.197.236.31
       DocumentRoot /var/www/dataverse/branding
       &lt;Directory "/var/www/dataverse/brandingx">
           Require all granted
       &lt;/Directory>
&lt;/VirtualHost>

Aquivo /etc/apache2/sites-avaliable/configuration.conf
<tt>pico /etc/apache2/sites-available/configuration.conf</tt>
<pre>
&lt;VirtualHost *:84>
       ServerAdmin renefgj@gmail.com
       ServerName pocdadosabertos.inep.rnp.br
       ## ServerAlias 20.197.236.31
       DocumentRoot /data/LattesData/PHP/public/
       &lt;Directory "/data/LattesData/PHP/public">
           Require all granted
       &lt;/Directory>
&lt;/VirtualHost>
</pre>

Aquivo /etc/apache2/sites-avaliable/deposito.conf
<tt>pico /etc/apache2/sites-available/deposito.conf</tt>
<pre>
&lt;VirtualHost *:82>
       ServerAdmin renefgj@gmail.com
       ServerName pocdadosabertos.inep.rnp.br
       ## ServerAlias 20.197.236.31
       DocumentRoot /data/LattesData/PHP7/public/
       &lt;Directory "/data/LattesData/PHP7/public">
           Require all granted
       &lt;/Directory>
&lt;/VirtualHost>
</pre>

<tt>a2ensite configuration.conf</tt><br>
<tt>a2ensite dvn.conf</tt>
<tt>a2ensite deposito.conf</tt>