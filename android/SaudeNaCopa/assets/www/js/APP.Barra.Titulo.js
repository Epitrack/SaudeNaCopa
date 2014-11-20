var APP = APP || {};
APP.Barra = APP.Barra || {};
APP.Barra.Titulo = {
	setUp: function() {
		
	},

	definir: function(titulo) {
		$("#barraDeTitulo .titulo").text(titulo);
	},

	botaoVoltar: function(status) {
		if(status) {
			$("#barraDeTitulo .voltar").show();
		} else {
			$("#barraDeTitulo .voltar").hide();
		}
	}
}