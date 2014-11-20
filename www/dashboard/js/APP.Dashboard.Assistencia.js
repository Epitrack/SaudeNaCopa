/**
 * Amber
 * @package principal
 * @author César Martins
 * @version 1.0
 */
var APP = APP || {};

APP.Dashboard = APP.Dashboard || {};


APP.Dashboard.Assistencia = {
	setUp: function() {
	},

	atualizarGraficos: function() {
		this.Grafico1.iniciar();
		this.Grafico2.iniciar();
		this.Grafico3.iniciar();
		this.Grafico4.iniciar();
	},

	Grafico1: {
		_grafico: null,
		_tipo: "line",
		_cotainerId: "graficoAssistencia1",
		_dados: [],

		setUp: function() {
			this.iniciar();
		},

		iniciar: function() {
			$("#"+this._cotainerId).empty();
			this._grafico = new CanvasJS.Chart(this._cotainerId,{
				title: {
					text: "Notificações/Hora"
				},
				data:[{
					type: this._tipo,
					dataPoints: this._dados
				}]
			});
			this._grafico.render();
		}
	},

	Grafico2: {
		_grafico: null,
		_tipo: "pie",
		_cotainerId: "graficoAssistencia2",
		_dados: [],

		setUp: function() {
			this.iniciar();
		},
		organizaValores: function() {
			
			var valores = {
				    objs: []
				};
			if(this._dados.length > 0){
				
				for(var c= 0; c <= this._dados.length-1; c++) {
					if(this._dados[c].y != ""){
						valores.objs.push ({
							"y" : parseInt(this._dados[c].y),
							"x" : parseInt(this._dados[c].x),
							"legendText" : this._dados[c].legendText.toString()
						});
					}
				}
			}
			//console.debug(valores.objs,"organizaValores");
			return valores.objs; 
		},
		iniciar: function() {
			//console.debug(this._dados);
			$("#"+this._cotainerId).empty();
			this._grafico = new CanvasJS.Chart(this._cotainerId,{
				title: {
					text: "Encaminhamento"
				},
				legend:{
			        verticalAlign: "center",
			        horizontalAlign: "left",
			        fontSize: 20,
			        fontFamily: "Helvetica",        
			      },
				animationEnabled: false,
				
				data:[{
					showInLegend: true,
					type: this._tipo,
					dataPoints: this.organizaValores(),
					//dataPoints: [{y:this._dados[0].y, x:1, legendText:'Alta'}, {y:this._dados[1].y, x:2, legendText:'Óbito'}, {y:this._dados[2].y, x:3, legendText:'Transferência'}]
				}]
			});
			this._grafico.render();
		}
	},

	Grafico3: {
		_grafico: null,
		_tipo: "line",
		_cotainerId: "graficoAssistencia3",
		_dados: [],

		setUp: function() {
			this.iniciar();
		},

		iniciar: function() {
			$("#"+this._cotainerId).empty();
			this._grafico = new CanvasJS.Chart(this._cotainerId,{
				title: {
					text: "Idade"
				},
				data:[{
					type: this._tipo,
					dataPoints: this._dados
				}]
			});
			this._grafico.render();
		}
	},

	Grafico4: {
		_grafico: null,
		_tipo: "bar",
		_cotainerId: "graficoAssistencia4",
		_dados: [],

		setUp: function() {
			this.iniciar();
		},

		iniciar: function() {
		
			$("#"+this._cotainerId).empty();
			
			locaisBanco = new Array();
			for(i=0; i < this._dados.length; i++){
				locaisBanco.push('' + this._dados[i].label + '');
            }
			this._grafico = new CanvasJS.Chart(this._cotainerId,{
				title: {
					text: "Ponto de Monitoramento",
					fontSize: 20
				},
				axisX:{
					interval: 1,
					gridThickness: 0,
					labelFontSize: 10,
					labelFontStyle: "normal",
					labelFontWeight: "normal",
					labelFontFamily: "Lucida Sans Unicode",

				},
				axisY2:{
					interlacedColor: "rgba(1,77,101,.2)",
					gridColor: "rgba(1,77,101,.1)",

				},
				data:[{
					type: this._tipo,
					name: "companies",
					axisYType: "secondary",
					color: "#014D65",	
					dataPoints: this._dados
				}]
			});
			this._grafico.render();
		}
	},

	Grafico5: {
		setUp: function() {
			
		}
	}
}
