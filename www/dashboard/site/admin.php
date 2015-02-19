<?php include('inc/head.inc.php') ?>

<body data-screen="home">

	<header class="header-primary">
		<?php include('inc/content-header.inc.php') ?>

		<div class="form-login">
			<form action="javascript:;" id="formPrimary" class="form-primary">
				<fieldset>
					<label data-i18n="login.login" for="login"></label>
					<input type="email" tabindex="2" data-i18n="[placeholder]login.login" placeholder="" name="email" id="email" class="input-primary medium">
				</fieldset>

				<fieldset>
					<label data-i18n="login.senha" for="senha"></label>
					<input type="password" tabindex="3" data-i18n="[placeholder]login.senha" placeholder="" name="senha" id="senha" class="input-primary medium">
				</fieldset>

				<input tabindex="4" type="submit" id="submit" name="submit" title="Clique para fazer o login" value="OK" class="input-submit medium">
			</form>

			<div id="respostaLogin" class="resposta-login"></div>
		</div>
	</header>

	<main id="main" role="main" class="main-primary">
		<?php include('inc/pins-mobile.inc.php') ?>
		<?php include('inc/box-main.inc.php') ?>

		<?php include('inc/map.inc.php') ?>
	</main>

<?php include('inc/footer.inc.php') ?>