var APP = APP || {};
APP.Area = {
	setUp: function() {
		
	},
	Rolagem: {
		_iScroll: {},
		setUp: function() {
			var that = this;
			$(".tela.comRolagem").each(function(i,tela) {
				var idTela = "#"+tela.id;
				that._iScroll[tela.id] = new IScroll(idTela, {
					mouseWheel: true
				});
			})
		}
	}
};