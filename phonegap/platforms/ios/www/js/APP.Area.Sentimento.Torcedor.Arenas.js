var APP = APP || {};
APP.Area = APP.Area || {};
APP.Area.Sentimento = APP.Area.Sentimento || {};
APP.Area.Sentimento.Torcedor = APP.Area.Sentimento.Torcedor || {};
APP.Area.Sentimento.Torcedor.Arenas = {
	_lista: [
		{ 	
			id: "cuiaba",
			nome: "Arena Pantanal",
			cidade: "Cuiabá"
		},
		{ 	
			id: "curitiba",
			nome: "Arena da Baixada",
			cidade: "Curitiba"
		},
		{
			id: "natal", 	
			nome: "Arena das Dunas",
			cidade: "Natal"
		},
		{ 	
			id: "manaus",
			nome: "Arena Amazônia",
			cidade: "Manaus"
		},
		{ 	
			id: "pernambuco",
			nome: "Arena Pernambuco",
			cidade: "São Lourenço da Mata (cidade da Grande Recife)"
		},
		{ 	
			id: "fortaleza",
			nome: "Castelão",
			cidade: "Fortaleza"
		},
		{ 	
			id: "portoAlegre",
			nome: "Beira-Rio",
			cidade: "Porto Alegre"
		},
		{
			id: "salvador",
			nome: "Fonte Nova",
			cidade: "Salvador"
		},
		{ 	
			id: "brasilia",
			nome: "Estádio Nacional (Mané Garrincha)",
			cidade: "Brasília"
		},
		{ 	
			id: "bh",
			nome: "Mineirão",
			cidade: "Belo Horizonte"
		},
		{ 	
			id: "saoPaulo",
			nome: "Arena de São Paulo (Fielzão)",
			cidade: "São Paulo"
		},
		{ 	
			id: "rioDeJaneiro",
			nome: "Maracanã",
			cidade: "Rio de Janeiro"
		}
	],
	_valorMaximo: 80,
	_iScroll: null,
	_atual: 0,
	_html: null,
	_timer: null,
	setUp: function() {
		this._html = $("#componente_arenas").html();
		var arenaSelecionada = APP.Area.Acesso.Usuario.consultarPropriedade("arena");
		if(arenaSelecionada !== null) {
			this._atual = parseInt(arenaSelecionada);
		}

		$("#componente_arenas").on(APP._tapEvent, function(){
			APP.GerenciadorDeTelas.exibir("#tela_arenas");
		});
		$("#componente_categoria").on(APP._tapEvent, function(){
			APP.GerenciadorDeTelas.exibir("#tela_categorias");
		})
	},

	gravar: function(pagina) {
		var that = this;

		this.limparTimer();
		this._timer = setTimeout(function(){
			APP.Area.Acesso.Usuario.definirPropriedade("arena", pagina);

			that.Formulario.enviar();
			that.limparTimer();
		}, 1000);
	},

	limparTimer: function() {
		if(this._timer !== null) {
			clearInterval(this._timer);
			this._timer = null;
		}
	},

	atualizar: function(porcentagem, arena) {
		var quantidade, arenaGravada;
			quantidade = this.calcularQuantidade(porcentagem);
			arenaGravada = parseInt(arena);

		//console.log("arenas", quantidade)
		this.definirQuantidade(quantidade);
		this.exibir(arenaGravada);
	},

	definirQuantidade: function(quantidade) {
		var that = this;
		
		if(this._iScroll) {
			this._iScroll.destroy();
			this._iScroll = null;
		}

		var html = $("<div>")
					.html(this._html)
					.i18n()
					.html();

		$("#componente_arenas")
			.attr('data-quantidade', quantidade)
			.html(html);
		
		this.criarRolagem();
	},

	atualizarRolagem: function() {
		var that = this;
		if(!this._iScroll) {
			setTimeout(function(){
				that.criarRolagem();
			}, 100);
		}
	},

	criarRolagem: function() {
		var that = this;
		var opcoes ={
			scrollX: true,
			scrollY: false,
			snap: true,
			click: true
		};

		this._iScroll = new IScroll("#componente_arenas .scroller", opcoes);
		this.exibir(this._atual);

		this._iScroll.on('scrollEnd', function() {
			that.gravar(this.currentPage.pageX);
			that.atualizarArenaSelecionada(this.currentPage.pageX);
		});
	},

	exibir: function(pagina) {
		this._iScroll.goToPage(pagina,0,0);
		this.atualizarArenaSelecionada(pagina);
	},
	exibirUltima: function() {
		var ultima = this._iScroll.pages.length-1
		this.exibir(ultima);
	},
	destruir: function() {
		this._iScroll.destroy();
	},

	atualizarArenaSelecionada: function(pagina) {
		this._atual = pagina;
		var idArena = this._lista[pagina].id;

		$("#background_arena")
			.attr('data-arena', idArena)

		$("#componente_arenas")
			.attr('data-selecionada', pagina)
			.find(".arenas .arena")
				.removeClass('selecionada')
				.eq(pagina)
				.addClass('selecionada');
	},

	calcularQuantidade: function(porcentagem) {
		//12 arenas
		var disponivel = this._lista.length;
		//80
		var valorMaximo = this._valorMaximo;
		
		//Calculando a quantidade disponível
		var quantidade = porcentagem/(valorMaximo/disponivel);

			//Garantindo mínimo 1 e máximo 80
			quantidade = Math.max(1,Math.min(disponivel,quantidade));

			//Removendo os decimais
			quantidade = Math.floor(quantidade)

		return quantidade;
	},

	Transicao: {
		setUp: function() {

		},
		destacar: function(arena) {
			var that=this;
			var nome = i18n.t("sentimento.torcedor.arena.arena"+arena+".nome");
			$("#background_arena").attr('data-arena', this.pai()._lista[arena-1].id);
			$("#background_arena .nome").text(nome);
			$("#tela_torcedor").addClass('transicaoArena');
			
			setTimeout(function(){
				that.voltar();
			},8000)
		},

		voltar: function() {
			$("#tela_torcedor").removeClass('transicaoArena');
			setTimeout(function(){
				APP.Area.Sentimento.Torcedor.posCarregamento();
			}, 1000);
		}
	},
	Formulario: {
		setUp: function() {},

		enviar: function() {
			var that = this;
			// url: [PTH_SERVER] + /updateUserArena
			// method: POST
			// params: usuario_id , arena
			
			$.ajax({
				url: APP._API+"/updateUserArena",
				dataType: "JSON",
				data: {
					"usuario_id": APP.Area.Acesso.Usuario._dados.userID,
					"arena": that.pai()._atual
				},
				type: "post",

				beforeSend: function() {
					that.enviando.apply(that, arguments);
				}, 
				success: function() {
					that.enviou.apply(that, arguments);
				}, 
				error: function() {
					that.naoEnviou.apply(that, arguments);
				}
			})
		},



		enviando: function() {
			this.adicionarCarregando();
		},

		adicionarCarregando: function() {
			$("#tela_torcedor").addClass('carregando');
		},

		removerCarregando: function() {
			$("#tela_torcedor").removeClass('carregando');
		},



		enviou: function(data) {
			if(data.status) {
				if(data.usuario) {
					APP.Area.Acesso.Usuario.gravar(data.usuario);
				}
			} else {
				if(data.mensagem) {
					this.naoEnviou(data.mensagem)
				} else {
					this.naoEnviou()	
				}	
			}
			this.removerCarregando();
		},

		naoEnviou: function(feedback) {
			var mensagem = "Erro.";

			if(feedback) {
				mensagem += "\n "+feedback;
			}

			console.log(mensagem);

			this.removerCarregando();
		}
	}
};