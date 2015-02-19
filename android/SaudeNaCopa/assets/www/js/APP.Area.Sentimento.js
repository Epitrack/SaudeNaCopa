var APP = APP || {};
APP.Area = APP.Area || {};
APP.Area.Sentimento = {
	setUp: function() {},


	Sentimento: {
		setUp: function() {

		},
		Sintomas: {
			setUp: function() {
				this.escutarMarcacao();
			},

			escutarMarcacao: function() {
				var that = this;

				$("#tela_sintomas li").on('vclick', function(event) {
					$(event.currentTarget).toggleClass('selecionado');
				})
			},

		Formulario: {

			_idFormulario: "formularioSintomas",

			setUp: function() {
				this.aplicarValidacao();
			},

			aplicarValidacao: function() {
				var that = this,
					opcoesDeValidacao = {
						debug: true,
						submitHandler: function(formulario) {
							that.enviar(formulario)
						},
						//Campos para validação
						rules: {
						    email: {
						    	required: true,
						    	email: true
						    },
						}
					};

				$("#"+this._idFormulario).validate(opcoesDeValidacao);
			},

			enviar: function(form) {
				var that = this;
				$.ajax({
					url: form.getAttribute('action'),
					dataType: "JSON",
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
				if(data.status) {
					APP.GerenciadorDeTelas.exibir("#tela_torcedor");
				} else {
					if(data.mensagem) {
						this.naoEnviou(data.mensagem);
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
		}
	},

	Torcedor: {
		_idLocalStorage: 'SNC_Sentimento_Torcedor_dados',
		
		_dadosFalsos: {
				nome: "Usuário anônimo",
				sentimento: "bem",
				nivel: "bem",
				pontos: "30",
				categoria: "infantil"
		},

		setUp: function() {},


		atualizar: function() {
			var dados = JSON.parse(localStorage.getItem(this._idLocalStorage));
			$("#status .dado.nome").text(dados.nome);
			$("#status .dado.sentimento").text(dados.sentimento);
			$("#status .icone.sentimento").attr('data-nivel', dados.nivel);
			$("#status .dado.pontos").text(dados.pontos);
			$("#status .dado.categoria").text(dados.categoria);
		},

		Dados:  {
			setUp: function() {
				this.carregar();
			},
			carregar: function() {
				this.carregando();
			},

			carregando: function() {
				this.carregado();
			},

			carregado: function() {
				
				
				var dadosFalsos = this.pai()._dadosFalsos;

				var meResponse = localStorage.getItem("SNC_Acesso_Facebook_meResponse");

				if(meResponse) {
					meResponse = JSON.parse(meResponse);	
					dadosFalsos.nome = meResponse.name;
				}

				localStorage.setItem(this.pai()._idLocalStorage,JSON.stringify(dadosFalsos));
			},

			naoCarregado: function() {
				alert('Não foi possível carregar o dados do Torcedor. Tente novamente. Se o problema persistir, você pode denunciar este poblema.');
			}
		}
	}
}