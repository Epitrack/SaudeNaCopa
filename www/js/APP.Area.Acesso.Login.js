var APP = APP || {};
APP.Area = APP.Area || {};
APP.Area.Acesso = APP.Area.Acesso || {};
APP.Area.Acesso.Login = {
	setUp: function() {

	},

	Formulario: {

		_idFormulario: "formularioLogin",
		_dadosUsuario: {
			nome: "Usuário logado",
			email: "e-mail@usuario.com.br",
			id: "3857239934"
		},

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
								APP.analytics.trackEvent('login', 'erro', mensagem);
							}

							alert(mensagem);
						}

					},
					//Campos para validação
					rules: {
					    login: {
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
			try {
				$.ajax({
					url: form.getAttribute('action'),
					data: $("#"+this._idFormulario).serialize(),
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
			} catch(e) {
				alert(e);
			}
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

				this.pai().pai().Usuario.gravar(data.usuario);
				APP.GerenciadorDeTelas.exibir("#tela_torcedor");
				$("#"+this._idFormulario).get(0).reset();
				APP.analytics.trackEvent('login', 'realizado');
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
			APP.analytics.trackEvent('login', 'erro envio');
			var mensagem = "Erro.";

			if(feedback) {
				mensagem += "\n "+feedback;
			}

			alert(mensagem, feedback);

			this.removerCarregando();
		}
	}
}