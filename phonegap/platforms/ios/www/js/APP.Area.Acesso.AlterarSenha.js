var APP = APP || {};
APP.Area = APP.Area || {};
APP.Area.Acesso = APP.Area.Acesso || {};
APP.Area.Acesso.AlterarSenha = {
	setUp: function() {

	},

	Formulario: {

		_idFormulario: "formularioAlterarSenha",

		setUp: function() {
			this.aplicarValidacao();
		},

		aplicarValidacao: function() {
			var that = this,
				opcoesDeValidacao = {
					//debug: true,
					submitHandler: function(formulario) {
						that.enviar(formulario)
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
								APP.analytics.trackEvent('alterar senha', 'erro', placeholder);
							}

							alert(mensagem);
						}

					},
					//Campos para validação
					rules: {
					    senhaAtual: {
					    	required: true
					    },
					    novaSenha: {
					    	required: true
					    },
					    repetirNovaSenha: {
					    	required: true,
					    	equalTo: "#campoAlterarSenha_novaSenha"
					    }

					}
				};

			$("#"+this._idFormulario).validate(opcoesDeValidacao);
		},

		enviar: function(form) {
			var that = this;

			var dadosFormulario = $("#"+this._idFormulario).serializeJSON();
			dadosFormulario.userID = APP.Area.Acesso.Usuario._dados.userID;

			$.ajax({
				url: form.getAttribute('action'),
				dataType: "JSON",
				data: dadosFormulario,
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
				.removeClass('carregando');
		},

		enviou: function(data) {
			if(data.status) {
				alert(i18n.t('acesso.alterarSenha.confirmacao'));
				APP.analytics.trackEvent('alterar senha', 'realizado');
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
			APP.analytics.trackEvent('alterar senha', 'erro no envio');
			var mensagem = "Erro.";

			if(feedback) {
				mensagem += "\n "+feedback;
			}

			alert(mensagem);

			this.removerCarregando();
		}
	}
}