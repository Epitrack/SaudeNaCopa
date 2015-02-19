var APP = APP || {};
APP.Area = APP.Area || {};
APP.Area.Apresentacao = {
	setUp: function() {

	},

	Idioma:  {
			
		_idLocalStorage: "SNC_Acesso_idioma",
		_preferenciaDoUsuario: null,
		_idiomaPadrao: "pt",

		setUp: function() {
			this.internacionalizar();

			//Escutar cliques de alteração de idioma
			this.idiomaEscutar();
		},

		internacionalizar: function() {
			var opcoesI18n, that = this;

			opcoesI18n = { 
				debug: true,
				lng: this.consultarIdioma()
			}

			i18n.init(opcoesI18n, function(t) {
				that.traduzir();
			});
		},

		traduzir: function() {
			$("#app").i18n();
		},

		idiomaEscutar: function() {
			var that = this;

			$("a[href^='#idioma']", document.body)
				.on('vclick', function() {
					that.idiomaEventHandler.apply(that, arguments);
				});
		},

		idiomaEventHandler: function(event) {
			var idioma = event.target.getAttribute('href').replace('#idioma_','');

			this.definirIdioma(idioma);

			APP.GerenciadorDeTelas.exibir('#tela_abertura');

			setTimeout(function() {
				APP.GerenciadorDeTelas.exibir('#tela_apresentacao');
			}, 3000);
		},

		gravarIdioma: function(idioma) {
			console.log('Gravando idioma como', idioma);
			localStorage.setItem(this._idLocalStorage, idioma);
		},

		definirIdioma: function(idioma) {
			console.log('Definindo idioma como', idioma);

			this.gravarIdioma(idioma);
			this.internacionalizar();
		},

		idiomaGravado: function() {
			var idioma = localStorage.getItem(this._idLocalStorage);
			console.log('Consultado idioma gravado: ', idioma);
			return idioma;
		},

		consultarIdioma: function() {
			var idioma, idiomaGravado;
				idiomaGravado = this.idiomaGravado();
		
			idioma = idiomaGravado !== "undefined" ? 
						idiomaGravado : this._idiomaPadrao;	

			console.log('Consultado idioma: ', idioma);

			return idioma;
		}

	}
}