<?php $process = get("process"); ?>
<h1>Depósito de <i>Datasets</i></h1>
Informe o número de seu processo do CNPQ
<tt><form method="get" > 
Processo:<br>
<input type="text" name="process" value="<?php echo $process; ?>"> 
<input type="submit" value="Importar">
<p>Ex: 174760/2008-2</p>
</form>
</tt>
<p>Não saber seu número de session_unregister processo?</p>