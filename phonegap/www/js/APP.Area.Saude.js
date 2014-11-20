var APP = APP || {};
APP.Area = APP.Area || {};
APP.Area.Saude = {
	setUp: function() {},
	Saude:  {
		setUp: function() {
			var that = this;
			$("a[href^='#mapa_']").on(APP._tapEvent, function(event){
				event.preventDefault(); 
				var href = $(event.currentTarget).attr('href');
				var lista = href.split("_")[1];
				that.pai().Mapa.mostrar(lista);
			});
		}
	},
	TelefonesUteis: {
		setUp: function() {
			$("#tela_telefonesUteis").on(APP._tapEvent,"a[href^='tel:']", function(event){
				event.preventDefault(); 
				var endereco = event.currentTarget.getAttribute("href").replace(":","://");
				APP.analytics.trackEvent('telefone útil', 'ligando', endereco.replace("://",""));
				window.location.href=endereco;
				//alert('foi');
			});
		}
	}

	/*
	

Mapa: {
		_googleMapsApiKey: "AIzaSyC_Nv_pzaJgeAcIy28RNbzoCaSNvAOd3Xw",
		_carregado: false,
		setUp: function() {},
		carregar: function() {
			
		},
		posCarregamento: function() {
			var that = this;
			if(this._carregado === true && !$("#tela_mapaSaude").is(":empty")) {
				if(APP.GerenciadorDeTelas._idTelaAnterior == "tela_farmacias" || APP.GerenciadorDeTelas._idTelaAnterior == "tela_hospitaisDeReferencia") {
					this.atualizarMarcadores();
				} else {
					this.atualizarHospitais();
				}
			} else {


				$("#tela_mapaSaude").addClass('loading');

				var googleMapsScript = "http://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&sensor=true&callback=APP.Area.Saude.Mapa.carregarMapa";
					googleMapsScript = googleMapsScript.replace("YOUR_API_KEY", this._googleMapsApiKey);
				
				head.load(googleMapsScript);
			}
		},

		atualizarMarcadores: function() {
			$("#tela_mapaSaude").removeClass('loading');

			var dados = APP.Area.Saude.Locais.Geolocalizacao.Locais._dados;
			if(dados !== null && dados.results) {

				$('#tela_mapaSaude').gmap("clear","markers");
				
				var meusDados = this.pai().Locais.Geolocalizacao._dados;
				var coords = meusDados.coords;
				var meuLatLng = new google.maps.LatLng(coords.latitude, coords.longitude);
				$('#tela_mapaSaude').gmap('addMarker', { 
					'position': meuLatLng,
					'animation': google.maps.Animation.BOUNCE,
					'icon': 'imagens/saude/mapa/icone_eu.png'
				})


				 $(dados.results).each(function(iLocal, local){
				 		var localtion = local.geometry.location;

				 		$('#tela_mapaSaude').gmap('addMarker', { 
							'position': new google.maps.LatLng(localtion.lat, localtion.lng), 
							'bounds': true 
						}).click(function() {
							$('#tela_mapaSaude').gmap('openInfoWindow', { 'content': local.name }, this);
						});
				 });
			}
		},

		atualizarHospitais: function() {
			this.pai().Hospitais.atualizar();
		},

		carregarMapa: function() {
			var that = this;
			var dados = this.pai().Locais.Geolocalizacao._dados;

			var opcoes = {
				success: function(position) {
					var coords = position.coords;
					var latLng = new google.maps.LatLng(coords.latitude, coords.longitude);
					$('#tela_mapaSaude').gmap({
		            	'center': latLng,
		            	callback: function() {
			            	setTimeout(function() {
			            		that._carregado = true;
			            		if(APP.GerenciadorDeTelas._idTelaAnterior == "tela_farmacias" || APP.GerenciadorDeTelas._idTelaAnterior == "tela_hospitaisDeReferencia") {
									that.atualizarMarcadores();
								} else {
									that.atualizarHospitais();
								}
			            	}, 500);
			            }
		            });
				},
				error: function(error) {
					that.capturarErrorHandler.call(that, error, lista);
				},
			}
			$.geolocation.get(opcoes);

			

            
		},
	},
	Locais: {
		setUp: function() {},

		Geolocalizacao: {
			_dados: null,
			setUp: function() {
				
			},


			capturar: function(lista) {
				$("#tela_"+lista).addClass('loading').find("ul").empty();
				$("#barraDeTitulo .avancar").hide();

				var that = this;
				var opcoes = {
					success: function(position) {
						that.capturarSuccessHandler.call(that, position, lista);
					},
					error: function(error) {
						that.capturarErrorHandler.call(that, error, lista);
					},
				}
				$.geolocation.get(opcoes);
			},

			capturarSuccessHandler: function(position, lista) {
				this._dados = position;
				var latLng = [position.coords.latitude, position.coords.longitude];
				$("#tela_"+lista).removeClass('loading');
				this.Locais.carregar(latLng.join(","), lista);
			},

			capturarErrorHandler: function(error, lista) {
				$("#tela_"+lista).removeClass('loading');
				alert(i18n.t("acesso.geolocalizacao.erro"), lista);
			},

			Locais: {
				_dados: null,
				setUp: function() {},

				carregar: function(latLng, lista) {
					var that = this;
					$.ajax({
						url: "http://saudenacopa.epitrack.com.br/proxySaude/",
						//url: "farmacias.json",
						type: "POST",
						data: {
							lista: lista,
							latLng: latLng
						},
						dataType: "json",
						beforeSend: function() { that.carregando.call(that, lista); },
						success: function(data, text, xhr) { that.carregou.call(that, data,text,xhr,lista); },
						error: function() { that.naoCarregou.call(that, lista); }
					})
				},

				carregando: function(lista) {
					$("#tela_"+lista).addClass('loading');
				},

				carregou: function(data, text, xhr, lista) {
					if(data.status == "OK") {
						this._dados = data;
						var fragmento = document.createDocumentFragment();

						$(data.results).each(function(iLocal, local){

							var li = $("<li>")
								.append("<span>").find("span").addClass('celula')
								.text(local.name)
								.attr('id', local.id)
								.attr('data-latitude', local.geometry.location.lat)
								.attr('data-longitude', local.geometry.location.lng)
								.end().get(0);

							fragmento.appendChild(li);

						});

						$("#tela_"+lista).removeClass('loading');
						$("#barraDeTitulo .avancar").show();
						$("#lista_"+lista).empty().get(0).appendChild(fragmento);
						APP.Area.Rolagem._iScroll["tela_"+lista].refresh();
					} else {
						$("#tela_"+lista).removeClass('loading');
						alert(i18n.t("saude.farmacias.erro"))
					}
				},

				naoCarregou: function(lista) {
					$("#tela_"+lista).removeClass('loading');
					var resposta = confirm("Falha ao carregar. Tentar novamente?");
					if(resposta) {
						this.pai().capturar(lista);
					} else {
						APP.GerenciadorDeTelas.exibir("#tela_saude");
					}
				}
			}
		}
	}

	 */

	
}