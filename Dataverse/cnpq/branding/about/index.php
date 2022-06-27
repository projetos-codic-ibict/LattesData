<?xml version='1.0' encoding='UTF-8' ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head id="j_idt5">
<title>Lattes Data</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<link type="text/css" rel="stylesheet" href="/javax.faces.resource/theme.css.xhtml?ln=primefaces-bootstrap&amp;v=10.0.0" />
<link type="text/css" rel="stylesheet" href="/javax.faces.resource/primeicons/primeicons.css.xhtml?ln=primefaces&amp;v=10.0.0" />
<script type="text/javascript" src="/javax.faces.resource/omnifaces.js.xhtml?ln=omnifaces&amp;v=3.8"></script><script type="text/javascript" src="/javax.faces.resource/jquery/jquery.js.xhtml?ln=primefaces&amp;v=10.0.0"></script><script type="text/javascript" src="/javax.faces.resource/jquery/jquery-plugins.js.xhtml?ln=primefaces&amp;v=10.0.0"></script><script type="text/javascript" src="/javax.faces.resource/core.js.xhtml?ln=primefaces&amp;v=10.0.0"></script><script type="text/javascript" src="/javax.faces.resource/components.js.xhtml?ln=primefaces&amp;v=10.0.0"></script>
<link rel="apple-touch-icon" sizes="180x180" href="/javax.faces.resource/images/fav/apple-touch-icon.png.xhtml" />
<link rel="icon" type="image/png" sizes="16x16" href="/javax.faces.resource/images/fav/favicon-16x16.png.xhtml" />
<link rel="icon" type="image/png" sizes="32x32" href="/javax.faces.resource/images/fav/favicon-32x32.png.xhtml" />
<link rel="manifest" href="/javax.faces.resource/images/fav/site.webmanifest.xhtml" />
<link rel="mask-icon" href="/javax.faces.resource/images/fav/safari-pinned-tab.svg.xhtml" color="#da532c" />
<meta name="msapplication-TileColor" content="#da532c" />
<meta name="theme-color" content="#ffffff" />
<link type="image/png" rel="image_src" href="/javax.faces.resource/images/dataverseproject.png.xhtml" />
<link type="text/css" rel="stylesheet" href="/javax.faces.resource/bs/css/bootstrap.min.css.xhtml?version=5.10" />
<link type="text/css" rel="stylesheet" href="/javax.faces.resource/bs/css/bootstrap.min.css.xhtml?version=5.10" />
<link type="text/css" rel="stylesheet" href="/javax.faces.resource/css/ie-compat.css.xhtml?version=5.10" />
<link type="text/css" rel="stylesheet" href="/javax.faces.resource/css/owl.carousel.css.xhtml?version=5.10" />
<link type="text/css" rel="stylesheet" href="/javax.faces.resource/css/fontcustom.css.xhtml?version=5.10" />
<link type="text/css" rel="stylesheet" href="/javax.faces.resource/css/socicon.css.xhtml?version=5.10" />
<link type="text/css" rel="stylesheet" href="/javax.faces.resource/css/structure.css.xhtml?version=5.10" />
<link type="text/css" rel="stylesheet" href="../custom-stylesheet.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js" type="text/javascript"></script> 
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js" type="text/javascript"></script> 
<script type="text/javascript">
    $(document).ready(function () {

            $('#myTab a').click(function (e) {
                e.preventDefault()
                $(this).tab('show')
            })

            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                e.relatedTarget // previous tab
            });
        });
</script>
<script src="https://kit.fontawesome.com/b387d4989b.js" crossorigin="anonymous"></script>    
</head>
<body>
<a href="#content" class="sr-only">Skip to main content</a>
<div class="headerCustom" style="width: 100%; z-index: 100;">
  <div class="container">
  <div class="row justify-content-between">
    <div class="col-xs-8">
        <a href="https://www.gov.br/cnpq/pt-br" target="_blank">
            <img src="/dvn/img/logo-cnpq-white.png" alt=""/>
        </a>
        <a href="/">
            <img src="/dvn/img/logo-lattes-white.png" alt=""/>
        </a> <a href="#"></a>
      </div>
    <div class="col-xs-4 offset-xs-4 text-right deposite"><a href="#"><i class="fa-solid fa-square-arrow-up-right fa-lg"></i>Deposite os seus conjuntos de dados</a></div>
  </div>
</div>
</div>
<nav id="dataverse-header-block">
  <div id="navbarFixed" class="navbar navbar-default navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#topNavBar" aria-pressed="false"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      </div>
      <div class="collapse navbar-collapse" id="topNavBar">
        <ul class="nav navbar-nav navbar-right">
          <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown">Pesquisa <b class="caret"></b></a>
            <ul class="dropdown-menu navbar-search">
              <li>
                <form class="form-inline" role="search">
                  <label id="searchNavLabel" class="sr-only" for="navbarsearch">Pesquisa</label>
                  <div class="input-group">
                    <input id="navbarsearch" type="text" class="form-control" size="28" value="" placeholder="Pesquisa de todos os dataverses ...">
                    <span class="input-group-btn">
                    <button type="submit" title="" class="btn btn-default bootstrap-button-tooltip" aria-labelledby="searchNavLabel" onclick="window.location='/dataverse/lattesdata?q=' + document.getElementById('navbarsearch').value;return false;" data-original-title="Localizar"> <span class="glyphicon glyphicon-search no-text"></span> </button>
                    </span> </div>
                </form>
              </li>
            </ul>
          </li>
          <li><a href="/dvn/about/" rel="noopener" target="_blank">Sobre</a></li>
          <li><a href="https://guides.dataverse.org/en/5.10/user" rel="noopener" target="_blank">Guia do usuário</a> </li>
          <li>
            <form id="j_idt49" name="j_idt49" method="post" action="/" class="navbar-form navbar-left navbar-form-link" enctype="application/x-www-form-urlencoded" data-partialsubmit="true">
              <input type="hidden" name="j_idt49" value="j_idt49">
              <a id="j_idt49:headerSupportLink" href="#" class="ui-commandlink ui-widget" onclick="PrimeFaces.ab({s:&quot;j_idt49:headerSupportLink&quot;,f:&quot;j_idt49&quot;,u:&quot;contactDialog&quot;,onco:function(xhr,status,args,data){PF('contactForm').show();}});return false;">Suporte</a>
              <input type="hidden" name="javax.faces.ViewState" id="j_id1:javax.faces.ViewState:0" value="-7140079024628492041:1086366191930500317" autocomplete="off">
            </form>
          </li>
          <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Idioma">Português <b class="caret"></b></a>
            <ul class="dropdown-menu navbar-language">
              <form id="j_idt54" name="j_idt54" method="post" action="/" class="form-inline" enctype="application/x-www-form-urlencoded">
                <input type="hidden" name="j_idt54" value="j_idt54">
                <li><script type="text/javascript" src="/javax.faces.resource/jsf.js.xhtml?ln=javax.faces"></script><a href="#" onclick="mojarra.jsfcljs(document.getElementById('j_idt54'),{'j_idt54:j_idt55:0:j_idt57':'j_idt54:j_idt55:0:j_idt57'},'');return false" class="language-menu-link">English</a> </li>
                <li class="active"><a href="#" onclick="mojarra.jsfcljs(document.getElementById('j_idt54'),{'j_idt54:j_idt55:1:j_idt57':'j_idt54:j_idt55:1:j_idt57'},'');return false" class="language-menu-link">Português</a> </li>
                <input type="hidden" name="javax.faces.ViewState" id="j_id1:javax.faces.ViewState:1" value="-7140079024628492041:1086366191930500317" autocomplete="off">
              </form>
            </ul>
          </li>
          <li> </li>
          <li><a href="/loginpage.xhtml?redirectPage=%2Fdataverse_homepage.xhtml"> Iniciar sessão </a> </li>
        </ul>
      </div>
    </div>
  </div>
  <noscript>
  <div id="noscript-alert" class="bg-danger">
    <div class="alert container text-danger no-margin-bottom">Please enable JavaScript in your browser. It is required to use most of the features of Dataverse. </div>
  </div>
  </noscript>
  <form id="j_idt103" name="j_idt103" method="post" action="/;jsessionid=89465f8b9ae4a2399195b4c9e24b" class="form-inline" enctype="application/x-www-form-urlencoded" data-partialsubmit="true">
    <input type="hidden" name="j_idt103" value="j_idt103">
    <input type="hidden" name="javax.faces.ViewState" id="j_id1:javax.faces.ViewState:2" value="-7893747309819969496:-2444764390170307798" autocomplete="off">
  </form>
  <div style="background:#000000;" id="dataverseHeader" class="container bg-muted">
    <div style="text-align:LEFT;background:#FFFFFF;" class="dataverseHeaderLogo"> <img src="/dvn/img/logo_lattesdata_200.png"></div>
    <div class="dataverseHeaderBlock">
      <div class="dataverseHeaderCell dataverseHeaderName"> <a href="/dataverse/lattesdata" class="dataverseHeaderDataverseName" style="color:#428BCA;">
        <h1>Lattes Data</h1>
        </a></div>
    </div>
  </div>
  <div id="messagePanel">
    <div class="container messagePanel"> </div>
  </div>
</nav>
<div class="container" id="content" role="main">
  <div class="row-fluid">
    <ul class="nav nav-tabs mt-4" id="myTab" role="tablist">
      <li role="presentation" class="active" > <a aria-controls="home" aria-expanded="true" data-toggle="tab" href="#home" id="home-tab" role="tab">Histórico</a> </li>
      <li role="presentation" class=""> <a aria-controls="politica" aria-expanded="false" data-toggle="tab" href="#politica" id="politica-tab" role="tab" >Política do Repositório</a> </li>
      <li role="presentation" class="" > <a aria-controls="faq" aria-expanded="false" data-toggle="tab" href="#faq" id="faq-tab" >FAQ</a> </li>
      <li role="presentation" class="" > <a aria-controls="contato" aria-expanded="false" data-toggle="tab" href="#contato" id="contato-tab" >Contato</a> </li>
    </ul>
    <div class="col">
      <div class="tab-content" id="myTabContent">
        <div aria-labelledby="home-tab" class="tab-pane fade active in" id="home" role="tabpanel">
          <?php require("historico.php");?>
        </div>
        <div aria-labelledby="politica-tab" class="tab-pane fade" id="politica" role="tabpanel">
          <?php require("politica.php");?> 
        </div>
        <div aria-labelledby="faq-tab" class="tab-pane fade" id="faq" role="tabpanel">
          <?php include("faq.php");?>
        </div>
        <div aria-labelledby="contato-tab" class="tab-pane fade" id="contato" role="tabpanel">
          <?php require("contato.php");?>        
        </div>
      </div>
    </div>
  </div>
</div>
<footer>
  <div id="dvfooter">
    <div class="container">
      <div class="row">
        <div class="col-sm-8 small">
          <p>Copyright &#169; 2022, CNPq/Ibict </p>
        </div>
        <div class="col-sm-4 text-right">
          <div class="poweredbylogo"> <span>Powered by</span> <a href="http://dataverse.org/" title="The Dataverse Project" target="_blank" rel="noopener"><img src="/resources/images/dataverse_project_logo.svg" width="118" height="40" alt="The Dataverse Project logo" /></a></div>
        </div>
      </div>
    </div>
  </div>
  
<div class="custom-footer">
  <div class="container">
    <div class="row">
      <div class="col-sm-3">
        <h6> Assuntos </h6>
        <ul class="list-unstyled">
          <li><a href="https://www.gov.br/cnpq/pt-br/assuntos/noticias" target="_blank">Notícias</a></li>
          <li><a href="https://www.gov.br/cnpq/pt-br/assuntos/popularizacao-da-ciencia" target="_blank">Popularização da ciência</a></li>
          <li><a href="https://www.gov.br/cnpq/pt-br/assuntos/centro-de-memoria" target="_blank">Memória</a></li>
        </ul>
      </div>
      <div class="col-sm-3">
        <h6> Acesso à Informação </h6>
        <ul class="list-unstyled">
          <li><a href="https://www.gov.br/cnpq/pt-br/acesso-a-informacao/institucional">Institucional</a></li>
          <li><a href="https://www.gov.br/cnpq/pt-br/acesso-a-informacao/acoes-e-programas" target="_blank">Ações e Programas</a></li>
          <li><a href="https://www.gov.br/cnpq/pt-br/acesso-a-informacao/bolsas-e-auxilios" target="_blank">Bolsas e Auxílios</a></li>
          <li><a href="https://www.gov.br/cnpq/pt-br/acesso-a-informacao/dados-abertos" target="_blank">Dados Abertos</a></li>
          <li><a href="http://memoria2.cnpq.br/web/guest/perguntas-frequentes1" target="_blank">Perguntas Frequentes</a></li>
        </ul>
      </div>
      <div class="col-sm-3">
        <h6> Canais de Atendimento</h6>
        <ul class="list-unstyled">
          <li><a href="https://www.gov.br/cnpq/pt-br/canais_atendimento/fale-conosco" target="_blank">Central de atendimento</a></li>
          <li>Contato </li>
          <li>Suporte </li>
        </ul>
      </div>
      <div class="col-sm-3">
        <h6>Proteção de dados</h6>
        <ul class="list-unstyled">
          <li><a href="https://lattesdata.cnpq.br/dvn/about/#politica">Política do LattesData</a></li>
          <li>Aviso de privacidade</li>
        </ul>
      </div>
    </div>
    <div class="row">
      <div class="col-12 text-left">          
          <a href="https://twitter.com/CNPq_Oficial" target="_blank"><i class="fa-brands fa-twitter fa-lg"></i></a>
          <a href="https://www.youtube.com/CNPqOficial" target="_blank"><i class="fa-brands fa-youtube fa-lg"></i></a>
          <a href="https://www.facebook.com/cnpqoficial" target="_blank"><i class="fa-brands fa-facebook fa-lg"></i></a>
          <a href="https://www.instagram.com/cnpq_oficial/" target="_blank"> <i class="fa-brands fa-instagram fa-lg"></i></a>
          <a href="https://www.flickr.com/photos/cnpqoficial" target="_blank"><i class="fa-brands fa-flickr fa-lg"></i></a>
        </div>
    </div>
  </div>
</div>

</footer>
</body>
</html>