/**
 * Amber
 * @package principal
 * @author César Martins
 * @version 1.0
 */
var APP = APP || {};

APP.Dashboard = APP.Dashboard || {};

APP.Dashboard.Vigilancia = {
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
		_cotainerId: "graficoVigilancia1",
		_dados: [],

		setUp: function() {
			this.iniciar();
		},

		iniciar: function() {
			$("#"+this._cotainerId).empty();
			this._grafico = new CanvasJS.Chart(this._cotainerId,{
				title: {
					text: "Notificações/Hora - Geral"
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
		_tipo: "line",
		_cotainerId: "graficoVigilancia2",
		_dados: [],

		setUp: function() {
			this.iniciar();
		},

		organizaValores: function(num) {
			var valores = {
				    objs: []
				};
			
				if(num == 0){
					
					for(var c= 0; c <= this._dados[0][0].total; c++){
			
						valores.objs.push ({
							"y" : parseInt(this._dados[0][c].y),
							"label" : this._dados[0][c].label
						});
					}
					
				}else{
				
					for(var c= 0; c <= this._dados[0][0].total; c++){
						valores.objs.push ({
							"y" : parseInt(this._dados[1][c].y),
							"label" : this._dados[1][c].label
						});
					}
				}
			//console.debug(valores.objs[num]);
			return valores.objs; 
		},
		
		iniciar: function() {
			
			//console.debug(this._dados[0][0], this._dados[1][0]);
			$("#"+this._cotainerId).empty();
			this._grafico = new CanvasJS.Chart(this._cotainerId,{
				theme: "theme3",
				title:{
					text: "Medidas Aplicadas"
				},
				toolTip: {
					shared: true
				},
				legend:{
					horizontalAlign: "center",
				},
				data: [ 
				{
					type: "column",	
					name: "Notificações",
					legendText: "Notificações",
					showInLegend: true, 
					dataPoints: this.organizaValores(0)

				},
				{
					type: "column",	
					name: "Percentual",
					legendText: "Percentual",
					axisYType: "secondary",
					showInLegend: true,
					dataPoints:this.organizaValores(1)

				},
				
				]
			});
			this._grafico.render();
		}
	},

	Grafico3: {
		_grafico: null,
		_tipo: "line",
		_cotainerId: "graficoVigilancia3",
		_dados: [],

		setUp: function() {
			this.iniciar();
		},

		organizaValores: function(num) {
			var valores = {
				    objs: []
				};
			
				if(num == 0){
					
					for(var c= 0; c <= this._dados[0][0].total; c++){
						valores.objs.push ({
							"y" : parseInt(this._dados[0][c].y),
							"label" : this._dados[0][c].label
						});
					}
					
				}else{
					
					for(var c= 0; c <= this._dados[0][0].total; c++){
						valores.objs.push ({
							"y" : parseInt(this._dados[1][c].y),
							"label" : this._dados[1][c].label
						});
					}
				}
			
			//console.debug(valores.objs[num]);
			return valores.objs; 
		},
		
		iniciar: function() {
			
			//console.debug(this._dados[0][0], this._dados[1][0]);
			$("#"+this._cotainerId).empty();
			this._grafico = new CanvasJS.Chart(this._cotainerId,{
				theme: "theme3",
				title:{
					text: "Produtos"
				},
				toolTip: {
					shared: true
				},
				legend:{
					horizontalAlign: "center",
				},
				data: [ 
				{
					type: "column",	
					name: "Notificações",
					legendText: "Notificações",
					showInLegend: true, 
					dataPoints: this.organizaValores(0)

				},
				{
					type: "column",	
					name: "Percentual",
					legendText: "Percentual",
					axisYType: "secondary",
					showInLegend: true,
					dataPoints:this.organizaValores(1)

				},
				
				]
			});
			this._grafico.render();
		}
	},

	Grafico4: {
		_grafico: null,
		_tipo: "bar",
		_cotainerId: "graficoVigilancia4",
		_dados: [],

		setUp: function() {
			this.iniciar();
		},

		organizaValores: function(num) {
			var valores = {
				    objs: []
				};
			
				if(num == 0){
					
					for(var c= 0; c <= this._dados[0][0].total; c++){

						valores.objs.push ({
							"y" : parseInt(this._dados[0][c].y),
							"label" : this._dados[0][c].label
						});
					}
				
				}else{
					
					for(var c= 0; c <= this._dados[0][0].total; c++){

						valores.objs.push ({
							"y" : parseInt(this._dados[1][c].y),
							"label" : this._dados[1][c].label,
							"name": [this._dados[1][c].y + "%"]
						});
					}
					
				}
			//console.debug(valores.objs);	
			return valores.objs; 
		},
		
		iniciar: function() {
		
			$("#"+this._cotainerId).empty();
			
			this.organizaValores(0);
			
			this._grafico = new CanvasJS.Chart(this._cotainerId,{
				title: {
					text: "Infração",
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
				data: [
				       {        
				         type: "bar",
				         showInLegend: true,
				         name: "Quantidade",
				         color: "#007199",
				         dataPoints: this.organizaValores(0)
				       },
				       {        
				           type: "bar",
				           showInLegend: true,
				           name: "Porcentagem",
				           color: "#508099",
				           dataPoints: this.organizaValores(1)
				         },
				       ]
				     });

			this._grafico.render();
		}
	},

	Grafico5: {
		setUp: function() {
			
		}
	}
}
