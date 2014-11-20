var APP = APP || {};
APP.GerenciadorDeTelas = APP.GerenciadorDeTelas || {};


APP.GerenciadorDeTelas.setUpCallback = function() {
	//APP.GerenciadorDeTelas._IScroll = new IScroll("#scroller");
}


APP.GerenciadorDeTelas.exibirCallback = function(tela) {
	var primeiraTela, idArea, metodoDeAtualizacao, 
		body = $(document.body),
		PrimeiraVez = APP.Area.Acesso.PrimeiraVez;

	//Troca o título da tela
	//APP.Barra.Titulo.definir(tela.getAttribute('data-titulo'));
	APP.Barra.Titulo.definir(i18n.t(tela.id.split("tela_").join("tela.")));
	

	//Esconde a barra de titulo e de menu, se necessário
	idArea = tela.parentNode.id; 
	if(idArea == "area_apresentacao" || idArea == "area_acesso") {
		body.addClass('fullscreen');
	} else {
		body.removeClass('fullscreen');
	}
	body
		.removeClass(this._idTelaAnterior)
		.addClass(this._idTelaAtual);

	//Se for a primeira tela, esconde o botão voltar
	primeiraTela = $(tela).prev().length > 0;
	APP.Barra.Titulo.botaoVoltar(primeiraTela);

	//Atualiza o conteúdo da página, se necessário
	metodoDeAtualizacao = tela.getAttribute('data-atualizar');
	if(metodoDeAtualizacao) {
		APP.nameSpace(metodoDeAtualizacao);
	}

	//Atualiza o componente de rolagem
	if(this._IScroll) { 
		this._IScroll.refresh();
		this._IScroll.scrollTo(0,0);
	}

	if(tela.id == "tela_acesso" && APP.Area.Acesso.PrimeiraVez._status) {
		APP.Area.Acesso.PrimeiraVez.definir(false);
	}
}