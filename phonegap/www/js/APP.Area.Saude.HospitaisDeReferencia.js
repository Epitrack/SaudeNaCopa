var APP = APP || {};
APP.Area = APP.Area || {};
APP.Area.Saude = APP.Area.Saude || {};
APP.Area.Saude.HospitaisDeReferencia = 	{
	setUp: function() {},
	carregar: function() {
		this.pai().Locais.Geolocalizacao.capturar("hospitaisDeReferencia");
	},
	posCarregamento: function() {
		this.carregar();
	}
}