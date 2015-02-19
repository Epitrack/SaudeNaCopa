<div class="box-main info-search">
	<div id="tab-cidades" class="tab-cidades">
		<div id="btn-cidades" class="btn-cidades" title="Clique para escolher as cidades"></div>
		<?php include('inc/tab-cidades.inc.php') ?>
	</div>

	<div class="tabs">
		<div id="tabs">
			<div class="tab">
				<?php include('inc/tab-sintomas.inc.php') ?>
			</div>
		</div>

		<div class="legend-sintomas">
			<h3 data-i18n="legendas.titulo" class="subtitle"></h3>
			<ul>
				<li data-i18n="legendas.voce" class="legend-you"></li>
				<li data-i18n="legendas.muitoBem" class="legend-muito-bem"></li>
				<li data-i18n="legendas.bem" class="legend-bem"></li>
				<li data-i18n="legendas.mal" class="legend-mal"></li>
				<li data-i18n="legendas.muitoMal" class="legend-muito-mal"></li>
			</ul>
		</div>
	</div>
</div>