<pre>
mkdir /var/www/dataverse/
mkdir /var/www/dataverse/langBundles/
mkdir /var/www/dataverse/langTmp/
mkdir /var/www/dataverse/langTmp/sources/
mkdir /var/www/dataverse/langTmp/sources/pt_BR
mkdir /var/www/dataverse/langTmp/sources/All
echo "Baixando atualizações"
echo "==>Portugues"
cd /var/www/dataverse/langTmp/sources/pt_BR/
rm * -r
wget https://github.com/RNP-dadosabertos/dataverse-language-packs/archive/develop.zip
unzip develop.zip
echo "==>Outros Idiomas"
cd /var/www/dataverse/langTmp/sources/All/
rm * -r
wget https://github.com/GlobalDataverseCommunityConsortium/dataverse-language-packs/archive/refs/heads/develop.zip
unzip develop.zip
echo "Copiando os arquivos necessários"
rm /var/www/dataverse/langTmp/*.properties
rm /var/www/dataverse/langTmp/*.zip
echo "======================== Copy files =us--en_US"
cp /var/www/dataverse/langTmp/sources/All/dataverse-language-packs-develop/en_US/astrophysics.properties /var/www/dataverse/langTmp/astrophysics_us.properties
cp /var/www/dataverse/langTmp/sources/All/dataverse-language-packs-develop/en_US/biomedical.properties /var/www/dataverse/langTmp/biomedical_us.properties
cp /var/www/dataverse/langTmp/sources/All/dataverse-language-packs-develop/en_US/BuiltInRoles.properties /var/www/dataverse/langTmp/BuiltInRoles_us.properties
cp /var/www/dataverse/langTmp/sources/All/dataverse-language-packs-develop/en_US/Bundle.properties /var/www/dataverse/langTmp/Bundle_us.properties
cp /var/www/dataverse/langTmp/sources/All/dataverse-language-packs-develop/en_US/citation.properties /var/www/dataverse/langTmp/citation_us.properties
cp /var/www/dataverse/langTmp/sources/All/dataverse-language-packs-develop/en_US/geospatial.properties /var/www/dataverse/langTmp/geospatial_us.properties
cp /var/www/dataverse/langTmp/sources/All/dataverse-language-packs-develop/en_US/journal.properties /var/www/dataverse/langTmp/journal_us.properties
cp /var/www/dataverse/langTmp/sources/All/dataverse-language-packs-develop/en_US/MimeTypeDetectionByFileExtension.properties /var/www/dataverse/langTmp/MimeTypeDetectionByFileExtension_us.properties
cp /var/www/dataverse/langTmp/sources/All/dataverse-language-packs-develop/en_US/MimeTypeDisplay.properties /var/www/dataverse/langTmp/MimeTypeDisplay_us.properties
cp /var/www/dataverse/langTmp/sources/All/dataverse-language-packs-develop/en_US/MimeTypeFacets.properties /var/www/dataverse/langTmp/MimeTypeFacets_us.properties
cp /var/www/dataverse/langTmp/sources/All/dataverse-language-packs-develop/en_US/socialscience.properties /var/www/dataverse/langTmp/socialscience_us.properties
cp /var/www/dataverse/langTmp/sources/All/dataverse-language-packs-develop/en_US/ValidationMessages.properties /var/www/dataverse/langTmp/ValidationMessages_us.properties
echo "======================== Copy files =br--pt_BR"
cp /var/www/dataverse/langTmp/sources/pt_BR/dataverse-language-packs-develop/pt_BR/astrophysics_br.properties /var/www/dataverse/langTmp/astrophysics_br.properties
cp /var/www/dataverse/langTmp/sources/pt_BR/dataverse-language-packs-develop/pt_BR/biomedical_br.properties /var/www/dataverse/langTmp/biomedical_br.properties
cp /var/www/dataverse/langTmp/sources/pt_BR/dataverse-language-packs-develop/pt_BR/BuiltInRoles_br.properties /var/www/dataverse/langTmp/BuiltInRoles_br.properties
cp /var/www/dataverse/langTmp/sources/pt_BR/dataverse-language-packs-develop/pt_BR/Bundle_br.properties /var/www/dataverse/langTmp/Bundle_br.properties
cp /var/www/dataverse/langTmp/sources/pt_BR/dataverse-language-packs-develop/pt_BR/citation_br.properties /var/www/dataverse/langTmp/citation_br.properties
cp /var/www/dataverse/langTmp/sources/pt_BR/dataverse-language-packs-develop/pt_BR/geospatial_br.properties /var/www/dataverse/langTmp/geospatial_br.properties
cp /var/www/dataverse/langTmp/sources/pt_BR/dataverse-language-packs-develop/pt_BR/journal_br.properties /var/www/dataverse/langTmp/journal_br.properties
cp /var/www/dataverse/langTmp/sources/pt_BR/dataverse-language-packs-develop/pt_BR/MimeTypeDetectionByFileExtension_br.properties /var/www/dataverse/langTmp/MimeTypeDetectionByFileExtension_br.properties
cp /var/www/dataverse/langTmp/sources/pt_BR/dataverse-language-packs-develop/pt_BR/MimeTypeDisplay_br.properties /var/www/dataverse/langTmp/MimeTypeDisplay_br.properties
cp /var/www/dataverse/langTmp/sources/pt_BR/dataverse-language-packs-develop/pt_BR/MimeTypeFacets_br.properties /var/www/dataverse/langTmp/MimeTypeFacets_br.properties
cp /var/www/dataverse/langTmp/sources/pt_BR/dataverse-language-packs-develop/pt_BR/socialscience_br.properties /var/www/dataverse/langTmp/socialscience_br.properties
cp /var/www/dataverse/langTmp/sources/pt_BR/dataverse-language-packs-develop/pt_BR/ValidationMessages_br.properties /var/www/dataverse/langTmp/ValidationMessages_br.properties
echo "======================== Copy default files ="
cp /var/www/dataverse/langTmp/sources/pt_BR/dataverse-language-packs-develop/pt_BR/astrophysics_br.properties /var/www/dataverse/langTmp/astrophysics_en.properties
cp /var/www/dataverse/langTmp/sources/pt_BR/dataverse-language-packs-develop/pt_BR/biomedical_br.properties /var/www/dataverse/langTmp/biomedical_en.properties
cp /var/www/dataverse/langTmp/sources/pt_BR/dataverse-language-packs-develop/pt_BR/BuiltInRoles_br.properties /var/www/dataverse/langTmp/BuiltInRoles_en.properties
cp /var/www/dataverse/langTmp/sources/pt_BR/dataverse-language-packs-develop/pt_BR/Bundle_br.properties /var/www/dataverse/langTmp/Bundle_en.properties
cp /var/www/dataverse/langTmp/sources/pt_BR/dataverse-language-packs-develop/pt_BR/citation_br.properties /var/www/dataverse/langTmp/citation_en.properties
cp /var/www/dataverse/langTmp/sources/pt_BR/dataverse-language-packs-develop/pt_BR/geospatial_br.properties /var/www/dataverse/langTmp/geospatial_en.properties
cp /var/www/dataverse/langTmp/sources/pt_BR/dataverse-language-packs-develop/pt_BR/journal_br.properties /var/www/dataverse/langTmp/journal_en.properties
cp /var/www/dataverse/langTmp/sources/pt_BR/dataverse-language-packs-develop/pt_BR/MimeTypeDetectionByFileExtension_br.properties /var/www/dataverse/langTmp/MimeTypeDetectionByFileExtension_en.properties
cp /var/www/dataverse/langTmp/sources/pt_BR/dataverse-language-packs-develop/pt_BR/MimeTypeDisplay_br.properties /var/www/dataverse/langTmp/MimeTypeDisplay_en.properties
cp /var/www/dataverse/langTmp/sources/pt_BR/dataverse-language-packs-develop/pt_BR/MimeTypeFacets_br.properties /var/www/dataverse/langTmp/MimeTypeFacets_en.properties
cp /var/www/dataverse/langTmp/sources/pt_BR/dataverse-language-packs-develop/pt_BR/socialscience_br.properties /var/www/dataverse/langTmp/socialscience_en.properties
cp /var/www/dataverse/langTmp/sources/pt_BR/dataverse-language-packs-develop/pt_BR/ValidationMessages_br.properties /var/www/dataverse/langTmp/ValidationMessages_en.properties

echo "===>Preparing ZIP FILE"
cd /var/www/dataverse/langTmp/
rm *.zip
zip languages.zip *.properties
export PAYARA=/usr/local/payara5/glassfish
$PAYARA/bin/asadmin create-jvm-options '-Ddataverse.lang.directory=/var/www/dataverse/langBundles'
$PAYARA/bin/asadmin stop-domain
$PAYARA/bin/asadmin start-domain
curl http://localhost:8080/api/admin/datasetfield/loadpropertyfiles -X POST --upload-file languages.zip -H "Content-Type: application/zip"
echo "===>Definindo so idiomas do Dataverse e suas extensões"
curl http://localhost:8080/api/admin/settings/:Languages -X PUT -d '[{"locale":"en","title":"Idioma Padrão"}, {"locale":"us","title":"English"}, {"locale":"br","title":"Português"}]'
</pre>
