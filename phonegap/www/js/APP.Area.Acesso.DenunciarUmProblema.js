var APP = APP || {};
APP.Area = APP.Area || {};
APP.Area.Acesso = APP.Area.Acesso || {};
APP.Area.Acesso.DenunciarUmProblema = {
	setUp: function() {

	},

	Formulario: {

		_idFormulario: "formularioDenunciarUmProblema",

		setUp: function() {
			this.aplicarValidacao();
		},

		aplicarValidacao: function() {
			var that = this,
				opcoesDeValidacao = {
					// debug: true,
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
								APP.analytics.trackEvent('denunciar um problema', 'erro', placeholder);
							}

							alert(mensagem);
						}

					},
					//Campos para validação
					rules: {
					    mensagem: {
					    	required: true
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
				data: {
					idusuario: APP.Area.Acesso.Usuario._dados.userID,
					msg: $("#denunciarProblema_mensagem").val()
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
				alert(i18n.t('acesso.denunciarUmProblema.confirmacao'));
				APP.analytics.trackEvent('denunciar um problema', 'realizado');
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
			APP.analytics.trackEvent('denunciar um problema', 'erro no envio');
			var mensagem = "Erro.";

			if(feedback) {
				mensagem += "\n "+feedback;
			}

			alert(mensagem);

			this.removerCarregando();
		}
	}
}