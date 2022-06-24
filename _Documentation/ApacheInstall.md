<h1>Instalação do Apache/Proxy</h1>
<tt>apt-get install apache2</tt>

<h2>Installl PHP</h2>
<pre>
[root] 
sudo apt-get update
sudo apt-get install apache2 php libapache2-mod-php
sudo apt-get install php-soap php-xml php-curl php-opcache php-gd php-sqlite3 php-mbstring php-intl
sudo apt install php php-json php-curl php-mysql

sudo apt install mariadb-server
sudo systemctl enable --now mariadb

sudo mysql_secure_installation


[root] 
apt install php-intl php-mbstring php-xml php-curl

[root]
mkdir /data
cd /data
git clone https://github.com/lattesdata-ibict/LattesData.git

cd /data/LattesData/PHP7
cp env .env

cd /data/LattesData/PHP
cp env .env

chown www-data /data/LattesData/PHP7/ -R
chown www-data /data/LattesData/PHP/ -R
</pre>

Configuração do arquivo 000-default.conf

<pre>
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

</pre>

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

<pre>
a2ensite configuration.conf
a2ensite dvn.conf
a2ensite deposito.conf

service apache2 restart

cd /data/LattesData/PHP7
cp env .env

</pre>