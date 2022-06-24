<h1>Instalação do Apache/Proxy</h1>
<tt>apt-get install apache2</tt>

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

<VirtualHost *:81>
       ServerAdmin renefgj@gmail.com
       ServerName pocdadosabertos.inep.rnp.br
       ## ServerAlias 20.197.236.31
       DocumentRoot /var/www/dataverse/branding
       <Directory "/var/www/dataverse/brandingx">
           Require all granted
       </Directory>
</VirtualHost>

Aquivo /etc/apache2/sites-avaliable/configuration.conf
<tt>pico /etc/apache2/sites-available/configuration.conf</tt>
<pre>
<VirtualHost *:84>
       ServerAdmin renefgj@gmail.com
       ServerName pocdadosabertos.inep.rnp.br
       ## ServerAlias 20.197.236.31
       DocumentRoot /data/LattesData/PHP/public/
       <Directory "/data/LattesData/PHP/public">
           Require all granted
       </Directory>
</VirtualHost>
</pre>

Aquivo /etc/apache2/sites-avaliable/deposito.conf
<tt>pico /etc/apache2/sites-available/deposito.conf</tt>
<pre>
<VirtualHost *:82>
       ServerAdmin renefgj@gmail.com
       ServerName pocdadosabertos.inep.rnp.br
       ## ServerAlias 20.197.236.31
       DocumentRoot /data/LattesData/PHP7/public/
       <Directory "/data/LattesData/PHP7/public">
           Require all granted
       </Directory>
</VirtualHost>
</pre>

<tt>a2ensite configuration.conf</tt><br>
<tt>a2ensite dvn.conf</tt>
<tt>a2ensite deposito.conf</tt>