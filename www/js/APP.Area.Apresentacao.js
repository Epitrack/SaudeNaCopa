var APP = APP || {};
APP.Area = APP.Area || {};
APP.Area.Apresentacao = {
	setUp: function() {},

	Idioma:  {
			
		_idLocalStorage: "SNC_Acesso_idioma",
		_preferenciaDoUsuario: null,
		_idiomaPadrao: "pt",

		setUp: function() {
			//Escutar cliques de alteração de idioma
			this.idiomaEscutar();
		},

		//Executa o plugin de validação 
		internacionalizar: function() {
			var opcoesI18n, that = this;
			i18n.addPostProcessor("quebrarLinhas", function(value, key, options) {
			  return value.replace(/\n/gi,"<br /><br />");
			});
			opcoesI18n = { 
				debug: false,
				getAsync: false,
				fallbackLng: 'pt',
				lng: this._preferenciaDoUsuario,
				postProcess: "quebrarLinhas"
			}

			i18n.init(opcoesI18n, function(t) {
				
				document.body.classList.remove("en");
				document.body.classList.remove("pt");
				document.body.classList.remove("es");
				document.body.classList.add(i18n.options.lng);
				that.traduzir();
				APP.Barra.Titulo.atualizar();
				//that.pai().Apresentacao._iScroll.refresh();
				
				//Atualiza os dados da tela após o carregamento do conteúdo localizado
				if(APP.GerenciadorDeTelas._idTelaAtual == "tela_torcedor") {
					APP.Area.Sentimento.Torcedor.posCarregamento();
				}
			});
		},

		//Traduz o aplicativo
		traduzir: function() {
			$("#app").i18n();
		},

		//Escuta os vclicks nos botões de idioma
		idiomaEscutar: function() {
			var that = this;

			$("#tela_idioma")
				.on(APP._tapEvent,"a[href^='#idioma']", function() {
					that.idiomaEventHandler.apply(that, arguments);
				});
		},

		//Administra os vclicks nos botões de icioma
		idiomaEventHandler: function(event) {
			event.preventDefault();
			//Dectecta qual foi o idioma vclicado
			var idioma = event.target.getAttribute('href').replace('#idioma_','');

			//Define o idioma do aplicativo
			this.definirIdioma(idioma);

			APP.analytics.trackEvent('idioma', 'definir', idioma);

			//Mostra a tela de abertura
			var telaAnterior = APP.GerenciadorDeTelas._idTelaAnterior;
			if(!telaAnterior) {
				APP.GerenciadorDeTelas.exibir('#tela_apresentacao');
			} else {
				APP.GerenciadorDeTelas.Historico.voltar();
			}
		},

		gravarIdioma: function(idioma) {
			localStorage.setItem(this._idLocalStorage, idioma);
		},

		definirIdioma: function(idioma) {
			//Define a preferência do usuário no objeto
			this._preferenciaDoUsuario = idioma;

			//Grava a preferência do usuário na memória
			this.gravarIdioma(idioma);

			//Carrega a validação das mensagens de erro dos formulários
			this.localizarValidacao(idioma);

			//Traduz o aplicativo para a linguagem selecionada
			this.internacionalizar();
		},

		localizarValidacao: function(idioma) {
			var caminho, arquivo;
				caminho = 'bibliotecas/jquery-validation/localization/';
			switch(idioma) {
				case 'en': arquivo = "messages_en.js"; break;
				case 'es': arquivo = "messages_es.js"; break;
				case 'pt': arquivo = "messages_pt_BR.js"; break;
			}

			head.load(caminho+arquivo);
		},

		idiomaGravado: function() {
			var idioma = localStorage.getItem(this._idLocalStorage);
			return idioma;
		},

		consultarIdioma: function() {
			var idioma, idiomaGravado;
				idiomaGravado = this.idiomaGravado();
		
			idioma = idiomaGravado !== "undefined" ? 
						idiomaGravado : this._idiomaPadrao;	

			return idioma;
		}

	},

	Abertura: {

		//Função chamada após exibir a tela
		posCarregamento: function() {
			//Mostra a tela de apresentação depois de 3 segundos
			setTimeout(function() {
				APP.GerenciadorDeTelas.exibir('#tela_apresentacao');
			}, 3000);
		}
	},

	Apresentacao: {
		_iScroll: null,
		setUp: function() {
			this._iScroll = new IScroll("#tela_apresentacao .scroller");
		},

		//Função chamada após exibir a tela
		posCarregamento: function() {
			if(this._iScroll !== null) {
				this._iScroll.refresh();
			}
		}
	}
}