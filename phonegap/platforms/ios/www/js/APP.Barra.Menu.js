var APP = APP || {};
APP.Barra = APP.Barra || {};
APP.Barra.Menu = {

	setUp: function() {

	},
	Menu: {
		setUp: function() {
			this.menuEscutar();	
		},

		menuEscutar: function() {
			var that = this;
			$("#barraDeMenu").on(APP._tapEvent, function(event) {
				that.menuEventHandler.apply(that, arguments);
			})
		},

		menuEventHandler: function (event){
			if(event.target.id == "menuDoAplicativo") {
				$("#menuDoAplicativo ul").toggle();
			} 
		},

		Sair: {
			setUp: function() {
				this.sairEscutar();
			},
			sairEscutar: function() {
				var that = this;
				$(document.body).on(APP._tapEvent,"a[href='#sair']", function(event) {
					that.sairEventHandler.apply(that, arguments);
				});
			},
			sairEventHandler: function(event) {
				event.preventDefault();
				APP.Area.Acesso.logout();
			}
		}
	}

}