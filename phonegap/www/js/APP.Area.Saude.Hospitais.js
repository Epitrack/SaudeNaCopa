var APP = APP || {};
APP.Area = APP.Area || {};
APP.Area.Saude = APP.Area.Saude || {};
APP.Area.Saude.Hospitais = {

    setUp: function() {

        if(window.isphone) {
            $("#tela_mapaSaude").on(APP._tapEvent, function(event) {
                if(event.target.tagName == "A" && event.target.getAttribute('href').match("http://")) {
                    event.preventDefault();
                    window.open(event.target.getAttribute('href'), '_system');
                }
            });
        }
    },

    atualizar: function() {
        this.MinhaLocalizacao.capturar();
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
            $('#tela_mapaSaude')
                .gmap("clear","markers")
                .gmap('addMarker', { 
                    'position': meuLatLng,
                    'animation': google.maps.Animation.BOUNCE,
                    'icon': 'imagens/saude/mapa/icone_'+sexo+'.png'
                });

            this.pai().MinhaUF.capturar(coords);
            this.pai().Locais.carregar(coords);
        },

        naoCapturou: function() {

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
                    lista: "hospitaisDeReferencia",
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
            var hospital = $("<div>").addClass('infoHospital');
            $("<h1>").text(local.name).appendTo(hospital);

            $("<p>").text(local.vicinity).appendTo(hospital);
            var rotaParams = "saddr="+userLocation.latitude+","+userLocation.longitude+"&daddr="+local.geometry.location.lat+","+local.geometry.location.lng;
            var rotaGoogle = "http://maps.google.com/maps?"+rotaParams;
            var rotaApple = "http://maps.apple.com/?"+rotaParams;

            $("<h2>").text(i18n.t("saude.mapa.tracarRota")).appendTo(hospital);
            $("<a>").attr("href",rotaApple).text(i18n.t("saude.mapa.rotaApple")).appendTo(hospital);
            $("<a>").attr("href",rotaGoogle).text(i18n.t("saude.mapa.rotaGoogle")).appendTo(hospital);
            return hospital.get(0);

            APP.analytics.trackEvent('Mapa', 'balão', 'hospital', 1);
            APP.analytics.trackEvent('Hospital', 'informação', local.name, 1);
        },

        atualizarMarcadores: function(resultados, coords) {

            var montarConteudo = this.montarConteudo;
            $(resultados).each(function(iLocal, local){
                var conteudo = montarConteudo(local, coords);
                $('#tela_mapaSaude').gmap('addMarker', { 
                    'position': new google.maps.LatLng(local.geometry.location.lat, local.geometry.location.lng), 
                    'bounds': true
                    ,'icon': 'imagens/saude/mapa/icone_hospitalGoogle.png'
                }).click(function() {
                    $('#tela_mapaSaude').gmap('openInfoWindow', { 'content': conteudo }, this);
                });

            });
        }
    },

    MinhaUF: {
        capturar: function(coords) {
            var that = this;
            $.ajax({
                url: "http://www.saudenacopa.epitrack.com.br/proxyUF/",
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
        return hospital.get(0);
    },

    atualizarMarcadores: function(uf, coords) {
        $("#tela_mapaSaude").removeClass('loading');

        var dados = APP.Area.Saude.Hospitais._enderecos;
        var tela = $('#tela_mapaSaude');
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
                    $('#tela_mapaSaude').gmap('openInfoWindow', { 'content': conteudo }, this);
                });
            }

        });
    }
}