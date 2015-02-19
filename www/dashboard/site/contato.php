<?php 
@session_start();
include('inc/head.inc.php') ?>

<body data-screen="contact">

	<header class="header-primary">
		<?php include('inc/content-header.inc.php') ?>

		<div class="form-login">
			<h3 data-i18n="internal.title-internal" class="title-internal"></h3>
		</div>
	</header>

	<main id="main" role="main" class="main-primary secondary">
		<div class="content-main">
			<h2 data-i18n="internal.title-contato" class="title-pattern">
			</h2>

			<div class="content-pattern secondary">

				<form action="javascript:;" name="formContact" id="formContact">
					<input type="text" name="inputContactName" id="inputContactName" class="input-primary large" data-i18n="[placeholder]contato.nome" placeholder="" >
					<input type="email" name="inputContactEmail" id="inputContactEmail" class="input-primary large" data-i18n="[placeholder]contato.email" placeholder="" required oninvalid="setCustomValidity('Por favor, preencha o Email de forma válida')" onchange="try{setCustomValidity('')}catch(e){}"/>
					<input type="tel" name="inputContactTel" id="inputContactTel" class="input-primary large" data-i18n="[placeholder]contato.telefone" placeholder="" >
					<input type="text" name="inputContactSubject" id="inputContactSubject" class="input-primary large" data-i18n="[placeholder]contato.assunto" placeholder="" />
					<textarea class="textarea secondary" name="textareaContact" id="textareaContact" data-i18n="[placeholder]contato.mensagem" placeholder="" required oninvalid="setCustomValidity('Por favor, preencha a Mensagem')" onchange="try{setCustomValidity('')}catch(e){}"></textarea>

          			<div class="recaptcha contact">
          				<img src='captcha.jpg' />
          				<label for="">
          					<p data-i18n="contato.captcha"></p>
          					<input id="security_code" name="security_code" type="text" />
          				</label>
          			</div>

					<input type="submit" data-i18n="[value]contato.enviarContato" value="" class="input-submit submit-problem">
				</form>

				<p id="reply-problem" class="reply-problem send-ok" data-i18n="internal.send-success">
				</p>
			</div>

			<div class="clear"></div>
		</div>
	</main>

<?php include('inc/footer.inc.php') ?>