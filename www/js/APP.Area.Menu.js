var APP = APP || {};
APP.Area = APP.Area || {};
APP.Area.Menu = {
	setUp: function() {},


	Voltar: {
		_origem: null,
		setUp: function() {
			var that = this;

			$(document.body)
				.on(APP._tapEvent,"a[href='#menuVoltar']", function(event) {
					that.voltarEventHandler.apply(that, arguments);
				});

		},
		voltarEventHandler: function() {
			var destino, telaAtual;
				destino = APP.GerenciadorDeTelas._idTelaAnterior;
				telaAtual = APP.GerenciadorDeTelas._idTelaAtual;

			if(telaAtual == "tela_sobre") {
				destino = "tela_menu";
			} else  if(telaAtual == "tela_menu") {
				destino = this._origem;
			}

			APP.GerenciadorDeTelas.exibir("#"+destino);
			if(telaAtual == "tela_menu") {
				this._origem = null;
			}
		}

	},

	Menu: {
		setUp: function() {

		},

		posCarregamento: function() {
			var origemVoltar = APP.Area.Menu.Voltar._origem;
			if(origemVoltar === null) {
				var origem = APP.GerenciadorDeTelas._idTelaAnterior;
				APP.Area.Menu.Voltar._origem = origem;
			}
		}
	},


	
	Versao: {
		_iScroll: null,
		setUp: function() {
			var that = this;
			
			//this._iScroll = new IScroll("#tela_versao .scroller");
		}

		// ,//Função chamada após exibir a tela
		// posCarregamento: function() {
		// 	if(this._iScroll !== null) {
		// 		this._iScroll.refresh();
		// 	}
		// }
	},
	Sobre: {
		_iScroll: null,
		setUp: function() {
			var that = this;
			
			this._iScroll = new IScroll("#tela_sobre .scroller");

			$("#tela_sobre").on(APP._tapEvent,"a[href='#topo']", function(){
				that.topoClickEventHandler.apply(that, arguments);
			})

			$("#tela_sobre").on(APP._tapEvent,"ol.menu li", function() {
				that.menuClickEventHandler.apply(that, arguments);
			});
		},

		menuClickEventHandler: function(event) {
			var ref = event.currentTarget.getAttribute('data-ref');
			APP.Area.Menu.Sobre._iScroll.scrollToElement("article[data-ref='"+ref+"']", 1000)
		},

		topoClickEventHandler: function(_tapEvent) {
			this._iScroll.scrollTo(0,0,1000);
		},

		//Função chamada após exibir a tela
		posCarregamento: function() {
			if(this._iScroll !== null) {
				this._iScroll.refresh();
			}
		}
	}
}