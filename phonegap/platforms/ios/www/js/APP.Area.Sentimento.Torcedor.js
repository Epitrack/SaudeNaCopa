var APP = APP || {};
APP.Area = APP.Area || {};
APP.Area.Sentimento = APP.Area.Sentimento || {};
APP.Area.Sentimento.Torcedor = {
	_idLocalStorage: 'SNC_Usuario',
	_categorias: ["denteDeLeite", "mirim", "infantil", "juvenil", "junior", "profissional"],

	setUp: function() {
		var that = this;
		$("#tela_torcedor .row.avatar .botao.ok").on(APP._tapEvent, function(event) {
			that.botaoOKEventHandler.apply(that, event);
		})
		$("#tela_torcedor .row.avatar .botao.farmacias").on(APP._tapEvent, function(event) {
			that.botaoFarmaciasEventHandler.apply(that, event);
		})
		$("#tela_torcedor .row.avatar .botao.hospitais").on(APP._tapEvent, function(event) {
			that.botaoHospitaisEventHandler.apply(that, event);
		})
	},

	botaoOKEventHandler: function() {
		var sentimento = this.Avatar._atual;
		if(sentimento != 2) {
			if(sentimento < 2) {
				this.Confirmacao.mostrar();
			} else {
				APP.GerenciadorDeTelas.exibir("#tela_sintomas");
			}	
			this.pai();
		}
	},
	botaoFarmaciasEventHandler: function() {
		APP.Area.Saude.Mapa.mostrarFarmacias();
	},
	botaoHospitaisEventHandler: function() {
		APP.Area.Saude.Mapa.mostrarHospitais();
	},

	atualizarUsuario: function(novo) {
		var antigo;
			antigo = JSON.parse(localStorage.getItem(this._idLocalStorage));

		var categoria = {
			antes: antigo.categoria,
			depois: novo.categoria
		};
		var arenas = {
			antes: this.Arenas.calcularQuantidade(antigo.engajamento),
			depois: this.Arenas.calcularQuantidade(novo.engajamento)
		}
		var novaCategoria = categoria.depois > categoria.antes;
		var novaArena = arenas.depois > arenas.antes;

		if(novaCategoria) {
			APP.analytics.trackEvent('torcedor', 'transicao', 'categoria', categoria.depois);
		}
		if(novaArena) {
			this.Arenas.Transicao.destacar(arenas.depois);
			APP.analytics.trackEvent('torcedor', 'transicao', 'arena', arenas.depois);
		}

		APP.Area.Acesso.Usuario.gravar(novo);
	},


	posCarregamento: function() {
		var dados, categoria, i18nId;
			dados = JSON.parse(localStorage.getItem(this._idLocalStorage));
			categoria = this._categorias[dados.categoria];
			i18nId = "sentimento.torcedor.categorias."+categoria;

		//Define o nome do usuário
		$("#tela_torcedor .dado.nome").text(dados.nome);

		//Alerar o sexo do avatar
		$("#avatares")
			.attr('data-genero', dados.sexo)
			.attr('data-categoria', categoria)

		//Altera a categoria do usuário
		$("#componente_trofeu")
			.attr('data-categoria', categoria);
			
		$("#componente_categoria")
			.attr('data-categoria', categoria)
			.find("p")
			.text(i18n.t(i18nId));

		//Altera a quantidade de pontos
		$("#componente_pontos .pontos").text(dados.pontos);

		//Altera a barra de engajamento
		this.Engajamento.definir(dados.engajamento);

		//Atualiza a rolagem dos avatares
		this.Avatar.atualizarRolagem();

		//Atualiza o componente de arenas
		this.Arenas.atualizar(dados.engajamento, dados.arena);

		//Verifica se o formulário de sintomas está válido
		var validacaoFormularioSintomas = APP.Area.Sentimento.Torcedor.Sintomas.Formulario.eValido();
		
		//Se o formulário não estiver válido, 
		////é porque o usuário já enviou os dados
		//// Então pode voltar ao normal
		if(validacaoFormularioSintomas === false){
			this.Avatar.normal();	
		} 

		//Altera o texto do sentimento
		this.Avatar.atualizarTextoSentimento();

		$("#tela_sintomas").removeClass('pronta');
		this.Sintomas.reset();
	},

	calcularCategoria: function(porcentagem) {

		var categoria;
		categoria = 0;
		if (porcentagem <= 5) {
            categoria = 0;
        } else if (porcentagem > 5 && porcentagem <= 15) {
            categoria = 1;
        } else if (porcentagem > 15 && porcentagem <= 30) {
            categoria = 2;
        } else if (porcentagem > 30 && porcentagem <= 50) {
            categoria = 3;
        } else if (porcentagem > 50 && porcentagem <= 80) {
            categoria = 4;
        } else if (porcentagem > 80) {
            categoria = 5;
        }
        return categoria;
	},



	AlertaDeTempo: {
		setUp: function() {
			var that = this;
			$("#componente_alertaDeTempo .botao.ok").on(APP._tapEvent, function(){
				that.botaoOKEventHandler.apply(that, arguments);
			});
		},
		botaoOKEventHandler: function(event){
			this.esconder();
		},
		definirTempo: function(tempo){
			var horas = Math.floor(tempo);
			var minutos = Math.round(((tempo%1).toFixed(2)*60));
			var segundos = (((tempo%1).toFixed(2)*60)%1).toFixed(2)*60;

			var texto = horas+"h ";
				texto += minutos+"m ";
				texto += segundos+"s ";

			var textoAnalytics = horas+"h ";
				textoAnalytics += minutos+"m ";

			APP.analytics.trackEvent('alerta de tempo', horas+"h ", minutos+"m ");

			$("#componente_alertaDeTempo span.tempo").text(texto);
		},

		mostrar: function(tempo) {
			this.definirTempo(tempo);
			$(".componente_alertaDeTempo").show();
		}, 

		esconder: function() {
			$(".componente_alertaDeTempo").hide();
		}
	},

	Confirmacao: {
		setUp: function() {
			var that = this;
			$("#componente_confirmacao").on(APP._tapEvent,".botao.voltar", function(event) {
				that.botaoVoltarEventHandler.apply(that, event);
			});
			$("#componente_confirmacao").on(APP._tapEvent,".botao.ok", function(event) {
				that.botaoOkEventHandler.apply(that, event);
			});
		},
		botaoVoltarEventHandler: function() {
			this.esconder();
		},
		botaoOkEventHandler: function() {
			var geo = APP.Area.Acesso.Geolocalizacao._dados;
			if(geo === null) {
				APP.Area.Acesso.Geolocalizacao.capturar(function(geo){
                    var dados = APP.Area.Sentimento.Torcedor.Sintomas.Formulario.gerarDados();
                    if(geo && geo.coords) {
                        dados.latitude = geo.coords.latitude;
                        dados.longitude = geo.coords.longitude;
                    }
					APP.Area.Sentimento.Torcedor.Sintomas.Formulario.enviar(dados);
					APP.Area.Sentimento.Torcedor.Confirmacao.esconder();
				});
			} else {
				APP.Area.Sentimento.Torcedor.Sintomas.Formulario.enviar();
				this.esconder();
			}
		},
		mostrar: function() {
			$(".componente_confirmacao").show();		
		},
		esconder: function() {
			$(".componente_confirmacao").hide();
		}
	},

	PersistenciaSintomas: {
		_emExibicao: false,
		_dados: null,
		setUp: function() {
			var that = this;
			$("#componente_persistenciaSintomas .botao.ok").on(APP._tapEvent, function(event){
				that.botaoOkEventHandler.apply(that, arguments);
			})
		},
		botaoOkEventHandler: function() {
			APP.analytics.trackEvent('torcedor', 'persistencia de sintomas', "usuário escondeu");
			this.esconder();
		},

		exibir: function(dados) {
			var that = this;
			this._dados = dados;

			this._emExibicao = true;
			$("#componente_persistenciaSintomas").css("display", "table");
			setTimeout(function() {
				APP.analytics.trackEvent('torcedor', 'persistencia de sintomas', "escondeu sozinho");
				that.esconder();
			},8000);
		},

		esconder: function(dados) {
			if(this._emExibicao === true) {
				var dados = this._dados;
				APP.Area.Sentimento.Torcedor.Sintomas.Formulario.enviar(dados);
				$("#componente_persistenciaSintomas").css("display", "none");
				this._emExibicao = false;
			}
		}
	},

	Engajamento: {
		setUp: function() {},

		definir: function(porcentaem) {
			$("#componente_engajamento .blocos.ativos")
				.addClass('zerando')
				.height(0)
				.removeClass('zerando')
				.height(porcentaem+"%");
		}
	}
}