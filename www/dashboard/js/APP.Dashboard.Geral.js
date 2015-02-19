/**
 * Amber
 * @package principal
 * @author César Martins
 * @version 1.0
 */
var APP = APP || {};

APP.Dashboard = APP.Dashboard || {};

APP.Dashboard.Geral = {
	setUp: function() {
		
	},

	atualizarGraficos: function() {
		this.Grafico1.iniciar();
		this.Grafico2.iniciar();
		this.Grafico3.iniciar();
		this.Grafico4.iniciar();
		this.Grafico5.iniciar();
	},

	Grafico1: {
		_grafico: null,
		_tipo: "line",
		_cotainerId: "graficoGeral1",
		_dadosAss: [],
		//_dadosVig: [],
		//_dadosSur: [],

		setUp: function() {
			this.iniciar();
		},

		iniciar: function() {
			
			$("#"+this._cotainerId).empty();
			this._grafico = new CanvasJS.Chart(this._cotainerId,{
				title: {
					text: "Notificações/Hora - Geral"
				},
				axisX: {
					valueFormatString:"00 h",
					interval: 5
			    },
				axisY2:{
					
					interval: 10,
					interlacedColor: "WhiteSmoke",
					gridColor: "LightGray",      
		 			tickColor: "Silver",								
				},
				data:[
					{
						//type: this._tipo,
						//dataPoints: this._dados
						type: "line",
						lineThickness:3,
						axisYType:"secondary",
						showInLegend: true,           
						name: "Geral", 
						dataPoints: this._dadosAss
					}	
				]
			});
			this._grafico.render();
		}
	},

	Grafico2: {
		_grafico: null,
		_tipo: "column",
		_cotainerId: "graficoGeral2",
		_dados: [],
		
		setUp: function() {
			this.iniciar();
		},

		organizaValores: function(num) {
			var valores = {
				    objs: []
				};
				if(num == 0){
					for(var c= 0; c <= 2; c++){
						valores.objs.push ({
							"y" : parseInt(this._dados[0][c].y),
							"label" : this._dados[0][c].label
						});
					}
				}else{
					for(var c= 0; c <= 2; c++){
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
				axisY2:{
					interval: 20,
					interlacedColor: "WhiteSmoke",
					gridColor: "LightGray",      
		 			tickColor: "Silver",								
				},
				data: [ 
						{
							type: "column",	
							name: "Total",
							legendText: "Total",
							showInLegend: true, 
							dataPoints: this.organizaValores(0)
						},
						{
							type: "column",	
							name: "Porcentagen",
							legendText: "Porcentagen",
							axisYType: "secondary",
							showInLegend: true,
							dataPoints:this.organizaValores(1)
						}	
				
				]
			});
			this._grafico.render();
		}
	},

	Grafico3: {
		_grafico: null,
		_tipo: "line",
		_cotainerId: "graficoGeral3",
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
					text: "Local de Residência"
				},
				toolTip: {
					shared: true
				},
				legend:{
					horizontalAlign: "center",
				},
				axisY2:{
					interval: 20,
					interlacedColor: "WhiteSmoke",
					gridColor: "LightGray",      
		 			tickColor: "Silver",								
				},
				data: [ 
				{
					type: "column",	
					name: "Notificações",
					legendText: "Notificações",
					showInLegend: true, 
					dataPoints: this._dados

				}
				
				]
			});
			this._grafico.render();
		}
	},

	Grafico4: {
		_grafico: null,
		_tipo: "bar",
		_cotainerId: "graficoGeral4",
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
		
		_grafico: null,
		_tipo: "line",
		_cotainerId: "graficoGeral5",
		_dadosAss: [],
		_dadosVig: [],
		_dadosSur: [],
		
		setUp: function() {
			
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
			return valores.objs; 
		},
		iniciar: function() {
			
			$("#"+this._cotainerId).empty();
			this._grafico = new CanvasJS.Chart(this._cotainerId,{
				title: {
					text: "Notificações/Hora - Geral"
				},
				axisX: {
					valueFormatString:"00 h",
					interval: 5
			    },
				axisY2:{
					
					interval: 10,
					interlacedColor: "WhiteSmoke",
					gridColor: "LightGray",      
		 			tickColor: "Silver",								
				},
				data:[
					{
						type: "line",
						lineThickness:3,
						axisYType:"secondary",
						showInLegend: true,           
						name: "Assistencia", 
						dataPoints: this._dadosAss
					},
					{        
						type: "line",
						lineThickness:3,
						showInLegend: true,           
						name: "Vigilancia",
						axisYType:"secondary",
						dataPoints: this._dadosVig
					},
					{        
						type: "line",
						lineThickness:3,
						showInLegend: true,           
						name: "Surto",        
						axisYType:"secondary",
						dataPoints: this._dadosSur
					}		
				]
			});
			this._grafico.render();
		}
	}
}
