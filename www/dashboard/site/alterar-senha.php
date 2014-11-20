<?php include('inc/head.inc.php') ?>

<body data-screen="changePassword">

	<header class="header-primary">
		<?php include('inc/content-header.inc.php') ?>

		<div class="form-login">
			<h3 data-i18n="internal.title-internal" class="title-internal"></h3>
		</div>
	</header>

	<main id="main" role="main" class="main-primary secondary">
		<div class="content-main">
			<h2 data-i18n="changePass.title-change-pass" class="title-pattern"></h2>
			<div class="content-pattern">
				<p data-i18n="changePass.title-hello"></p>
				<br />

				<p data-i18n="changePass.sub-description"></p>

				<form action="javascript:;" id="newPass" class="new-pass">
					<input id="input-new-pass" data-i18n="[placeholder]changePass.novaSenha" placeholder="" type="password" class="input-primary large">
					<input id="input-confirm-pass" data-i18n="[placeholder]changePass.confirmarSenha" placeholder="" type="password" class="input-primary large">
					<input type="submit" id="submit" data-i18n="[value]changePass.mudarSenha" value="Mudar Senha" class="input-submit medium">
				</form>

				<div id="reply" class="reply"></div>
			</div>

			<div class="clear"></div>
		</div>
	</main>

<?php include('inc/footer.inc.php') ?>