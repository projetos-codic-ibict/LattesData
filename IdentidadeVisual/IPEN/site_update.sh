echo "$1"
if [-d /var/www/dataverse/branding/assets]
then
    echo "Diretório existe"
else
    echo "Diretório não existe"
    mkdir /var/www/dataverse/branding/assets
fi

cp assets/* /var/www/dataverse/branding/assets
cp custom-stylesheet.css /var/www/dataverse/branding/.