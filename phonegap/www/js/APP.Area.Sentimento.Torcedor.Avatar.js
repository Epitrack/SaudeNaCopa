var APP = APP || {};
APP.Area = APP.Area || {};
APP.Area.Sentimento = APP.Area.Sentimento || {};
APP.Area.Sentimento.Torcedor = APP.Area.Sentimento.Torcedor || {};
APP.Area.Sentimento.Torcedor.Avatar = {
	_iScroll: null,
	_atual: 0,
	_sentimentos: ["muitoBem", "bem", "normal", "mal", "muitoMal"],

	setUp: function() {
		var that = this;
		this._iScroll = new IScroll("#tela_torcedor .scroller", { snap: true });
		
		this._iScroll.on('scrollEnd', function() {
			that._atual = this.currentPage.pageY;
			$("#campoSentimentoHidden").val(that._atual);
			that.scrollEndEventHandler();
		});
	},

	Atalhos: {
		_ultimo: -1,
		definirUltimo: function(sentimento) {
			this._ultimo = sentimento;
		},
		atualizar: function(sentimento,conferirUltimo) {
			if(sentimento > 1) {
				if(conferirUltimo !== true || conferirUltimo === true && this._ultimo > 1) {
					this.mostrar();
				}
			} else {
				this.esconder();
			}
		},

		mostrar: function() {
			$(".botao.hospitais", "#tela_torcedor").show();
		},

		esconder: function() {
			$(".botao.hospitais", "#tela_torcedor").hide();
		}
	},


	scrollEndEventHandler: function() {
		this.atualizarTextoSentimento();
		this.atualizarIndicador();
	},

	atualizarIndicador: function() {
		$("#componente_indicadorSentimento .indicador")
			.removeClass('selecionado')
			.eq(this._atual)
			.addClass('selecionado');
	},

	atualizarTextoSentimento: function() {
		var textoSentimento = 
			i18n.t("sentimento.sentimento."+this._sentimentos[this._atual]);
		$("#tela_torcedor .dado.sentimento, #componente_confirmacao .sentimento").text(textoSentimento);
		


		if(this._atual == 2) {
			$("#tela_torcedor .pergunta").show();
			$("#tela_torcedor .resposta, .row.avatar .botao.ok").hide();

		} else {
			$("#tela_torcedor .resposta, .row.avatar .botao.ok").show();
			$("#tela_torcedor .pergunta").hide();
		}

		this.Atalhos.atualizar(this._atual, true);
		

	},

	exibirSentimento: function(indice) {
		if(this._iScroll !== null) {
			this._iScroll.goToPage(0,indice);
		}
	},

	muitoBem: function() {
		this.exibirSentimento(0);
	},

	bem: function() {
		this.exibirSentimento(1);
	},

	normal: function() {
		this.exibirSentimento(2);
	},

	mal: function() {
		this.exibirSentimento(3);
	},

	muitoMal: function() {
		this.exibirSentimento(4);
	},

	atualizarRolagem: function() {
		var that = this;

		if(this._iScroll !== null) {
			this._iScroll.refresh();
		}
	}
};