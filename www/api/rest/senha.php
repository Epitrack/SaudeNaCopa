<!DOCTYPE html>
<!-- saved from url=(0052)http://thulioph.com/trabalhos/epitrack/alterar-senha -->
<html xmlns:og="http://ogp.me/ns#" lang="pt-br" slick-uniqueid="3">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">

	<title>Epitrack | Saúde na Copa</title>
<base href="{{path}}"/>
	<!-- CSS -->
    <link rel="stylesheet" href="{{path}}/css/style.css">

    <!--[if lte IE 8]>
        <link rel="stylesheet" href="dist/css/ie.css">
        <script src="libs/js/create-elements.js"></script>
    <![endif]-->
<style id="clearly_highlighting_css" type="text/css">/* selection */ html.clearly_highlighting_enabled ::-moz-selection { background: rgba(246, 238, 150, 0.99); } html.clearly_highlighting_enabled ::selection { background: rgba(246, 238, 150, 0.99); } /* cursor */ html.clearly_highlighting_enabled {    /* cursor and hot-spot position -- requires a default cursor, after the URL one */    cursor: url("chrome-extension://pioclpoplcdbaefihamjohnefbikjilc/clearly/images/highlight--cursor.png") 14 16, text; } /* highlight tag */ em.clearly_highlight_element {    font-style: inherit !important; font-weight: inherit !important;    background-image: url("chrome-extension://pioclpoplcdbaefihamjohnefbikjilc/clearly/images/highlight--yellow.png");    background-repeat: repeat-x; background-position: top left; background-size: 100% 100%; } /* the delete-buttons are positioned relative to this */ em.clearly_highlight_element.clearly_highlight_first { position: relative; } /* delete buttons */ em.clearly_highlight_element a.clearly_highlight_delete_element {    display: none; cursor: pointer;    padding: 0; margin: 0; line-height: 0;    position: absolute; width: 34px; height: 34px; left: -17px; top: -17px;    background-image: url("chrome-extension://pioclpoplcdbaefihamjohnefbikjilc/clearly/images/highlight--delete-sprite.png"); background-repeat: no-repeat; background-position: 0px 0px; } em.clearly_highlight_element a.clearly_highlight_delete_element:hover { background-position: -34px 0px; } /* retina */ @media (min--moz-device-pixel-ratio: 2), (-webkit-min-device-pixel-ratio: 2), (min-device-pixel-ratio: 2) {    em.clearly_highlight_element { background-image: url("chrome-extension://pioclpoplcdbaefihamjohnefbikjilc/clearly/images/highlight--yellow@2x.png"); }    em.clearly_highlight_element a.clearly_highlight_delete_element { background-image: url("chrome-extension://pioclpoplcdbaefihamjohnefbikjilc/clearly/images/highlight--delete-sprite@2x.png"); background-size: 68px 34px; } } </style><style type="text/css"></style><style>[touch-action="none"]{ -ms-touch-action: none; touch-action: none; }[touch-action="pan-x"]{ -ms-touch-action: pan-x; touch-action: pan-x; }[touch-action="pan-y"]{ -ms-touch-action: pan-y; touch-action: pan-y; }[touch-action="scroll"],[touch-action="pan-x pan-y"],[touch-action="pan-y pan-x"]{ -ms-touch-action: pan-x pan-y; touch-action: pan-x pan-y; }</style>
</head>

<body data-screen="changePassword">

	<header class="header-primary">
		<div class="content-header">
			<h1 class="logo-primary" tabindex="1">
				<a href="#" class="sprite-logo-primary-br" title="Saúde na Copa 2014">Saúde na Copa 2014</a>
			</h1>
		</div>

		<div class="box-languages">
			<ul>
				<li><a href="javascript:;" class="sprite-language-br" title="BR">Brasileiro</a></li>
				<li><a href="javascript:;" class="sprite-language-es language-off" title="ES">Espanho</a></li>
				<li><a href="javascript:;" class="sprite-language-en language-off" title="EN">Inglês</a></li>
			</ul>
		</div>

		<div class="form-login">
			<h3 class="title-internal">Seja bem vindo</h3>
		</div>
	</header>

	<main id="main" role="main" class="main-primary secondary">
		<div class="content-main">
			<h2 class="title-pattern">
				Altere a sua senha
			</h2>
			<div class="content-pattern">
				Olá, {{nome}}
				<br>

				Preencha os campos abaixo para inserir uma nova senha.

				<form action="{{path}}/novaSenha/{{cod}}" id="newPass" class="new-pass">
					<input id="input-new-pass" placeholder="Nova Senha" type="password" name="senha" class="input-primary large">
					<input id="input-confirm-pass" placeholder="Confirme a Senha" type="password" class="input-primary large">
					<input type="submit" id="submit" value="Mudar Senha" class="input-submit medium">
				</form>

				<div id="reply" class="reply"><p class="send-error">As senhas digitadas não são iguais.</p></div>
			</div>
		</div>
		<div class="clear"></div>
	</main>

<footer class="footer-primary">
	<div class="content-footer">
		<nav class="nav-secondary">
			<ul>
				<li><a href="http://thulioph.com/trabalhos/epitrack/termos" title="Termos de Uso" class="nav-link">Termos de Uso</a></li>
				<li><a href="http://thulioph.com/trabalhos/epitrack/sobre" title="Sobre" class="nav-link">Sobre</a></li>
				<li><a href="http://thulioph.com/trabalhos/epitrack/reportar-problema" title="Reportar Problema" class="nav-link">Reportar Problema</a></li>
			</ul>
		</nav>
	</div>

	<div class="parceiros">
		<div class="img-parceiro">
			<a href="http://portalsaude.saude.gov.br/" title="Clique para ir ao site do SUS" target="_blank">
				<figure>
					<img src="{{path}}/img/parceiro-sus.png" alt="Logo SUS">
				</figure>
			</a>
		</div>

		<div class="img-parceiro">
			<a href="http://www.skollglobalthreats.org/" title="Clique para ir ao site da Skoll" target="_blank">
				<figure>
					<img src="{{path}}/img/parceiro-skoll.png" alt="Logo Skoll">
				</figure>
			</a>
		</div>

		<div class="img-parceiro">
			<a href="http://www.tephinet.org/" title="Clique para ir ao site da Tephinet" target="_blank">
				<figure>
					<img src="{{path}}/img/parceiro-tephinet.png" alt="Logo Tephinet">
				</figure>
			</a>
		</div>
	</div>
</footer>

<!-- libs - start -->
<script src="{{path}}/js/jquery-2.1.0.min.js"></script>  


 	<script>
 	 

		$('#reply').hide();

		$('#submit').on("click",onClick);

		function onClick(bt){

			var senha = $('#input-new-pass').val();
			var confirma = $('#input-confirm-pass').val();

			if(senha!="" ){
				if(senha === confirma){
				

				$('#newPass').submit();
				$('#reply').hide();
				}else{
					$('#reply').show();
					$('#reply').html("As senhas digitadas não são iguais.")
				}
			}else{
				$('#reply').show();
				$('#reply').html("Digite a nova senha.")
			}
			
		}

	 </script>
<!-- <script src="/js/main.js"></script> -->



</body></html>