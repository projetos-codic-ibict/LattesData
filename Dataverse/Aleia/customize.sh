echo "DOI"
echo "Atribui DOI para cada dataset, sem gerar para arquivos"
curl -X PUT -d 'false' http://localhost:8080/api/admin/settings/:FilePIDsEnabled

curl -X PUT -d doi http://localhost:8080/api/admin/settings/:Protocol
curl -X PUT -d "aleia/" http://localhost:8080/api/admin/settings/:Shoulder
curl -X PUT -d 10.48472 http://localhost:8080/api/admin/settings/:Authority

echo "################# PAGES"
./update-pages.sh

cp logo.png /usr/local/payara5/glassfish/domains/domain1/docroot/logos/navbar/.
curl -X PUT -d '/logos/navbar/logo.png' http://localhost:8080/api/admin/settings/:LogoCustomizationFile
echo "Customizando página inicial"
curl -X PUT -d '/var/www/dataverse/branding/custom-homepage.html' http://localhost:8080/api/admin/settings/:HomePageCustomizationFile
curl -X PUT -d '/var/www/dataverse/branding/custom-stylesheet.css' http://localhost:8080/api/admin/settings/:StyleCustomizationFile
curl -X PUT -d '/var/www/dataverse/branding/custom-footer.html' http://localhost:8080/api/admin/settings/:FooterCustomizationFile

echo "e-mail"
curl -X PUT -d 'LattesData <lattesdata@cnpq.br>' http://localhost:8080/api/admin/settings/:SystemEmail
curl -X PUT -d true http://localhost:8080/api/admin/settings/:SendNotificationOnDatasetCreation