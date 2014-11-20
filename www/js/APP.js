var APP = APP || {};
APP._tapEvent = "tap";
APP._API = "http://saudenacopa.epitrack.com.br/api/rest";
APP.setUp= function() {

	var idiomaGravado = APP.Area.Apresentacao.Idioma.idiomaGravado();
	var usuarioGravado = APP.Area.Acesso.Usuario.consultar();
	
	//APP.Area.Apresentacao.Idioma._preferenciaDoUsuario = 
		//APP.Area.Apresentacao.Idioma.idiomaGravado();
	
	if(idiomaGravado === null) {
		APP.GerenciadorDeTelas._idTelaInicial = "tela_idioma";

	} else {
		APP.Area.Apresentacao.Idioma.definirIdioma(idiomaGravado);
		//APP.Area.Apresentacao.Idioma.internacionalizar();

		if(APP.Area.Acesso.PrimeiraVez.consultar()) {
			APP.GerenciadorDeTelas._idTelaInicial = "tela_apresentacao";
		} else {
			if(usuarioGravado !== null && typeof usuarioGravado == 'object' && usuarioGravado.nome) {
				APP.GerenciadorDeTelas._idTelaInicial = "tela_torcedor";
			} else {
				APP.GerenciadorDeTelas._idTelaInicial = "tela_acesso";
			}
		}
	}
	//APP.GerenciadorDeTelas._idTelaInicial = "tela_calendarioDosJogos";

	document.addEventListener(
	  'touchmove',
	  function(e) {
	    e.preventDefault();
	  },
	  false
	);


	$(document.body).show();

};

APP.analytics = {
	trackEvent: function() {
		if(window.isphone && window.analytics) {
			analytics.trackEvent.apply(analytics, arguments);
		} else if(window.ga) {
			ga.apply(window,$.merge(['send', 'event'], arguments));
		}
	},
	trackView: function() {
		if(window.isphone && window.analytics) {
			analytics.trackView.apply(analytics, arguments);
		} else if(window.ga) {
			ga.apply(window,$.merge(['send', 'pageview'], arguments));
		}
	}
}

APP.redefinir= function (){
	localStorage.clear();
	APP.Area.Apresentacao.Idioma.definirIdioma();
	APP.GerenciadorDeTelas.exibir("#tela_sobre");
}
