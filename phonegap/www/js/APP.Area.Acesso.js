var APP = APP || {};
APP.Area = APP.Area || {};
APP.Area.Acesso = {

	setUp: function() {

	},

	logout: function() {
		this.Usuario.apagar();
		APP.GerenciadorDeTelas.exibir("#tela_acesso");
		/*FB.logout(function(response) {
	       APP.GerenciadorDeTelas.exibir("#tela_acesso");
	    });*/
	},

	PrimeiraVez: {
	
		_status: null,
		_idLocalStorage: "SNC_Acesso_Primeira_vez",

		setUp: function() {
			this.consultar();
		},

		consultar: function() {
			var valorLocalStorage = localStorage.getItem(this._idLocalStorage);

			if(valorLocalStorage == "true" || valorLocalStorage === null) {
				this._status = true;
			}  else if(valorLocalStorage == "false") {
				this._status = false;
			}
			return this._status;
		},

		definir: function(status) {
			localStorage.setItem(this._idLocalStorage, status ? "true" : "false");
		}

	},


	TermosDeUso: {
		_iScroll: null,
		setUp: function() {
			var that = this;
			this._iScroll = new IScroll("#tela_termosDeUso .scroller");
	
			$("#tela_termosDeUso").on(APP._tapEvent,"a[href='#topo']", function(){
				that.voltarAoTopoClickEventHandler.apply(that, arguments);
			})
			$("#tela_termosDeUso").on(APP._tapEvent,"a[href='#menu']", function(){
				that.voltarAoMenuClickEventHandler.apply(that, arguments);
			})

			$("#tela_termosDeUso").on(APP._tapEvent,"ol.menu li", function() {
				that.menuClickEventHandler.apply(that, arguments);
			});
		},

		menuClickEventHandler: function(event) {
			var ref = event.currentTarget.getAttribute('data-ref');
			APP.analytics.trackEvent('termos de uso', 'ler', ref);
			this._iScroll.scrollToElement("article[data-ref='"+ref+"']", 1000);
		},

		voltarAoTopoClickEventHandler: function(_tapEvent) {
			APP.analytics.trackEvent('termos de uso', 'voltar', "top");
			this._iScroll.scrollTo(0,0,1000);
		},

		voltarAoMenuClickEventHandler: function(_tapEvent) {
			APP.analytics.trackEvent('termos de uso', 'voltar', "menu");
			this._iScroll.scrollToElement("#tela_termosDeUso .ancoraMenu", 1000);
		},

		//Função chamada após exibir a tela
		posCarregamento: function() {
			if(this._iScroll !== null) {
				this._iScroll.refresh();
			}
		}
	},

	Geolocalizacao: {
		_dados: null,
		setUp: function() {
			if(navigator.onLine) this.capturar();
		},

		capturar: function(callback) {
			var that = this;
			var opcoes = {
				success: function() {
					var argumentos = arguments;
					if(callback) {
						var argumentsArray = Array.prototype.slice.call(arguments);
							argumentsArray.push(callback);
							argumentos = argumentsArray;
					}
					that.capturarSuccessHandler.apply(that, argumentos);
				},
				error: function() {
					var argumentos = arguments;
					if(callback) {
						var argumentsArray = Array.prototype.slice.call(arguments);
							argumentsArray.push(callback);
							argumentos = argumentsArray;
					}
					that.capturarErrorHandler.apply(that, argumentos);
				},
			}
			$.geolocation.get(opcoes);
		},

		capturarSuccessHandler: function(position, callback) {
			this._dados = position;
			if(callback) {
				callback.call(this, position);
			}
			APP.analytics.trackEvent('localizacao', 'encontrada', 'tela', APP.GerenciadorDeTelas._idTelaAtual);
		},

		capturarErrorHandler: function(error, callback) {
			alert(i18n.t("acesso.geolocalizacao.erro"));
			if(callback) {
				callback.call(this, position);
			}
			APP.analytics.trackEvent('localizacao', 'nao encontrada', 'tela', APP.GerenciadorDeTelas._idTelaAtual);
		}
	},

	Usuario: {
		_idLocalStorage: "SNC_Usuario",
		_dados: null,

		setUp: function() {
			this.recuperar();
		},

		gravar: function(objetoUsuario) {
			var dados = this._dados;

			if(objetoUsuario && typeof objetoUsuario == 'object') {
				dados = objetoUsuario;
				this._dados = objetoUsuario;
			}

			localStorage.setItem(this._idLocalStorage, JSON.stringify(dados));
		},

		consultar: function() {
			var dados, objeto = null; 
				dados = localStorage.getItem(this._idLocalStorage);
			
			if(dados) {
				objeto = JSON.parse(dados);
			}	
			return objeto;
		},

		definirPropriedade: function(propriedade, valor){ 
			var objeto = this.consultar();
			objeto[propriedade] = valor;
			this.gravar(objeto);
		},

		consultarPropriedade: function(propriedade) {
			var objeto = this.consultar();
			var resposta = null;

			if(objeto !== null && objeto.hasOwnProperty(propriedade)) {
				resposta = objeto[propriedade];
			}
			return resposta;
		},

		apagar: function() {
			localStorage.removeItem(this._idLocalStorage);
		},

		recuperar: function() {
			var dadosGravados = this.consultar();

			if(dadosGravados !== null) {
				this._dados = dadosGravados;
			}
		}
	}
}