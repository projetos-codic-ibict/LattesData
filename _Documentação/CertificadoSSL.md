<h2>Para criar um certificado</h2>

<p>Crie uma pasta no projeto com o nome ".ssl" ex: mkdir /data/html/lattes/.ssl

<p>Acesse a página e crie uma chave
openssl req -newkey rsa:2048 -new -nodes -x509 -days 3650 -keyout key.pem -out cert.pem

<p>https://support.microfocus.com/kb/doc.php?id=7013103</p>

Altere no arquivo .env a localização da chave, ex:
CURL_SSL = /data/html/lattes/.ssl/cert.pem