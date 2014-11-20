var APP = APP || {};
APP.Area = APP.Area || {};
APP.Area.Saude = APP.Area.Saude || {};
APP.Area.Saude.Mapa = {
	_lista: null,
	_googleMapsApiKey: "AIzaSyC_Nv_pzaJgeAcIy28RNbzoCaSNvAOd3Xw",
	_carregado: false,

	setUp: function() {

		if(window.isphone) {
			$("#tela_mapaSaude").on(APP._tapEvent, function(event) {
				if(event.target.tagName == "A" && event.target.getAttribute('href').match("http://")) {
					var href;
					event.preventDefault();
					href = event.target.getAttribute('href');

					APP.analytics.trackEvent('mapa', 'rota', href);
					window.open(href, '_system');
				}
			});
		}
	},
	mostrar: function(lista){ 
		this._lista = lista;
		$("#tela_mapaSaude").removeClass('farmacias').removeClass('hospitaisDeReferencia').addClass(lista);
		APP.GerenciadorDeTelas.exibir("#tela_mapaSaude");
		APP.analytics.trackView(lista);
	},

	mostrarFarmacias: function() {
		this.mostrar("farmacias");
	}, 

	mostrarHospitais: function() {
		this.mostrar("hospitais");
	},

	posCarregamento: function() {
		var that = this;

		var tela = this._lista == "farmacias" ? "farmacias" : "hospitais";

		$("#barraDeTitulo .titulo").text(i18n.t("tela."+tela));

		if(this._carregado === true && !$("#tela_mapaSaude").is(":empty")) {
			if(APP.GerenciadorDeTelas._idTelaAnterior == "tela_farmacias" || APP.GerenciadorDeTelas._idTelaAnterior == "tela_hospitaisDeReferencia") {
				this.atualizar();
			} else {
				this.atualizar();
			}
		} else {


			$("#tela_mapaSaude").addClass('loading');

			var googleMapsScript = "http://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&sensor=true&callback=APP.Area.Saude.Mapa.carregarMapa";
				googleMapsScript = googleMapsScript.replace("YOUR_API_KEY", this._googleMapsApiKey);
			
			head.load(googleMapsScript);
			//$("#tela_mapaSaude .legendas").hide();
		}
	},

	carregarMapa: function() {
		var that = this;

		var opcoes = {
			success: function(position) {
				var coords = position.coords;
				var latLng = new google.maps.LatLng(coords.latitude, coords.longitude);
				$('#googleMap').gmap({
	            	'center': latLng,
	            	zoomControl: false,
	            	callback: function() {
		            	setTimeout(function() {
		            		that._carregado = true;
		            		that.atualizar();
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

	atualizar: function() {
		this.MinhaLocalizacao.capturar();

		if(this._lista == "farmacias") {
			$("#tela_mapaSaude .legendas").addClass("farmacias").removeClass('hospitais');
		} else {
			$("#tela_mapaSaude .legendas").addClass("hospitais").removeClass('farmacias');
		}
	},

	MinhaLocalizacao: {
		capturar: function() {
			var that = this;
			var opcoes = {
				success: function(position) {
					that.capturou.call(that, position);
				},
				error: function(error) {
					that.naoCapturou.call(that, error);
				}
			}

			$.geolocation.get(opcoes);
		
		},

		capturou: function(position) {
			var coords = position.coords;
			var meuLatLng = new google.maps.LatLng(coords.latitude, coords.longitude);
			var sexo = APP.Area.Acesso.Usuario._dados.sexo;
			$('#googleMap')
				.gmap("clear","markers")
				.gmap('addMarker', { 
					'position': meuLatLng,
					'animation': google.maps.Animation.BOUNCE,
					'icon': 'imagens/saude/mapa/icone_'+sexo+'.png'
				});

			if(this.pai()._lista == "hospitaisDeReferencia") {
				this.pai().MinhaUF.capturar(coords);
			}
			this.pai().Locais.carregar(coords);
			APP.analytics.trackEvent('localizacao', 'encontrada', 'tela', APP.GerenciadorDeTelas._idTelaAtual);
		},

		naoCapturou: function() {
			APP.analytics.trackEvent('localizacao', 'nao encontrada', 'tela', APP.GerenciadorDeTelas._idTelaAtual);
		}
	},

	Locais: {
		setUp: function() {

		},

		carregar: function(coords) {
			var that = this;
			$.ajax({
				url: "http://saudenacopa.epitrack.com.br/proxySaude/",
				//url: "farmacias.json",
				type: "POST",
				data: {
					lista: that.pai()._lista,
					latLng: coords.latitude+","+coords.longitude
				},
				dataType: "json",
				beforeSend: function() { that.carregando.apply(that,arguments); },
				success: function(data, text, xhr) { that.carregou.call(that, data, coords); },
				error: function() { that.naoCarregou.apply(that,arguments); }
			})
		},

		carregou: function(data, coords) {
			if(data.status == "OK") {

				this.atualizarMarcadores(data.results, coords);
			}
		},

		carregando: function() {

		},

		naoCarregou: function() {

		},	

		montarConteudo: function(local, userLocation) {
			var tipo = APP.Area.Saude.Mapa._lista == "farmacias" ? "farmacia" : "hospital";

			var hospital = $("<div>").addClass('infoHospital');
			$("<h1>").text(local.name).appendTo(hospital);

			$("<p>").text(local.vicinity).appendTo(hospital);
			var rotaParams = "saddr="+userLocation.latitude+","+userLocation.longitude+"&daddr="+local.geometry.location.lat+","+local.geometry.location.lng;
			var rotaGoogle = "http://maps.google.com/maps?"+rotaParams;
			var rotaApple = "http://maps.apple.com/?"+rotaParams;

			$("<h2>").text(i18n.t("saude.mapa.tracarRota")).appendTo(hospital);
			$("<a>").attr("href",rotaApple).text(i18n.t("saude.mapa.rotaApple")).appendTo(hospital);
			$("<a>").attr("href",rotaGoogle).text(i18n.t("saude.mapa.rotaGoogle")).appendTo(hospital);
			
			APP.analytics.trackEvent('mapa', 'balão', tipo);
			APP.analytics.trackEvent('mapa', tipo, local[2]);

			return hospital.get(0);
		},

		atualizarMarcadores: function(resultados, coords) {

			var montarConteudo = this.montarConteudo;

			var tipo = this.pai()._lista == "farmacias" ? "farmacia" : "hospital";
			$(resultados).each(function(iLocal, local){
				var conteudo = montarConteudo(local, coords);
				$('#googleMap').gmap('addMarker', { 
					'position': new google.maps.LatLng(local.geometry.location.lat, local.geometry.location.lng), 
					'bounds': true
					,'icon': 'imagens/saude/mapa/icone_'+tipo+'Google.png'
				}).click(function() {
					$('#googleMap').gmap('openInfoWindow', { 'content': conteudo }, this);
				});

			});
		}
	},

	MinhaUF: {
		capturar: function(coords) {
			var that = this;
			$.ajax({
				url: "http://www.saudenacopa.com/proxyUF/",
				data: {
					latLng: [coords.latitude, coords.longitude].join(",")
				},
				dataType: "JSON",
				type: "post",
				success: function(data) { that.capturou.call(that, data, coords); },
				error: function() { that.naoCapturou.apply(that, arguments); }
			})
		},

		capturou: function(data, coords) {
			if(data.results && data.results.length > 0) {
				var penultimo = data.results.length - 2;
				var dados = data.results[penultimo];
				var uf = dados.address_components[0].short_name;
				this.pai().atualizarMarcadores(uf, coords);
			}
		},

		naoCapturou: function() { 
			//alert()
		}
	},

	montarConteudo: function(local,userLocation) {
		//["UF", "MUNICÍPIO", "ESTABELECIMENTO", "TELEFONE", "ENDEREÇO", "GEOLOCALIZAÇÃO"]

		var hospital = $("<div>").addClass('infoHospital');
		$("<h1>").text(local[2]).appendTo(hospital);

		var numeros = local[3].split("/");
		if(numeros.length > 0) {
			$(numeros).each(function(iNumero, numero) {
				$("<a>").attr("href","tel://"+numero.replace(/[^\d]/g,"")).text(numero).appendTo(hospital);
			})
		}
		$("<p>").text(local[4]+" "+local[1]+"-"+local[0]).appendTo(hospital);
		var rotaParams = "saddr="+userLocation.latitude+","+userLocation.longitude+"&daddr="+local[5];
		var rotaGoogle = "http://maps.google.com/maps?"+rotaParams;
		var rotaApple = "http://maps.apple.com/?"+rotaParams;

		$("<h2>").text(i18n.t("saude.mapa.tracarRota")).appendTo(hospital);
		$("<a>").attr("href",rotaApple).text(i18n.t("saude.mapa.rotaApple")).appendTo(hospital);
		$("<a>").attr("href",rotaGoogle).text(i18n.t("saude.mapa.rotaGoogle")).appendTo(hospital);

		APP.analytics.trackEvent('mapa', 'balão', 'hospital de referência');
		APP.analytics.trackEvent('mapa', 'hospital de referência', local[2]);

		return hospital.get(0);
	},

	atualizarMarcadores: function(uf, coords) {
		$("#tela_mapaSaude").removeClass('loading');

		var dados = APP.Area.Saude.Hospitais._enderecos;
		var tela = $('#googleMap');
		var montarConteudo = this.montarConteudo;

		$(dados.dados).each(function(iLocal, local){
			var ufLocal = local[0];

			if(uf == ufLocal) {

				var conteudo = montarConteudo(local, coords);
				var position = local[5].split(",");
				tela.gmap('addMarker', { 
					'position': new google.maps.LatLng(position[0], position[1]), 
					'bounds': true,
					'icon': 'imagens/saude/mapa/icone_hospitalReferencia.png'
				}).click(function() {
					$('#googleMap').gmap('openInfoWindow', { 'content': conteudo }, this);
				});
			}

		});
	}
}