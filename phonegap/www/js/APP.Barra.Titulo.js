var APP = APP || {};
APP.Barra = APP.Barra || {};
APP.Barra.Titulo = {
	setUp: function() {
		var that = this;

		$(document.body)
			.on(APP._tapEvent,"a[href='#avancar']", function(event) {
				that.avancarEventHandler.apply(that, arguments);
			});

	},
	avancarEventHandler: function() {
		var telaAtual = APP.GerenciadorDeTelas._idTelaAtual;

		if(telaAtual == "tela_sintomas") {
			APP.Area.Sentimento.Torcedor.Sintomas.enviar();
		}

		// if(telaAtual == "tela_hospitaisDeReferencia" || telaAtual == "tela_farmacias") {
		// 	APP.GerenciadorDeTelas.exibir("#tela_mapaSaude");
		// }
		// if(telaAtual == "tela_mapaSaude") {
		// 	APP.GerenciadorDeTelas.exibir("#"+APP.GerenciadorDeTelas._idTelaAnterior);
		// }
		if(telaAtual == "tela_torcedor") {
			APP.GerenciadorDeTelas.exibir("#tela_calendario");
		}
	},

	definir: function(titulo) {
		$("#barraDeTitulo .titulo").text(titulo);
	},

	atualizar: function() {
		if(!APP.GerenciadorDeTelas._idTelaAtual) return false;
		var tela = document.getElementById(APP.GerenciadorDeTelas._idTelaAtual);
		APP.Barra.Titulo.definir(i18n.t(tela.id.split("tela_").join("tela.")));
	},

	botaoVoltar: function(status) {
		if(status) {
			$("#barraDeTitulo .voltar").show();
		} else {
			$("#barraDeTitulo .voltar").hide();
		}
	},


}