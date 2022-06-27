<?php
$q = array();
$w = array();
array_push($q, 'O que é o Repositório LattesData?');
array_push($w, 'É resultado do Compromisso 3 do 4º Plano de Ação Nacional que visa estabelecer mecanismos de governança de dados científicos para o avanço da Ciência Aberta no Brasil por meio de um Acordo de Cooperação assinado entre o Conselho Nacional de Desenvolvimento Científico e Tecnológico (CNPq) e o Instituto Brasileiro de Informação em Ciência e Tecnologia (Ibict).
<br>O LattesData tem como objetivo reunir, armazenar e divulgar os conjuntos de dados científicos de pesquisadores beneficiários CNPq. 
');

array_push($q, 'Quais serviços o Repositório LattesData oferece?');
array_push($w, 'O LattesData oferece os seguintes serviços: depósito e disponibilização de conjuntos de dados de pesquisa de projetos financiados pelo CNPq.');

array_push($q, 'Como eu posso recuperar a minha senha?');
array_push($w, 'Clique em “Esqueceu sua senha?”, depois você será direcionado para uma página de redefinição de senha da conta. Preencha com o e-mail cadastrado no LattesData e aperte em “Submeter pedido de senha”. Após isso, você receberá um e-mail para cadastrar uma nova senha.<br>Clique no link <a href="https://lattesdata.cnpq.br/loginpage.xhtml?redirectPage=%2Fdataverse_homepage.xhtml">https://lattesdata.cnpq.br/loginpage.xhtml?redirectPage=%2Fdataverse_homepage.xhtml</a> para acessar a página.');

//array_push($q, 'Como acessar o Repositório LattesData?');
//array_push($w, 'O Repositório está disponível para pesquisadores e sociedade em geral, podendo navegar, pesquisar os metadados e baixar os conteúdos que estiverem em acesso aberto no repositório.');

array_push($q, 'Quem pode acessar o Repositório LattesData?');
array_push($w, 'O Repositório está disponível para pesquisadores e sociedade em geral, podendo navegar, pesquisar os metadados e baixar os conteúdos que estiverem em acesso aberto no repositório.');

array_push($q, 'Quais tipos de dados são aceitos no LattesData?');
array_push($w, 'O LattesData aceita os seguintes tipos de dados: imagens, texto simples, texto estruturado, gráficos, bancos de dados, dados audiovisuais, dados bibliográficos, aplicações de software, gráficos estruturados, entre outros.');

array_push($q, 'Quais conteúdos são proibidos no LattesData?');
array_push($w, 'É proibido qualquer conteúdo de caráter difamatório, calunioso, injurioso, violento, pornográfico, obsceno, ofensivo ou ilícito conforme apuração do LattesData a seu critério exclusivo, inclusive informações de propriedade exclusiva pertencentes a outras pessoas ou empresas, sem a expressa autorização do titular desses direitos, cuja violação não será responsabilidade do LattesData.');

array_push($q, 'Há limite no tamanho de arquivo que eu vou submeter?');
array_push($w, 'Não há limite no tamanho do arquivo que você submeterá no LattesData, porém arquivos muito grandes podem sofrer esgotamento de tempo (<i>timeout</i>) em sua submissão.');

array_push($q, 'Com quem devo entrar em contato para obter outras informações sobre o LattesData?');
array_push($w, 'Caso você tenha alguma dúvida, comentário ou sugestão, por favor, entre em contato conosco por meio do e-mail lattesdata@cnpq.br');

$cr = chr(13);

?>
<h2>FAQ</h2>
<p>Nesta seção são divulgadas as perguntas frequentes sobre o Repositório LattesData do Conselho Nacional de Desenvolvimento Científico e Tecnológico - CNPq. </p>
<div id="demo-gen-faq" class="panel-group accordion">
    <?php
    for ($r = 0; $r < count($q); $r++) {
        echo '<div class="bord-no pad-top">' . $cr;
        echo '<!-- Question -->' . $cr;
        echo '<div class="faq-question"><a href="#demo-gen-faq' . ($r + 1) . '" data-toggle="collapse" data-parent="#demo-gen-faq">';
        echo $q[$r];
        echo '</a> </div>' . $cr;

        echo '
                <!-- Answer -->
                <div id="demo-gen-faq'.($r+1).'" class="collapse">
                    <div class="pad-all"><div class="1w">' . $w[$r] . '</div>.</div>
                </div>
                ';
        echo '</div>';
    }
    ?>
</div>