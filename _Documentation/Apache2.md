<h1>Apache 2</h1>
apt install apache2

sudo apt-get update
sudo apt-get install apache2 php libapache2-mod-php
sudo apt-get install php-soap php-xml php-curl php-opcache php-gd php-sqlite3 php-mbstring php-intl
sudo apt install php php-json php-mysql

##Habilitando os MODs do proxy
sudo a2enmod ssl
sudo a2enmod proxy
sudo a2enmod proxy_http
sudo a2enmod proxy_balancer
sudo a2enmod lbmethod_byrequests

pico /etc/apache2/ports.conf
Listen 81
Listen 82
Listen 83
Listen 84

pico /etc/apache2/sites-available/000-default.conf

        <Location />
              ProxyPass http://localhost:8080/
              SetEnv force-proxy-request-1.0 1
              SetEnv proxy-nokeepalive 1
        </Location>

        <Location /config>
              ProxyPass http://localhost:81
              SetEnv force-proxy-request-1.0 1
              SetEnv proxy-nokeepalive 1
        </Location>



pico /etc/apache2/sites-available/config.conf

<VirtualHost *:81>
       ServerAdmin renefgj@gmail.com
       ServerName aleia.ibict.br
       DocumentRoot /data/LattesData/PHP/public/
       <Directory "/data/LattesData/PHP/public/">
           Require all granted
       </Directory>
</VirtualHost>

pico /etc/apache2/sites-available/dvn.conf

<VirtualHost *:88>
       ServerAdmin renefgj@gmail.com
       ServerName aleia.ibict.br
       DocumentRoot /var/www/dataverse/branding/
       <Directory "/var/www/dataverse/branding/">
           Require all granted
       </Directory>
</VirtualHost>


a2ensite dvn
a2ensite config
