var APP = APP || {};
APP.GerenciadorDeTelas = APP.GerenciadorDeTelas || {};



APP.GerenciadorDeTelas.setUpCallback = function() {
	//APP.GerenciadorDeTelas._IScroll = new IScroll("#scroller");
};

APP.GerenciadorDeTelas.newVoltar = function() {
	this.exibir("#"+APP.GerenciadorDeTelas._historico.pop());
}


APP.GerenciadorDeTelas.exibirCallback = function(tela) {
	var primeiraTela, idArea, preCarregamento, posCarregamento, rolagem, 
		body = $(document.body),
		PrimeiraVez = APP.Area.Acesso.PrimeiraVez, telasSemAlertaOffline;

	APP.analytics.trackView(tela.id.split("tela_")[1]);
	

	//Troca o título da tela
	//APP.Barra.Titulo.definir(tela.getAttribute('data-titulo'));
	APP.Barra.Titulo.definir(i18n.t(tela.id.split("tela_").join("tela.")));
	

	//Esconde a barra de titulo e de menu, se necessário
	idArea = tela.parentNode.id; 
	areasSemAlertaOffline = ["area_saude", "area_menu", "area_mais", "area_apresentacao"];
	if($.inArray(idArea, areasSemAlertaOffline) > -1) {
		APP.Offline.esconder();
	} else if(!APP.Offline._onLine && $.inArray(idArea, areasSemAlertaOffline) == -1) {
		APP.Offline.mostrar();
	}

	if(idArea == "area_apresentacao" || idArea == "area_acesso" || idArea == "area_menu") {
		body.addClass('fullscreen');
	} else {
		body.removeClass('fullscreen');
	}

	if(tela.id == "tela_torcedor") {
		$("#barraDeTitulo .informacoes").show();
	} else {
		$("#barraDeTitulo .informacoes").hide();
	}
	body
		.removeClass(this._idTelaAnterior)
		.addClass(this._idTelaAtual)
		.removeClass(this._idAreaAnterior)
		.addClass(this._idAreaAtual);

	//Se for a primeira tela, esconde o botão voltar
	primeiraTela = $(tela).prev().length > 0;
	APP.Barra.Titulo.botaoVoltar(primeiraTela);



	//Atualiza o conteúdo da página antes de carregar, se necessário
	preCarregamento = tela.getAttribute('data-preCarregamento');
	if(preCarregamento) {
		APP.nameSpace(preCarregamento);
	}
	
	//Atualiza o conteúdo da página, se necessário
	posCarregamento = tela.getAttribute('data-posCarregamento');
	if(posCarregamento) {
		APP.nameSpace(posCarregamento);
	}

	/* ROLAGEM */
	if(APP.Area.Rolagem._iScroll.hasOwnProperty(tela.id)) {
		rolagem = APP.Area.Rolagem._iScroll[tela.id];
		rolagem.refresh();
		rolagem.scrollTo(0,0);
	}

	if(tela.id == "tela_acesso" && APP.Area.Acesso.PrimeiraVez._status) {
		APP.Area.Acesso.PrimeiraVez.definir(false);
	}
}