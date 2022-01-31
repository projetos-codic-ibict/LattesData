echo off

echo off
echo "Language"
mkdir php\app\Language\pt-BR
copy ..\Brapci3.0\app\Language\pt-BR\social.* php\app\Language\pt-BR\*.* 

echo "Copiando Helper"
copy ..\Brapci3.0\app\Helpers\*.* php\app\Helpers\*.* 
copy ..\Brapci3.0\app\Models\Social*.* php\app\Models\*.*

#echo "RDP"
#mkdir php\app\Models\Rdf

#copy ..\Brapci3.0\app\Models\RDF\RDF*.php php\app\Models\RDF\*.* 

#echo "Images"
#copy ..\Brapci3.0\app\Models\Images.php php\app\Models\*.* 

echo "IO"
mkdir php\app\Models\Io
copy ..\Brapci3.0\app\Models\Io\*.php php\app\Models\Io\*.* 

echo "Dataverse"
mkdir php\app\Models\Dataverse
copy ..\Brapci3.0\app\Models\Dataverse\*.php php\app\Models\Dataverse\*.* 

echo "Lattes"
mkdir php\app\Models\Lattes
copy ..\Brapci3.0\app\Models\Lattes\*.php php\app\Models\Lattes\*.* 
