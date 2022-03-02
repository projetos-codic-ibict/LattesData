<?php
$process = get('process');
if (!isset($erro)) { $erro = ''; }
?>
<div class="container-fluid bgc">
	<div class="row">
		<div class="col-2"></div>
		<div class="col-8 p-5">
			<div class="heroe" class="bgc">
				<h3><b>Bem vindo ao Integrador Carlos Chagas / LattesData</b></h3>
				<h5>Interoperabilidade multiplataforma</h5>
			</div>
		</div>
	</div>
</div>

<div class="container">
	<div class="row">
		<div class="col-2"></div>
		<div class="col-8 mt-5">
			<!-- CONTENT -->
			<section>
				<h5><b>Sobre a integração</b></h5>
				<p>Informando o número do processo, este sistemas busca inserir automáticamente os metadados do processo da Plataforma Carlos Chagas para o Dataverse.</p>
				<p>Criando os Dataverses e Datasets referente aos projetos. Para importação, informe o número do processo da bolsa do CNPq.</p>
				<pre><code><form method="get" > Processo: <input type="text" name="process" value="<?php echo $process; ?>"> <input type="submit" value="Importar"> Ex: 174760/2008-2</form></code></pre>
				<p style="color:red;"><b><?php echo $erro;?></b></p>
				<p>Etapas a serem realizadas</p>
				<ul>
					<li>Importação via API da Carlos Chagas</li>
					<li>Identificação dos metadados dos arquivos</li>
					<li>Criação dos arquivos .json para o Dataverse</li>
					<li>Submissão dos arquivos .json para o Dataverse</li>
					<li>Liberação de acesso ao sistema</li>
				</ul>
			</section>
		</div>
		<div class="col-2"></div>
	</div>
</div>
<a href="<?php echo URL.'home/dataverse/';?>" style="color: white; font-size: 6px;">[TT]</a>