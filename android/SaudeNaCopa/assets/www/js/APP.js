var APP = APP || {};
APP.setUp= function() {

	
	/*APP.Area.Apresentacao.Idioma._preferenciaDoUsuario = APP.Area.Apresentacao.Idioma.idiomaGravado();
	
	if(APP.Area.Apresentacao.Idioma._preferenciaDoUsuario === "undefined") {

		APP.GerenciadorDeTelas._idTelaInicial = "tela_idioma";

	} else if(APP.Area.Acesso.PrimeiraVez.consultar()) {

		APP.GerenciadorDeTelas._idTelaInicial = "tela_apresentacao";

	} else {

		APP.GerenciadorDeTelas._idTelaInicial = "tela_acesso";

	}
	*/
	APP.GerenciadorDeTelas._idTelaInicial = "tela_idioma";
};

APP.redefinir= function (){
	localStorage.clear();
	APP.Area.Apresentacao.Idioma.definirIdioma();
	APP.GerenciadorDeTelas.exibir("#tela_idioma");
}
