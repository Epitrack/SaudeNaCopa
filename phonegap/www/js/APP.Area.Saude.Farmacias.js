var APP = APP || {};
APP.Area = APP.Area || {};
APP.Area.Saude = APP.Area.Saude || {};
APP.Area.Saude.Farmacias = 	{
	setUp: function() {},
	carregar: function() {
		this.pai().Locais.Geolocalizacao.capturar("farmacias");
	},
	posCarregamento: function() {
		this.carregar();
	}
}