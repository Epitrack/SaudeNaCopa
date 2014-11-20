var APP = APP || {};
APP.Area = APP.Area || {};
APP.Area.Acesso = APP.Area.Acesso || {};
APP.Area.Acesso.CadastreSe = {
	setUp: function() {

	},

	Formulario: {

		_idFormulario: "formularioCadastro",

		setUp: function() {
			this.aplicarValidacao();

			jQuery.validator.addMethod("alfaNumerico", function(value, element) {
				return this.optional(element) || value.match(/[^a-zA-Z0-9\s]/g) ? false : true;
			});

		},

		aplicarValidacao: function() {
			var that = this,
				opcoesDeValidacao = {
					//debug: true,
					submitHandler: function(formulario) {
						that.enviar(formulario);
					},
					invalidHandler: function(event, validator) {
						var errors = validator.numberOfInvalids();
						
						if (errors) {
							var mensagem = i18n.t("formulario.erro")+"\n";

							for(var i = 0; i < validator.errorList.length; i++) {
								var elemento = validator.errorList[i].element;
								var placeholder = elemento.getAttribute('placeholder');
								var msg = $("<div>").html(validator.errorList[i].message).text()
								mensagem += "\n - "+placeholder;
								mensagem += ": "+msg;
								APP.analytics.trackEvent('cadastro', 'erro', placeholder);
							}

							alert(mensagem);
						}

					},
					//Campos para validação
					rules: {
					    apelido: {
					    	required: true,
					    	alfaNumerico: true
					    },
					    idade: {
					    	required: true,
					    	number: true,
  							min: 13,
  							max: 116
					    },
					    termo: "required",
					    sexo: "required",
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
			// val_apelido = $("#campoCadastro_apelido").val();
			// val_idade = $("#campoCadastro_idade").val();
			// val_sexo = $("#campoCadastro_sexo").val();
			// val_email = $("#campoCadastro_email").val();
			// val_senha = $("#campoCadastro_senha").val();
			// val_confirma_senha = $("#campoCadastro_confirmacaoDeSenha").val();
			

			var idioma;

			switch(APP.Area.Apresentacao.Idioma._preferenciaDoUsuario) {
				case 'pt': idioma = 0; break;
				case 'es': idioma = 0; break;
				case 'en': idioma = 0; break;
				default: idioma = 0;
			}

			var dadosFormulario = $("#"+this._idFormulario).serializeJSON();
				dadosFormulario.gcmid = "0";
				dadosFormulario.idioma = idioma
				dadosFormulario.device = head.touch ? "ios" : "desktop";

			$.ajax({
				url: form.getAttribute('action'),
				dataType: "JSON",
				type: "post",
				data: dadosFormulario,

				//data: {'apelido': val_apelido, 'idade': val_idade, 'sexo': val_sexo, 'email': val_email, 'senha': val_senha, 'confirmacaoDeSenha': campoCadastro_confirmacaoDeSenha},
				
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
				.removeClass('carregando');
		},



		enviou: function(data) {
			if(data.status) {
				//console.debug(data.dados);
				var email = $("#campoCadastro_email").val();

				$("#"+this._idFormulario).get(0).reset();
				$("#campoLogin_email").val(email);

				alert(i18n.t("acesso.cadastre-se.confirmacaoCadastre-se"));
				APP.GerenciadorDeTelas.exibir("#tela_entrarComLoginESenha");


				APP.analytics.trackEvent('cadastro', 'realizado');
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
			APP.analytics.trackEvent('cadastro', 'erro no envio');
			var mensagem = "Erro.";

			if(feedback) {
				mensagem += "\n "+feedback;
			}

			alert(mensagem);

			this.removerCarregando();
		}
	}
}