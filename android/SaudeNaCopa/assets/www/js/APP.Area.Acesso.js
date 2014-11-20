var APP = APP || {};
APP.Area = APP.Area || {};
APP.Area.Acesso = {

	setUp: function() {

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


	Login:  {
		setUp: function() {

		},

		

		Formulario: {

			_idFormulario: "formularioLogin",

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
						    senha: "required"
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
	},


	CadastreSe:  {
		setUp: function() {

		},

		

		Formulario: {

			_idFormulario: "formularioCadastro",

			setUp: function() {
				this.aplicarValidacao();
			},

			aplicarValidacao: function() {
				var that = this,
					opcoesDeValidacao = {
						debug: true,
						submitHandler: function(formulario) {
							that.enviar(formulario);
						},
						//Campos para validação
						rules: {
						    apelido: "required",
						    idade: "required",
						    sexo: "required",
						    email: {
						    	required: true,
						    	email: true
						    },
						    senha: "required",
						    confirmacaoDeSenha:  {
						    	required: true,
						    	equalTo: "#campoCadastro_senha"
						    }
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
				});
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
					console.log('Deu certo');
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
	},



	EsqueciASenha:  {
		setUp: function() {

		},

		Formulario: {

			_idFormulario: "formularioEsqueciASenha",

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
						    }
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
					console.log('Deu certo');
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
	},

	Facebook: {
		setUp: function() {

		}
	}
	

}