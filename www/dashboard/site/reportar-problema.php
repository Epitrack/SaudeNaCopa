<?php include('inc/head.inc.php') ?>

<body data-screen="problem">

	<header class="header-primary">
		<?php include('inc/content-header.inc.php') ?>

		<div class="form-login">
			<h3 data-i18n="internal.title-internal" class="title-internal"></h3>
		</div>
	</header>

	<main id="main" role="main" class="main-primary secondary">
		<div class="content-main">
			<h2 data-i18n="internal.title-reportar-problema" class="title-pattern">
			</h2>

			<div class="content-pattern">
				<form action="javascript:;" name="reportProblem" id="reportProblem">
					<textarea data-i18n="[placeholder]reportarProblema.textArea" id="textAreaProblem" name="textAreaProblem" class="textarea" placeholder="" required oninvalid="setCustomValidity('Por favor, preencha a Mensagem')" onchange="try{setCustomValidity('')}catch(e){}"></textarea>

					<div class="recaptcha report-problem">
          				<img src='captcha.jpg' />
          				<label for="">
          					<p data-i18n="reportarProblema.captcha"></p>
          					<input id="security_code" name="security_code" type="text" />
          				</label>
          			</div>

          			<div class="clear"></div>

					<input type="submit" data-i18n="[value]reportarProblema.enviar" value="" class="input-submit submit-problem">
				</form>

				<p id="reply-problem" class="reply-problem send-ok" data-i18n="internal.send-success"></p>
			</div>

			<div class="clear"></div>
		</div>
	</main>

<?php include('inc/footer.inc.php') ?>