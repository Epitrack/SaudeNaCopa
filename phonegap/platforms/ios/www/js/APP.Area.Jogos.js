var APP = APP || {};
APP.Area = APP.Area || {};
APP.Area.Jogos = {
    setUp: function () {},

    Arenas: {
        setUp: function () {
            var that = this;
            $("#tela_arenas").on(APP._tapEvent,"h1", function () {
                that.tituloClickEventHandler.apply(that, arguments);
            });
        },
        posCarregamento: function() {
            $("#tela_arenas .arena").removeClass('ativa');
        },
        tituloClickEventHandler: function (event) {
            if(!$(event.currentTarget).parents('.arena').hasClass('ativa')) {
                $("#tela_arenas .arena").removeClass('ativa');
            }
            var arenaHTML = $(event.currentTarget).parents('.arena').toggleClass('ativa');

            if(arenaHTML.hasClass('ativa')) {
                APP.analytics.trackEvent('arenas', 'arena', arenaHTML.find("h1").text());
            }
            var arenaDOM = arenaHTML.get(0);
            APP.Area.Rolagem._iScroll.tela_arenas.refresh();
            APP.Area.Rolagem._iScroll.tela_arenas.scrollToElement(arenaDOM, 1000);
        }
    }
};