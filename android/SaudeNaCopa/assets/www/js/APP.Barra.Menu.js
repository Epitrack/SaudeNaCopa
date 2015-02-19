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
			$("#barraDeMenu").on('vclick', function(event) {
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
				$("a[href='#sair']", document.body).on('vclick', function(event) {
					that.sairEventHandler.apply(that, arguments);
				});
			},
			sairEventHandler: function(event) {


				event.preventDefault();
				
				APP.GerenciadorDeTelas.exibir("#tela_acesso");

				/*FB.logout(function(response) {
			       APP.GerenciadorDeTelas.exibir("#tela_acesso");
			    });*/
			}
		}
	}

}