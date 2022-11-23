
clear
echo "Definindo CSS"
curl -X PUT -d '/var/www/dataverse/branding/css/custom-stylesheet.css' http://localhost:8080/api/admin/settings/:StyleCustomizationFile
echo "Definindo Homepage"
curl -X PUT -d '/var/www/dataverse/branding/custom-homepage.html' http://localhost:8080/api/admin/settings/:HomePageCustomizationFile



