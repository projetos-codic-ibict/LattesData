clear
echo "Atualizando GITHUB"
git pull

echo "################################################################################################"
echo "$1"
if [ -d /var/www/dataverse/branding/assets ]
then
    echo "Diretório existe Assets (OK)"
else
    mkdir /var/www/dataverse/branding/assets
fi

if [ -d /var/www/dataverse/branding/css/ ]
then
        echo "Diretório CSS (Ok)"
else
        mkdir /var/www/dataverse/branding/css/
fi

if [ -d /var/www/dataverse/branding/about/ ]
then
        echo "Diretório Sobre"
else
        mkdir /var/www/dataverse/branding/aboout/
fi

echo "Copiando Assets"
cp assets/* /var/www/dataverse/branding/assets/. -R
echo "Copiando CSS"
cp custom-stylesheet.css /var/www/dataverse/branding/css/.
echo "Copiando Custom Header"
cp custom-header.html /var/www/dataverse/branding/.
cp custom-header.html /var/www/dataverse/branding/about/.
echo "Copiando Custom HomePage"
cp custom-homepage.html /var/www/dataverse/branding/.
echo "Copiando Sobre"
cp about/* /var/www/dataverse/branding/about/. -R
