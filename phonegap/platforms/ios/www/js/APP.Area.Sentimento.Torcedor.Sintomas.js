var APP = APP || {};
APP.Area = APP.Area || {};
APP.Area.Sentimento = APP.Area.Sentimento || {};
APP.Area.Sentimento.Torcedor = APP.Area.Sentimento.Torcedor || {};
APP.Area.Sentimento.Torcedor.Sintomas = {
	setUp: function() {
		this.escutarMarcacao();
	},

	reset: function() {
		$("#"+this.Formulario._idFormulario).get(0).reset();
		$("#tela_sintomas li").removeClass('selecionado');
	},

	enviar: function() {
		APP.Area.Sentimento.Torcedor.Sintomas.Formulario.validar();
	},

	escutarMarcacao: function() {
		var that = this;

		$("#tela_sintomas").on(APP._tapEvent,"li", function(event) {
			if($('#tela_sintomas').hasClass('pronta')) {
				var li = $(event.currentTarget);
				if(event.target.tagName != "LABEL") {
					li.toggleClass('selecionado');
					//li.find('input').get(0).checked = li.hasClass('selecionado');
				} else {
					li.toggleClass('selecionado');
				}
			}
		})
	},

	posCarregamento: function() {
		var that = this;
		this.reset();

		setTimeout(function(){
			$("#tela_sintomas").addClass('pronta');
			that.reset();
		}, 600);
	},

	Formulario: {

		_idFormulario: "formularioSintomas",

		setUp: function() {
		},

		eValido: function() {
			return $("#tela_sintomas li.selecionado").length > 0;
		},
		validar: function(dadosGravados) {
            
			var that = this;
			var valido = this.eValido();
			var geo = APP.Area.Acesso.Geolocalizacao._dados;
            var dados = dadosGravados ? dadosGravados : this.gerarDados();
            
			if(valido && geo !== null) {
				APP.GerenciadorDeTelas.exibir("#tela_torcedor");
				this.pai().pai().PersistenciaSintomas.exibir(dados);
			} else {
				if(!valido) {
					alert(i18n.t("sentimento.sintomas.erroMarcacao"));
				}
				if(geo === null) {
					APP.Area.Acesso.Geolocalizacao.capturar(function(geo){
                                                            
                        if(geo && geo.coords) {
                            dados.latitude = geo.coords.latitude;
                            dados.longitude = geo.coords.longitude;
                        }
						APP.Area.Sentimento.Torcedor.Sintomas.Formulario.validar(dados);
                                                       
					});
				}
			}
		},


		gerarDados: function() {
			var that = this;
			var geo = APP.Area.Acesso.Geolocalizacao._dados;

			var form = $("#"+this._idFormulario).get(0);
			var campos = $("#tela_sintomas li")
				.map(function(i,e){ 
					return $(e).hasClass('selecionado') ? 1 : 0;  
			});
			var userID = APP.Area.Acesso.Usuario._dados.userID;
			var sentimento = APP.Area.Sentimento.Torcedor.Avatar._atual;
			var idSentimento = APP.Area.Sentimento.Torcedor.Avatar._sentimentos[sentimento];

			var dados = {
				"usuario_id": userID,
				sentimento: sentimento,
				latitude: 0,
				longitude: 0
			};
            
   
            if(geo && geo.coords) {
				dados.latitude = geo.coords.latitude;
				dados.longitude = geo.coords.longitude;
            }

			APP.analytics.trackEvent('torcedor', 'sentimento', idSentimento);

			if(sentimento > 2) {
				for(var i = 0; i<campos.length; i++) { 
					dados['campo'+(i+1)] = campos[i];
				}
			}
			if(sentimento < 2) {
				for(var i = 0; i<campos.length; i++) { 
					dados['campo'+(i+1)] = 0;
				}
			}


			return dados;
		},

		enviar: function(dadosGravados) {
			var that = this;
			var dados = dadosGravados ? dadosGravados : this.gerarDados();
			var form = $("#"+this._idFormulario).get(0);


			this.pai().pai().Avatar.Atalhos.definirUltimo(dados.sentimento);
			this.pai().pai().Avatar.Atalhos.atualizar(dados.sentimento);
            
			$.ajax({
				url: form.getAttribute('action'),
				dataType: "JSON",
				data: dados,
				cache: false,
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
			$("#"+this._idFormulario)
				.parent('.tela').first()
				.addClass('carregando');
		},

		removerCarregando: function() {
			$("#"+this._idFormulario)
				.parent('.tela').first()
				.addClass('carregando');
		},

		enviou: function(data) {
			var atualizacao = false;
			if(data.status) {
				if(data.usuario) {
					APP.Area.Sentimento.Torcedor.atualizarUsuario(data.usuario);
					atualizacao = true;
				}
			} else {
				if(data.tempo) {
					APP.Area.Sentimento.Torcedor.AlertaDeTempo.mostrar(data.tempo);
				}
			}

			//this.pai().pai().Confirmacao.esconder();
			APP.GerenciadorDeTelas.exibir("#tela_torcedor");
			APP.Area.Sentimento.Torcedor.Avatar.exibirSentimento(2);
			$("#tela_sintomas li").removeClass('selecionado');
			this.removerCarregando();

			if(atualizacao === true) {
				APP.Area.Sentimento.Torcedor.Arenas.exibirUltima();
			}
		},

		naoEnviou: function(feedback) {
			var mensagem = "Erro.";

			if(feedback) {
				mensagem += "\n "+feedback;
			}

			le.log(mensagem);

			this.removerCarregando();
		}
	}
};