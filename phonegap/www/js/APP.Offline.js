var APP = APP || {};
APP.Offline = {
	_onLine: null,
	setUp: function() {
		var that = this;
		this._onLine = navigator.onLine;
		this.detectar(false);

		
		//this.forcarOffline();
	},

	detectar: function(atualizarTela) {
		if(this._onLine == true) {
			$(document.body).addClass('online').removeClass('offline');
			this.esconder();
		} else {
			$(document.body).addClass('offline').removeClass('online');
			this.mostrar();
		}

		if(atualizarTela) {
			var tela = document.getElementById(APP.GerenciadorDeTelas._idTelaAtual);
			APP.GerenciadorDeTelas.exibirCallback(tela);
		
		}
	},

	forcarOffline: function() {
		this._onLine = false;
		this.detectar(true);
	},
	forcarOnline: function() {
		this._onLine = true;
		this.detectar(true);
	},

	esconder: function() {
		$(".componente_offline").hide();
	},

	mostrar: function() {
		$(".componente_offline").show();
	}
};