var Main = {};

Main = {
    _BASEPATH: 'dist/images/',
    _currentScreen: null,

    setUp: function(){
        var that = this;

        that._currentScreen = $('body').data('screen');

        // MOBILE
        // mostrando conteúdo e alterando idioma
        if(screen.width > 480) {
            // desktop
        } else {
            // mobile
            that.Mobile.getLanguage();
            that.Mobile.showConteudo();
            that.Mobile.changeLanguage();
        }

        // Eventos google
        that.Events.trackingEvents();

        // language
        that.language.i18next();

        // language
        that.language.detectLanguage();

        // alterando linguagem a partir do clique
        that.language.changeLanguage();

        // iframe WebApp
        that.WebApp.openWebApp();


        if (that._currentScreen == 'home') {
            that.tabs.tabsCidades();
            that.gmaps.createMap();
            that.graphicInternal.createGraphic();
            that.formSend.envioForm();
        };

        if(that._currentScreen == 'internal') {
            that.tabs.tabsCharts();
            that.tabs.tabsCidades();
            that.gmaps.createMap();
            that.graphicInternal.createGraphic();
            that.Modal.setUp();
            that.Range.sliderRange();
        };

        if(that._currentScreen == 'changePassword') {
            that.changePass.validarSenha();
        }

        if(that._currentScreen == 'contact') {
            that.contactSend.send();
        }

        if(that._currentScreen == 'problem') {
            that.problemSend.reportProblemSend();
        }

        if(that._currentScreen == 'about') {
            // assim que entrar na página é detectado o idioma do i18 para alterar a imagem de sobre
            var language = i18n.lng();
            var logoSobre = document.querySelector('#logoInternal');

            // trocar a imagem de sobre
            if(language == 'en-US') {
                logoSobre.classList.add('sprite-logo-en');
            } if (language == 'es') {
                logoSobre.classList.add('sprite-logo-es');
            } else {
                logoSobre.classList.add('sprite-logo-br');
            }
        }

    },

    Modal: {
        _modalOverlay: '',

        setUp: function(){
            var that = this;

            $('#title-graphics').on('click', function(event){
                var currentTarget = event.currentTarget,
                    url = currentTarget.href;

                that._modalOverlay = $('<div>')
                    .addClass('modalOverlay')
                    .appendTo('body');

                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'html',
                    beforeSend: function(){
                        $('<img>').attr({
                            src: Main._BASEPATH + 'loading.gif',
                            alt: 'Carregando'
                        })
                        .addClass('loading')
                        .fadeIn()
                        .appendTo(that._modalOverlay);
                    },

                    complete: function(){
                        $('.loading').remove();
                    },

                    success: function(data){
                        $(data).fadeIn().appendTo(that._modalOverlay);
                    }
                });

                event.preventDefault();
            })

            $('body').on('click', '.btn-close', function(event){
                that._modalOverlay.fadeOut();

                setTimeout(function(){
                    that._modalOverlay.remove();
                }, 1000);
            })
        }
    },

    language: {
        i18next: function() {
            i18n.init({
                fallbackLng: 'pt-BR', // fallback para quando não definir a linguagem
                // debug: true, // debug do plugin (substitui o console.log)
                fixLng: true //preservar o cookie com a linguagem definida
            },
            function(translation){
                $('[data-i18n]').i18n();

                var appName = translation('app.name');
            });
        },

        i18br: function() {
            i18n.setLng('pt-BR', {fixLng: true}, function(t) {
                $('[data-i18n]').i18n();

                // alterando logo de sobre
                var logoSobre = document.querySelector('#logoInternal');
                    logoSobre.classList.remove('sprite-logo-br', 'sprite-logo-es', 'sprite-logo-en');
                    logoSobre.classList.add('sprite-logo-br');
            });
        },

        i18es: function() {
            i18n.setLng('es', {fixLng: true}, function(t) {
                $('[data-i18n]').i18n();

                // alterando logo de sobre
                var logoSobre = document.querySelector('#logoInternal');
                    logoSobre.classList.remove('sprite-logo-br', 'sprite-logo-es', 'sprite-logo-en');
                    logoSobre.classList.add('sprite-logo-es');
            });
        },

        i18en: function() {
            i18n.setLng('en-US', {fixLng: true}, function(t) {
                $('[data-i18n]').i18n();

                // alterando logo de sobre
                var logoSobre = document.querySelector('#logoInternal');
                    logoSobre.classList.remove('sprite-logo-br', 'sprite-logo-es', 'sprite-logo-en');
                    logoSobre.classList.add('sprite-logo-en');
            });
        },

        detectLanguage: function() {
            // Assim que entrar na página é detectado o idioma do i18 para alterar a imagem da logo principal
            var language = i18n.lng();
            var logoPrimary = document.querySelector('#logo-primary'),
            btnWebapp = document.querySelector('#btn-webapp'),
            languageBR = document.querySelector('#language-br'),
            languageES = document.querySelector('#language-es'),
            languageEN = document.querySelector('#language-en');

            if(language == 'en-US' || language == 'en') {
                // console.log('Minha linguagem é ' + language + ' INGLES');
                logoPrimary.classList.add('sprite-logo-primary-en');
                btnWebapp.classList.add('btn-webapp-en');
                languageES.classList.add('language-active');
                languageBR.classList.add('language-active');
                languageEN.classList.remove('language-active');
            } if (language == 'es') {
                // console.log('Minha linguagem é ' + language + ' ESPANHOL');
                logoPrimary.classList.add('sprite-logo-primary-es');
                $('#language-es').removeClass('language-active');
                btnWebapp.classList.add('btn-webapp-es');
                languageEN.classList.add('language-active');
                languageBR.classList.add('language-active');
                languageES.classList.remove('language-active');
            } if (language == 'pt-BR') {
                // console.log('Minha linguagem é ' + language + ' BRASIL');
                logoPrimary.classList.add('sprite-logo-primary-br');
                $('#language-br').removeClass('language-active');
                btnWebapp.classList.add('btn-webapp');
                languageEN.classList.add('language-active');
                languageES.classList.add('language-active');
                languageBR.classList.remove('language-active');
            }
        },

        changeLanguage: function() {
            $('#language-br').on('click', function(){
                Main.language.i18br();

                // indicando a linguagem ativa
                $('.box-languages li a').addClass('language-active');
                $('#language-br').removeClass('language-active');

                // alterando logo principal
                var logoPrimary = document.querySelector('#logo-primary');
                logoPrimary.classList.remove('sprite-logo-primary-br', 'sprite-logo-primary-es', 'sprite-logo-primary-en');
                logoPrimary.classList.add('sprite-logo-primary-br');

                var btnWebapp = document.querySelector('#btn-webapp');
                btnWebapp.classList.remove('btn-webapp', 'btn-webapp-es', 'btn-webapp-en');
                btnWebapp.classList.add('btn-webapp');
            });

            $('#language-es').on('click', function(){
                Main.language.i18es();

                // indicando a linguagem ativa
                $('.box-languages li a').addClass('language-active');
                $('#language-es').removeClass('language-active');

                // alterando logo principal
                var logoPrimary = document.querySelector('#logo-primary');
                    logoPrimary.classList.remove('sprite-logo-primary-br', 'sprite-logo-primary-es', 'sprite-logo-primary-en');
                    logoPrimary.classList.add('sprite-logo-primary-es');

                    var btnWebapp = document.querySelector('#btn-webapp');
                        btnWebapp.classList.remove('btn-webapp', 'btn-webapp-es', 'btn-webapp-en');
                        btnWebapp.classList.add('btn-webapp-es');
            });

            $('#language-en').on('click', function(){
                Main.language.i18en();

                // indicando a linguagem ativa
                $('.box-languages li a').addClass('language-active');
                $('#language-en').removeClass('language-active');

                // alterando logo principal
                var logoPrimary = document.querySelector('#logo-primary');
                    logoPrimary.classList.remove('sprite-logo-primary-br', 'sprite-logo-primary-es', 'sprite-logo-primary-en');
                    logoPrimary.classList.add('sprite-logo-primary-en');

                var btnWebapp = document.querySelector('#btn-webapp');
                    btnWebapp.classList.remove('btn-webapp', 'btn-webapp-es', 'btn-webapp-en');
                    btnWebapp.classList.add('btn-webapp-en');
            });
        }
    },

    gmaps: {
        _markers: [],

         carregarPontos:function (data1,data2) {

         	var map = Main.gmaps.map;
         	var that = Main.gmaps;

         	for (var i = 0; i < that._markers.length; i++) {
			    that._markers[i].setMap(null);
			  }

         	that._markers = [];

                    $.ajax({
                        url: 'proxy.php?data1='+data1+"&data2="+data2,
                        dataType: 'JSON',
                        beforeSend: function() {
                            $("#loading").addClass('loading');
                        },

                        success: function(pontos) {
                            $('#loading').removeClass('loading');
                            for (i = 0; i < pontos.locais.length; i++) {
                                var id = pontos.locais[i].Id,
                                latitude = pontos.locais[i].Latitude,
                                longitude = pontos.locais[i].Longitude,
                                descricao = pontos.locais[i].Descricao,
                                icone = pontos.locais[i].Icone,
                                sintoma = pontos.locais[i].Sintoma,
                                latlngbounds = new google.maps.LatLngBounds();

                                    var marker = new google.maps.Marker({
                                        position: new google.maps.LatLng(latitude, longitude),
                                        map: map,
                                        icon: Main._BASEPATH + icone + '.png'
                                    });

                                    if (sintoma != undefined) {
                                        marker["sintoma"] = sintoma;
                                    };

                                    // agrupar marcadores
                                    that._markers.push(marker);

                                } //end for


                                    //////////////////
                                    // ZOOM CIDADES
                                    //////////////////
                                        $('#fortaleza').on('change', function(){
                                            var map = Main.gmaps.map,
                                                latLngFortaleza = new google.maps.LatLng(-3.7318616, -38.5266704);
                                            map.setZoom(9);
                                            map.setCenter(latLngFortaleza);

                                            // var bermudaTriangle;

                                            // var triangleCoords = [
                                            //     new google.maps.LatLng(-3.7318616, -38.5266704),
                                            //     new google.maps.LatLng(-3.7250096779959736, -38.643400136328125),
                                            //     new google.maps.LatLng(-3.809969709366486, -38.571989003515625),
                                            //     new google.maps.LatLng(-3.785304749769546, -38.43054002890625)
                                            // ];

                                            // bermudaTriangle = new google.maps.Polygon({
                                            //     paths: triangleCoords,
                                            //     strokeColor: "#FF0000",
                                            //     strokeOpacity: 0.8,
                                            //     strokeWeight: 2,
                                            //     fillColor: "#FF0000",
                                            //     fillOpacity: 0.35
                                            // });

                                            // bermudaTriangle.setMap(map);
                                        });

                                        $('#natal').on('change', function(){
                                            var map = Main.gmaps.map,
                                                latLngNatal = new google.maps.LatLng(-5.7792569, -35.20091600000001);
                                            map.setZoom(8);
                                            map.setCenter(latLngNatal);
                                        });

                                        $('#recife').on('change', function(){
                                            var map = Main.gmaps.map,
                                                latLngRecife = new google.maps.LatLng(-8.0470739, -34.87737930000003);
                                            map.setZoom(7);
                                            map.setCenter(latLngRecife);
                                        });

                                        $('#salvador').on('change', function(){
                                            var map = Main.gmaps.map,
                                                latLngSalvador = new google.maps.LatLng(-12.9722286, -38.50142010000002);
                                            map.setZoom(8);
                                            map.setCenter(latLngSalvador);
                                        });

                                        $('#manaus').on('change', function(){
                                            var map = Main.gmaps.map,
                                                latLngManaus = new google.maps.LatLng(-3.1190275, -60.02173140000002);
                                            map.setZoom(7);
                                            map.setCenter(latLngManaus);
                                        });

                                        $('#cuiaba').on('change', function(){
                                            var map = Main.gmaps.map,
                                                latLngCuiaba = new google.maps.LatLng(-15.6014109, -56.09789169999999);
                                            map.setZoom(8);
                                            map.setCenter(latLngCuiaba);
                                        });

                                        $('#brasilia').on('change', function(){
                                            var map = Main.gmaps.map,
                                                latLngBrasilia = new google.maps.LatLng(-15.7941874, -47.882585199999994);
                                            map.setZoom(7);
                                            map.setCenter(latLngBrasilia);
                                        });

                                        $('#bh').on('change', function(){
                                            var map = Main.gmaps.map,
                                                latLngBh = new google.maps.LatLng(-19.9191013, -43.93860940000002);
                                            map.setZoom(8);
                                            map.setCenter(latLngBh);
                                        });

                                        $('#sp').on('change', function(){
                                            var latLngSp = new google.maps.LatLng(-23.5505199, -46.63330939999997);
                                            map.setZoom(7);
                                            map.setCenter(latLngSp);
                                        });

                                        $('#rj').on('change', function(){
                                            var map = Main.gmaps.map,
                                                latLngRj = new google.maps.LatLng(-22.9133954, -43.20071009999998);
                                            map.setZoom(8);
                                            map.setCenter(latLngRj);
                                        });

                                        $('#curitiba').on('change', function(){
                                            var map = Main.gmaps.map,
                                                latLngCuritiba = new google.maps.LatLng(-25.4200388, -49.26509729999998);
                                            map.setZoom(7);
                                            map.setCenter(latLngCuritiba);
                                        });

                                        $('#poa').on('change', function(){
                                            var map = Main.gmaps.map,
                                                latLngPoa = new google.maps.LatLng(-30.0346471, -51.217658400000005);
                                            map.setZoom(8);
                                            map.setCenter(latLngPoa);
                                        });

                                    //////////////////
                                    // FILTRO-PONTOS
                                    //////////////////
                                        var idsMarcados = null;
                                        $(".list-sintomas input[type=checkbox]").on("change", function(){
                                            idsMarcados = $(".list-sintomas input[type=checkbox]:checked").map(function(iElemento, elemento) {
                                                return elemento.id;
                                            });

                                            for (i=0; i < that._markers.length; i++) {
                                                var matchAll = 0;
                                                for (j=0; j < idsMarcados.length; j++) {
                                                    var symptomId = idsMarcados[j];
                                                    if (jQuery.inArray(symptomId, that._markers[i].sintoma) > -1) {
                                                        matchAll++;
                                                    }
                                                }
                                                that._markers[i].setVisible(false);
                                                if (matchAll == idsMarcados.length) {
                                                    that._markers[i].setVisible(true);
                                                }
                                            }
                                        });

                        }, // end function success

                        error: function() {
                            $("#loading").addClass('error');
                        }
                    });
                        },

        createMap: function() {
            var that = this;
                //////////////////
                // GEOLOCATION
                //////////////////

                // Script que solicita a geolocalização do usuário
                    if (navigator.geolocation) {
                      navigator.geolocation.getCurrentPosition(success, error);
                    } else {
                      error('not supported');
                    }

                // Função de sucesso para a geolocalização.
                    function success(position) {
                      var status = document.querySelector('#status');
                      if (status.className == 'success') {
                        return;
                      }

                      status.innerHTML = "";
                      status.className = 'success';


                  //////////////////
                  // SHOW MAP
                  //////////////////

                  // disabilitando scroll e definindo zoom
                      if(screen.width > 480) {
                        // desktop
                        var isDraggable = true;
                        var isZomm = 4;
                      } else {
                        // mobile
                        var isDraggable = false;
                        var isZomm = 2;
                      }

                      var myLatlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                      var mapOptions = {
                        zoom: isZomm,
                        center: myLatlng,
                        panControl: false,
                        draggable: isDraggable, //disabled scroll in mobile
                        scrollwheel: false, // disabled scroll in desktop
                        overviewMapControl: false,
                        streetViewControl: false,
                        mapTypeControlOptions: {
                          mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style']
                        }
                      }

                  // Exibir o mapa na div #createMap;
                  Main.gmaps.map = new google.maps.Map(document.getElementById('createMap'), mapOptions);

                  var map = Main.gmaps.map;


                  //////////////////
                  // LOAD PINS
                  //////////////////
                  // carregar pontos externos
                    // Main.gmaps.carregarPontos("2014-05-01","2014-07-31");
                    var date = new Date();
                    var data1 = date.getFullYear()+"-"+"0"+(date.getMonth()+1)+"-"+(date.getDate()-2);
                    var data2 = date.getFullYear()+"-"+"0"+(date.getMonth()+1)+"-"+date.getDate();

                    // console.log(data1, data2);

                    Main.gmaps.carregarPontos(data1,data2);
                    // Main.gmaps.carregarPontos(data1,data2);


                  // aqui pode vim o avatar do usuário
                      var image = Main._BASEPATH + 'pin-user-icon.png';
                      var marcadorPersonalizado = new google.maps.Marker({
                        position: myLatlng,
                        map: map,
                        icon: image,
                        animation: google.maps.Animation.BOUNCE
                      });

                    // Exibir texto ao clicar no pin;
                    google.maps.event.addListener(marcadorPersonalizado, 'click', function() {
                        if (marcadorPersonalizado.getAnimation() != null) {
                            marcadorPersonalizado.setAnimation(null);
                        } else {
                            marcadorPersonalizado.setAnimation(google.maps.Animation.BOUNCE);
                        }
                    });

                  //////////////////
                  // CUSTOM MAP
                  //////////////////
                      // Estilizando o mapa;
                      var styles = [
                        {
                          featureType: "all",
                          stylers: [
                            { hue: "#b0d7ec" },
                            { lightness: 75 },
                            { saturation: 90 }
                          ]
                        }
                      ];

                      // crio um objeto passando o array de estilos (styles) e definindo um nome para ele;
                      var styledMap = new google.maps.StyledMapType(styles, {
                        name: "Mapa Style"
                      });

                      // Aplicando as configurações do mapa
                      map.mapTypes.set('map_style', styledMap);
                      map.setMapTypeId('map_style');

                }

                ////////////////////////
                // GEOLOCATION / ERROR
                ////////////////////////
                    // Função de error caso o navegador não suporte a geolocalização
                        function error(msg) {
                          var status = document.querySelector('status');
                          alert('Você precisa permitir a sua localização para poder visualizar o mapa.');
                          // status.innerHTML = typeof msg == 'string' ? msg : "Você não permitiu ser localizado.";
                          status.className = 'fail';
                        }

                }
        // }
    },

    tabs: {
        tabsCidades: function() {
            $('#btn-cidades').on('click', function(){
                $(this).toggleClass('segue');
                $('#content-cidades').toggleClass('hover');
            });
        },
        tabsCharts: function() {
            $('#btn-charts').on('click', function(){
                $('#charts').toggleClass('hover2');
                $('.graphic').toggleClass('inline-block');
                $('#content-charts').toggleClass('hover3');
            });
        }
    },

    changePass: {
        validarSenha: function() {
            var form = document.querySelector('#newPass');
            var newPass = document.querySelector('#input-new-pass');
            var confirmPass = document.querySelector('#input-confirm-pass');
            var submit = document.querySelector('#submit');
            var reply = document.querySelector('#reply');

            function fieldClean() {
                reply.innerHTML = '<p class="send-error">Os campos não podem ficar em branco.</p>';
                newPass.focus();
            }

            function errorLogin() {
                reply.innerHTML = '<p class="send-error">As senhas digitadas não são iguais.</p>';
                newPass.focus();
                newPass.value = '';
                confirmPass.value = '';
            }

            function successLogin() {
                reply.innerHTML = '<p class="send-ok">Sua senha foi alterada com sucesso!</p>';
                location.href = 'interna';
            }

            function submitClickEventHandler(event) {
                if(newPass.value == '' || confirmPass.value == '') {
                    fieldClean();
                } else if(newPass.value !== confirmPass.value) {
                    errorLogin();
                } else {
                    successLogin();
                 }
            }

            submit.addEventListener('click', submitClickEventHandler, false);
        }
    },

    formSend: {
        envioForm: function() {
            var form = document.querySelector('#formPrimary');
            var respostaLogin = document.querySelector('#respostaLogin');
            var submit = document.querySelector('#submit');
            var email = document.querySelector('#email');

            function submitClickEventHandler(event) {
                if(email.value == '' || senha.value == '') {
                    respostaLogin.innerHTML = 'Os campos não pode ficar em branco!';
                    document.getElementById('email').focus();
                } else {
                    submitForm();
                }
            }

            function submitForm() {
                $(form).submit(function(){
                        var email = document.querySelector('#email').value;
                        var senha = document.querySelector('#senha').value;
                        var respostaLogin = document.querySelector('#respostaLogin');

                        // document.getElementById('respostaLogin').fadeIn();
                        $('#respostaLogin').fadeIn();
                        // $("#respostaLogin").fadeIn();
                        respostaLogin.innerHTML = 'Aguarde...';
                        $.post('http://saudenacopa.epitrack.com.br/dashboard/api/login.json.php', {email:email,senha:senha}, function(data) {
                            // console.log(data);
                            // console.log(data.status);

                            if (data.status == false) {
                                respostaLogin.innerHTML = data.mensagem;
                                document.getElementById('formPrimary').reset();
                                document.getElementById('email').focus();
                            }else{
                                respostaLogin.innerHTML = data.mensagem;
                                document.getElementById('formPrimary').reset();
                                window.location.href = 'interna';
                            }
                        },"JSON");
                });
            }

            submit.addEventListener('click', submitClickEventHandler, false);
        }
    },

    graphicInternal: {
        createGraphic: function() {
            $('#saveMap').on('click', function(){
                window.print();
            });

            $.ajax({
                url: 'proxyInterno.php',
                dataType: 'JSON',
                beforeSend: function() {
                    // $("#loading").addClass('loading');
                },

                success: function(values) {

                    // graphics footer
                    var procurouServico = values.campo11;
                    $("#procurouServico").html(procurouServico + '%');

                    var teveContato = values.campo12;
                    $("#teveContato").html(teveContato + '%');

                    var valorHomem = values.campo_masc;
                    $("#valorHomem").html(valorHomem + '%');

                    var valorMulher = values.campo_fem;
                    $("#valorMulher").html(valorMulher + '%');

                    var desktops = values.campo_device_pc;
                    $("#desktops").html(desktops + '%');

                    var tablets = values.campo_device_tablet;
                    $("#tablets").html(tablets + '%');

                    var smartphones = values.campo_device_android;
                    $("#smartphones").html(smartphones + '%');

                    var notebooks = values.campo_device_ios;
                    $("#notebooks").html(notebooks + '%');

                    // graphics sidebar
                    var febre = values.campo1;
                    $(".labelFebre").html(febre + '%');

                    var tosse = values.campo2;
                    $(".labelTosse").html(tosse + '%');

                    var garganta = values.campo3;
                    $(".labelGarganta").html(+ garganta + '%');

                    var faltaAr = values.campo4;
                    $(".labelFaltaAr").html(+ faltaAr + '%');

                    var vomito = values.campo5;
                    $(".labelVomito").html(+ vomito + '%');

                    var diarreia = values.campo6;
                    $(".labelDiarreia").html(+ diarreia + '%');

                    var articulacao = values.campo7;
                    $(".labelArticulacao").html(+ articulacao + '%');

                    var dorCabeca = values.campo8;
                    $(".labelDorCabeca").html(+ dorCabeca + '%');

                    var sangramento = values.campo9;
                    $(".labelSangramento").html(+ sangramento + '%');

                    var manchaCorpo = values.campo10;
                    $(".labelManchaCorpo").html(+ manchaCorpo + '%');

                    var tiveContato = values.campo10;
                    $(".labelTiveContato").html(+ tiveContato + '%');

                }, // end function success

                error: function() {
                    // $("#loading").addClass('error');
                }

              }); //end ajax
        }
    },

    contactSend: {
       send: function() {
           $("#formContact").submit(function(){
               var nome     = $("#inputContactName").val();
               var email    = $("#inputContactEmail").val();
               var telefone = $("#inputContactTel").val();
               var assunto  = $('#inputContactSubject').val();
               var mensagem = $("#textareaContact").val();
               var security_code = $("#security_code").val();

                $("#reply-problem").fadeIn();
                $("#reply-problem").html('Aguarde...');
                $.post('envio_contato.php', {nome:nome, email:email, telefone:telefone, assunto:assunto,  mensagem:mensagem, security_code:security_code}, function(enviar) {
                   if (enviar != false) {
                       $("#reply-problem").html(enviar);
                       document.getElementById("formContact").reset();

                   }else{
                       alert("erro, tente novamente em alguns minutos!")
                   }
                });
            });
       }
    },

    problemSend: {
        reportProblemSend: function() {
            $("#reportProblem").submit(function(){
                var mensagem = $("#textAreaProblem").val();
                var security_code = $("#security_code").val();

                $("#reply-problem").fadeIn();
                $("#reply-problem").html('Aguarde...');
                $.post('envio_problema.php', {mensagem: mensagem, security_code:security_code}, function(enviar) {
                    if (enviar != false) {
                        $("#reply-problem").html(enviar);
                        document.getElementById("reportProblem").reset();

                    }else{
                        $("#reply-problem").html('Erro, tente novamente em alguns minutos!');
                    }
                });
            });
        }
    },

    Range: {
        sliderRange: function() {
          // array com os meses do ano
          var months = ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"];

          $(".range").dateRangeSlider({
            arrows:false, // tirando setas internas
            bounds: { // datas limite minimas e máximas
                min: new Date(2014, 4, 1),
                max: new Date(2014, 6, 31, 12, 59, 59)
            },
            defaultValues: { // valores default mínimo e máximo
                min: new Date(2014, 4, 1),
                max: new Date(2014, 6, 31, 12, 59, 59)
            },
            symmetricPositionning: true, // evita que os ranges passem um pro lado do outro
            range:{ // valores máximo e mínimo que pode-se mecher
                min: {days: 0},
                max: {days: 91}
            },
            // valueLabels: "change", //só mostra os valores quando ocorre mudança
            scales: [{
                first: function(value){
                    return value;
                },
                end: function(value) {
                    return value;
                },
                next: function(value){
                    var next = new Date(value);
                    return new Date(next.setMonth(value.getMonth() + 1));
              },
              label: function(value){
                return months[value.getMonth()];
              },
              format: function(tickContainer, tickStart, tickEnd){ // alterando o estilo dos meses
                tickContainer.addClass("myCustomClass");
              }
            }]
          }); //fim dateRangeSlider

            // detectando as mudanças para cada interação do usuário
            $(".range").on("valuesChanged", function(event, data){

				// var data1 = data.values.min.getFullYear()+"-"+(data.values.min.getMonth()+1)+"-"+data.values.min.getDate();
                var data1 = data.values.min.getFullYear()+"-"+(data.values.min.getMonth()+1)+"-"+(data.values.min.getDate()-2);
				var data2 = data.values.max.getFullYear()+"-"+(data.values.max.getMonth()+1)+"-"+data.values.max.getDate();
                // console.log( "carregarPontos('"+  data.values.min.getFullYear()+"-"+(data.values.min.getMonth()+1)+"-"+data.values.min.getDate() + "' , '"+ data.values.max.getFullYear()+"-"+(data.values.max.getMonth()+1)+"-"+data.values.max.getDate() +"' );"  );

				Main.gmaps.carregarPontos(data1,data2);
                // console.log("A mudança foi entre, min: " + data.values.min + " max: " + data.values.max);
            });

        }
    },

    Mobile: {
        showConteudo: function () {
            var botaoCidades = document.getElementById('btn-cidades-mobile'),
                contentCidades = document.getElementById('content-cidades-mobile'),

                botaoSintomas = document.getElementById('btn-sintomas-mobile'),
                contentSintomas = document.getElementById('content-sintomas-mobile'),

                botaoGraficos = document.getElementById('btn-graficos-mobile'),
                contentGraficos = document.getElementById('content-graficos-mobile'),

                botaoTimeline = document.getElementById('btn-timeline-mobile'),
                contentTimeline = document.getElementById('content-timeline-mobile');

                $(botaoCidades).on('click', function() {
                    $(contentSintomas).removeClass('conteudo-ativo');
                    $(contentGraficos).removeClass('conteudo-ativo');
                    $(contentTimeline).removeClass('conteudo-ativo');

                    $(contentCidades).toggleClass('conteudo-ativo');
                    $(contentCidades).css('background', '#9bb336');
                });

                $(botaoSintomas).on('click', function() {
                    $(contentCidades).removeClass('conteudo-ativo');
                    $(contentGraficos).removeClass('conteudo-ativo');
                    $(contentTimeline).removeClass('conteudo-ativo');

                    $(contentSintomas).toggleClass('conteudo-ativo');
                    $(contentSintomas).css('background', '#2b7998');
                });

                $(botaoGraficos).on('click', function() {
                    var contents = [contentSintomas, contentCidades, contentTimeline];
                    $(contents).removeClass('conteudo-ativo');

                    $(contentGraficos).toggleClass('conteudo-ativo');
                    $(contentGraficos).css('background', '#e7b84c');
                });

                $(botaoTimeline).on('click', function() {
                    var contents = [contentSintomas, contentCidades, contentGraficos];
                    $(contents).removeClass('conteudo-ativo');

                    $(contentTimeline).toggleClass('conteudo-ativo');
                    $(contentTimeline).css('background', '#f1d240');
                });
        },

        changeLanguage: function() {
            // alterando linguagem a partir do clique
            $('#language-br').on('click', function(){
                Main.language.i18br();

                // indicando a linguagem ativa
                $('.box-languages li a').addClass('language-active');
                $('#language-br').removeClass('language-active');

                // alterando logo principal
                var logoPrimary = document.querySelector('#logo-primary');
                    logoPrimary.classList.remove('sprite-logo-primary-br', 'sprite-logo-primary-es', 'sprite-logo-primary-en', 'logo-primary-mobile-br', 'logo-primary-mobile-en', 'logo-primary-mobile-es');
                    logoPrimary.classList.add('logo-primary-mobile-br');

                var btnWebapp = document.querySelector('#btn-webapp');
                    btnWebapp.classList.remove('btn-webapp', 'btn-webapp-es', 'btn-webapp-en');
                    btnWebapp.classList.add('btn-webapp');
            });

            $('#language-es').on('click', function(){
                Main.language.i18es();

                // indicando a linguagem ativa
                $('.box-languages li a').addClass('language-active');
                $('#language-es').removeClass('language-active');

                // alterando logo principal
                var logoPrimary = document.querySelector('#logo-primary');
                    logoPrimary.classList.remove('sprite-logo-primary-br', 'sprite-logo-primary-es', 'sprite-logo-primary-en', 'logo-primary-mobile-br', 'logo-primary-mobile-en', 'logo-primary-mobile-es');
                    logoPrimary.classList.add('logo-primary-mobile-es');

                var btnWebapp = document.querySelector('#btn-webapp');
                    btnWebapp.classList.remove('btn-webapp', 'btn-webapp-es', 'btn-webapp-en');
                    btnWebapp.classList.add('btn-webapp-es');
            });

            $('#language-en').on('click', function(){
                Main.language.i18en();

                // indicando a linguagem ativa
                $('.box-languages li a').addClass('language-active');
                $('#language-en').removeClass('language-active');

                // alterando logo principal
                var logoPrimary = document.querySelector('#logo-primary');
                    logoPrimary.classList.remove('sprite-logo-primary-br', 'sprite-logo-primary-es', 'sprite-logo-primary-en', 'logo-primary-mobile-br', 'logo-primary-mobile-en', 'logo-primary-mobile-es');
                    logoPrimary.classList.add('logo-primary-mobile-en');

                var btnWebapp = document.querySelector('#btn-webapp');
                    btnWebapp.classList.remove('btn-webapp', 'btn-webapp-es', 'btn-webapp-en');
                    btnWebapp.classList.add('btn-webapp-en');
            });
        },

        getLanguage: function() {
            var userLanguage = window.navigator.language,
                logoPrimary = document.querySelector('#logo-primary');
            // alert(userLanguage);

            if(userLanguage == 'en-us') {
                    logoPrimary.classList.remove('sprite-logo-primary-br', 'sprite-logo-primary-es', 'sprite-logo-primary-en', 'logo-primary-mobile-br', 'logo-primary-mobile-en', 'logo-primary-mobile-es');
                    logoPrimary.classList.add('logo-primary-mobile-en');
            }
            if(userLanguage == 'pt-br') {
                    logoPrimary.classList.remove('sprite-logo-primary-br', 'sprite-logo-primary-es', 'sprite-logo-primary-en', 'logo-primary-mobile-br', 'logo-primary-mobile-en', 'logo-primary-mobile-es');
                    logoPrimary.classList.add('logo-primary-mobile-br');
            }
            if(userLanguage == 'es') {
                    logoPrimary.classList.remove('sprite-logo-primary-br', 'sprite-logo-primary-es', 'sprite-logo-primary-en', 'logo-primary-mobile-br', 'logo-primary-mobile-en', 'logo-primary-mobile-es');
                    logoPrimary.classList.add('logo-primary-mobile-es');
            }
        }
    },

    WebApp: {
        openWebApp: function() {
            var btn = document.getElementById('btn-webapp'),
                link = 'http://saudenacopa.epitrack.com.br/webapp/',
                config = "height=568,width=320";

            function btnEventHandler(url) {
                window.open(link, 'WebApp', config);
                url.preventDefault();
            }

            btn.addEventListener('click', btnEventHandler, false);
        }
    },

    Events: {
        trackingEvents: function() {

            // filtro dos mapas
                $('#febre').on('click', function() {
                    ga('send', 'event', 'mapa', 'filtros', 'Febre');
                });

                $('#tosse').on('click', function() {
                    ga('send', 'event', 'mapa', 'filtros', 'Tosse');
                });

                $('#DordeGarganta').on('click', function() {
                    ga('send', 'event', 'mapa', 'filtros', 'Dor de Garganta');
                });

                $('#FaltadeAr').on('click', function() {
                    ga('send', 'event', 'mapa', 'filtros', 'Falta de Ar');
                });

                $('#NauseaeVomitos').on('click', function() {
                    ga('send', 'event', 'mapa', 'filtros', 'Nausea e Vomitos');
                });

                $('#Diarreia').on('click', function() {
                    ga('send', 'event', 'mapa', 'filtros', 'Diarreia');
                });

                $('#DorNasArticulacoes').on('click', function() {
                    ga('send', 'event', 'mapa', 'filtros', 'Dor nas Articulacoes');
                });

                $('#DordeCabeca').on('click', function() {
                    ga('send', 'event', 'mapa', 'filtros', 'Dor de Cabeca');
                });

                $('#Sangramento').on('click', function() {
                    ga('send', 'event', 'mapa', 'filtros', 'Sangramento');
                });

                $('#ManchasVermelhasnoCorpo').on('click', function() {
                    ga('send', 'event', 'mapa', 'filtros', 'Manchas Vermelhas no Corpo');
                });

            // cidades
                $('#btn-cidades').on('click', function() {
                    ga('send', 'event', 'mapa', 'cidades', 'Escolher Cidade');
                });

                $('#fortaleza').on('click', function() {
                    ga('send', 'event', 'mapa', 'cidades', 'Fortaleza');
                });

                $('#natal').on('click', function() {
                    ga('send', 'event', 'mapa', 'cidades', 'Natal');
                });

                $('#recife').on('click', function() {
                    ga('send', 'event', 'mapa', 'cidades', 'Recife');
                });

                $('#salvador').on('click', function() {
                    ga('send', 'event', 'mapa', 'cidades', 'Salvador');
                });

                $('#manaus').on('click', function() {
                    ga('send', 'event', 'mapa', 'cidades', 'Manaus');
                });

                $('#cuiaba').on('click', function() {
                    ga('send', 'event', 'mapa', 'cidades', 'Cuiaba');
                });

                $('#brasilia').on('click', function() {
                    ga('send', 'event', 'mapa', 'cidades', 'Brasilia');
                });

                $('#bh').on('click', function() {
                    ga('send', 'event', 'mapa', 'cidades', 'Belo Horizonte');
                });

            // geral
                // idioma
                    $('#language-br').on('click', function() {
                        ga('send', 'event', 'geral', 'idioma', 'pt-BR');
                    });

                    $('#language-es').on('click', function() {
                        ga('send', 'event', 'geral', 'idioma', 'es');
                    });

                    $('#language-en').on('click', function() {
                        ga('send', 'event', 'geral', 'idioma', 'en-US');
                    });

                // Diversos
                    // btn webapp
                        $('#btn-webapp').on('click', function() {
                            ga('send', 'event', 'geral', 'diversos', 'WebApp');
                        });

                    // btn sair
                    $('.btn-logout').on('click', function() {
                        ga('send', 'event', 'geral', 'diversos', 'Botão Sair');
                    });

                    // nav rodapé
                        $('a[href="termos"]').on('click', function() {
                            ga('send', 'event', 'geral', 'internas', 'Termos de Uso');
                        });

                        $('a[href="sobre"]').on('click', function() {
                            ga('send', 'event', 'geral', 'internas', 'Sobre');
                        });

                        $('a[href="reportar-problema"]').on('click', function() {
                            ga('send', 'event', 'geral', 'internas', 'Reportar Problema');
                        });

                        $('a[href="contato"]').on('click', function() {
                            ga('send', 'event', 'geral', 'internas', 'Contato');
                        });

                    // gráficos internos
                        $('#btn-charts').on('click', function() {
                            ga('send', 'event', 'geral', 'graficos', 'Aba Gráficos');
                        });

                    // parceiros
                        $('.parceiro-skoll').on('click', function() {
                            ga('send', 'event', 'geral', 'parceiros', 'Skoll');
                        });

                        $('.parceiro-tephinet').on('click', function() {
                            ga('send', 'event', 'geral', 'parceiros', 'Tephinet');
                        });

                        $('.parceiro-sus').on('click', function() {
                            ga('send', 'event', 'geral', 'parceiros', 'SUS');
                        });

                    // interna
                        $('#title-graphics').on('click', function() {
                            ga('send', 'event', 'geral', 'graficos', 'Sintomas por Cidade');
                        });

                        $('#title-download').on('click', function() {
                            ga('send', 'event', 'geral', 'graficos', 'Download CSV');
                        });

                        $('#saveMap').on('click', function() {
                            ga('send', 'event', 'geral', 'graficos', 'Salvar Mapa');
                        });
        }
    }

}
