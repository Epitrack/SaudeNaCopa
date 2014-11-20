<?php
@session_start();
if(!isset($_SESSION["auth_session"]) || $_SESSION["auth_session"] == "") {
	header("Location: index.php");
}

include('inc/head.inc.php') ?>

<body data-screen="internal">

	<header class="header-primary">
		<?php include('inc/content-header.inc.php') ?>
		<?php include('inc/tab-filtros.php') ?>

		<div class="form-login">
			<h3 data-i18n="internal.title-internal" class="title-internal"></h3>

			<a href="logout.php" class="btn-logout" title="clique aqui para sair" data-i18n="internal.botaoSair"></a>
		</div>
	</header>

	<main id="main" role="main" class="main-primary">
		<?php include('inc/pins-mobile.inc.php') ?>
		<?php include('inc/box-main.inc.php') ?>

		<?php include('inc/map.inc.php') ?>

		<aside id="charts" class="charts">
			<div id="content-charts" class="content-charts">
				<div id="btn-charts" class="btn-charts"></div>

				<div class="graphic charts-city">
					<a href="modal.php" id="title-graphics" class="btn-graphics btn-graphics-modal" title="Clique para visualizar o relatório" data-i18n="graphics.title-sintoma"></a>
					<a href="../php/download_csv.php" id="title-download" class="btn-graphics btn-graphics-download" title="Clique para fazer o download do relatório" data-i18n="graphics.title-download"></a>
					<a href="javascript:;" id="saveMap" class="btn-graphics btn-graphics-save-map" title="Clique para salvar o mapa como imagem" data-i18n="graphics.salvarMapa"></a>
				</div>

				<div class="graphic charts-health">
					<h3 class="title-graphics" data-i18n="graphics.title-search-service">Procurou serviço de saúde.</h3>
					<div id="procurouServico" class="value-graphic"></div>
				</div>

				<div class="graphic charts-contact">
					<h3 class="title-graphics" data-i18n="graphics.title-contact-symptoms">Tive contato ou conheço alguém com algum desses sintomas nos últimos 7 dias.</h3>
					<div id="teveContato" class="value-graphic"></div>
				</div>

				<div class="graphic charts-peoples">
					<div class="chart-male">
						<figure class="avatar-user">
							<img src="<?php echo _BASEPATH . 'ico-male.png'?>" alt="icone de um avatar masculino" />
						</figure>
						<p id="valorHomem" class="value-peoples"></p>
					</div>

					<div class="chart-female">
						<figure class="avatar-user">
							<img src="<?php echo _BASEPATH . 'ico-female.png'?>" alt="icone de um avatar feminino" />
						</figure>
						<p id="valorMulher" class="value-peoples"></p>
					</div>
				</div>

				<div class="graphic charts-devices">
					<ul>
						<li id="desktops" class="desktops"></li>
						<li id="tablets" class="tablets"></li>
						<li id="smartphones" class="smartphones"></li>
						<li id="notebooks" class="notebooks"></li>
					</ul>
				</div>
			</div>
		</aside>
	</main>

<?php include('inc/footer.inc.php') ?>