# LattesData
<h1>Projeto LattesData</h1>

Para instalar:

a) Usando o GIT CLONE

<h3>Instalar o MariaDB</h3>
$apt install mariadb-server

<h3>Instalando Aplicativo</h3>

$git clone https://github.com/lattesdata-ibict/LattesData.git
$cd LattesData/PHP
$cp env .env

$nano .env
Alterar
#app.baseURL = ''
para
app.baseURL = 'http://<seu IP>'

database.default.hostname = localhost
database.default.database = lattesdata
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.DBPrefix =

database.schema.hostname = localhost
database.schema.database = lattesdata
database.schema.username = root
database.schema.password =
database.schema.DBDriver = MySQLi
database.schema.DBPrefix =






Projeto de integração entre o DataVerse e os dados dos pesquisadores produtividade do CNPq

<a href="_Documentação/CNPq/API.md">CNPq API</a>

<h3>Workflow</h3>
<a href="_Documentação/Workflow/workflow_PQ.md">Bolsista Produtividade</a>

<a href="http://200.130.0.214:8080/">Acesso externo ao protótipo do LattesData</a>

<h3>Chave SSL para Consulta</h3>
<a href="_Documentação/CertificadoSSL.md">Chave SSL para CURL</a>

