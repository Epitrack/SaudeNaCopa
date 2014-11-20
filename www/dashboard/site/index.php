<?php
	include('inc/head.inc.php')
?>

<body data-screen="steps">

	<main id="main" role="main" class="main-steps">
		<div class="content-steps content-steps-intro">
			<p class="text-steps">
				O “SAÚDE NA COPA” é uma aplicação Web gratuita, projetada para utilização em 
				dispositivos móveis e navegadores de internet e faz parte do projeto inovador 
				de aprimoramento da vigilância em saúde no Sistema Único de Saúde (SUS), 
				denominado vigilância participativa.

				<br />
				<br />

				É um processo simples que conta com a participação voluntária de visitantes ou 
				residentes no Brasil, informando sobre sua condição de saúde, durante a Copa do 
				Mundo da FIFA Brasil 2014™. O “SAÚDE NA COPA” foi desenvolvido em português, 
				inglês e espanhol para ser um canal complementar de informação de saúde e de 
				serviços aos usuários, permitindo a participação de todos. Para isso, basta 
				ter 13 anos de idade ou mais e aceitar os termos de participação e uso, 
				informando regularmente sua condição de saúde, conforme instruções disponíveis 
				no aplicativo.

				<br />
				<br />

				Este projeto é uma iniciativa da Secretaria de Vigilância em Saúde do Ministério 
				da Saúde em parceria com as Secretarias de Saúde das sedes dos jogos e outras
				instituições nacionais e internacionais.
			</p>

			<figure class="img-instrucoes">
				<img src="<?php echo _BASEPATH . 'instrucoes.png'?>" alt="Instruções" />
			</figure>

			<div class="box-download">
				<a href="https://play.google.com/store/apps/details?id=br.com.epitrack.healthycup" target="_blank" title="Baixe o app pela Play Store" class="download-app android">Download na Play Store</a>
				<a href="https://itunes.apple.com/br/app/saude-na-copa/id860378564?mt=8&ign-mpt=uo%3D4" target="_blank" title="Baixe o app na Itunes Store" class="download-app iphone">Download na Itunes Store</a>
			</div>

			<a id="btn-webapp" href="#" class="webapp" title="Clique para participar pelo WebApp">Participe no WebApp</a>

			<a href="user" title="Clique para ir ao painel de monitoramento" class="link-dashboard">Ir para painel de monitoramento</a>
		</div>
	</main>

	<script>
		var btn = document.getElementById('btn-webapp'),
	        link = 'http://www.saudenacopa.com/webapp/',
	        config = "height=568,width=320";

	        function btnEventHandler(url) {
	            window.open(link, 'WebApp', config);
	            url.preventDefault();
	        }

	        btn.addEventListener('click', btnEventHandler, false);
	</script>

</body>
</html>