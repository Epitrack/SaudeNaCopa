var APP = APP || {};
APP.Area = APP.Area || {};
APP.Area.Mais = {
    setUp: function () {},

    Mais: {
        setUp: function () {
            $("#tela_mais, #tela_linksUteis").on(APP._tapEvent,"a[href^='http://']", function(event) {
                APP.analytics.trackEvent('link útil', $(event.target).text(), href);
                var href = event.target.getAttribute('href');
                if(window.isphone) {
                    event.preventDefault();
                    window.open(href, '_system');
                } else if(head.mobile && head.browser.ios) {
                    event.preventDefault();
                    window.location.href = href;
                }
            });
        },
        posCarregamento: function () {
            if (APP.Offline._onLine) this.pai().Twitter.enviar();
        }
    },

    Categorias: {
        setUp: function () {

        },
        posCarregamento: function () {
            var dados, categoria, i18nId;
            dados = JSON.parse(localStorage.getItem(APP.Area.Sentimento.Torcedor._idLocalStorage));
            categoria = APP.Area.Sentimento.Torcedor._categorias[dados.categoria];
            i18nId = "sentimento.torcedor.categorias." + categoria;


            $("#componente_categoriaDoUsuario").attr('data-categoria', categoria);
        }
    },

    Twitter: {
        _iScroll: null,
        setUp: function () {
            this._iScroll = new IScroll("#twitter_minSaude .scroller", {
                scrollbars: true
            });
            this.gerarDadosPrettyDate();
        },

        enviar: function (form) {
            var that = this;
            $.ajax({
                url: "http://saudenacopa.epitrack.com.br/proxyTwitter/index.php",
                dataType: "JSON",

                beforeSend: function () {
                    that.enviando.apply(that, arguments);
                },
                success: function () {
                    that.enviou.apply(that, arguments);
                },
                error: function () {
                    that.naoEnviou.apply(that, arguments);
                }
            });
        },

        _dadosPD: {},

        gerarDadosPrettyDate: function () {
            this._dadosPD.agora = i18n.t("mais.mais.twitter.agora");
            this._dadosPD._1minuto = i18n.t("mais.mais.twitter.1minuto");
            this._dadosPD.minutos = i18n.t("mais.mais.twitter.minutos");
            this._dadosPD._1hora = i18n.t("mais.mais.twitter.1hora");
            this._dadosPD.horas = i18n.t("mais.mais.twitter.horas");
            this._dadosPD.ontem = i18n.t("mais.mais.twitter.ontem");
            this._dadosPD.dias = i18n.t("mais.mais.twitter.dias");
            this._dadosPD.semanas = i18n.t("mais.mais.twitter.semanas");
        },

        prettyDate: function (time) {
            var date = new Date((time || "").replace(/-/g, "/").replace(/[TZ]/g, " ")),
                diff = (((new Date()).getTime() - date.getTime()) / 1000),
                day_diff = Math.floor(diff / 86400);

            if (isNaN(day_diff) || day_diff < 0 || day_diff >= 31)
                return;



            return day_diff === 0 && (
                diff < 60 && this._dadosPD.agora ||
                diff < 120 && this._dadosPD._1minuto ||
                diff < 3600 && Math.floor(diff / 60) + " " + this._dadosPD.minutos ||
                diff < 7200 && this._dadosPD._1hora ||
                diff < 86400 && Math.floor(diff / 3600) + " " + this._dadosPD.horas) ||
                day_diff == 1 && this._dadosPD.ontem ||
                day_diff < 7 && day_diff + " " + this._dadosPD.dias ||
                day_diff < 31 && Math.ceil(day_diff / 7) + " " + this._dadosPD.semanas;
        },



        preencher: function (dados) {
            var that = this;
            var fragmento = document.createDocumentFragment();
            $(dados).each(function (iDado, dado) {
                var twit = $("<article>")
                    .addClass('twit');

                var h1 = $("<h1>")
                    .text(dado.user.name + " ")
                    .appendTo(twit);

                var tempo = $("<span>")
                    .addClass('tempo')
                    .text("@" + dado.user.screen_name + " • " + that.prettyDate(dado.created_at))
                    .appendTo(h1);

                $("<p>").text(dado.text).appendTo(twit);

                fragmento.appendChild(twit.get(0));
            });

            $("#twits").empty().get(0).appendChild(fragmento);
            this._iScroll.refresh();
        },


        enviando: function () {
            this.adicionarCarregando();
        },

        adicionarCarregando: function () {
            $("#twitter_minSaude")
                .parent('.tela').first()
                .addClass('carregando');
        },

        removerCarregando: function () {
            $("#twitter_minSaude")
                .parent('.tela').first()
                .removeClass('carregando');
        },

        enviou: function (data) {
            if (data.length && data.length > 0) {
                this.preencher(data);
            } else {
                if (data.mensagem) {
                    this.naoEnviou(data.mensagem);
                } else {
                    this.naoEnviou();
                }
            }
            this.removerCarregando();
        },

        naoEnviou: function (feedback) {
            var mensagem = "Erro.";

            if (feedback) {
                mensagem += "\n " + feedback;
            }

            //alert(mensagem);

            this.removerCarregando();
        }
    }
};
