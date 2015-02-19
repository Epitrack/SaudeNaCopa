/**
 * Amber
 * @package principal
 * @author César Martins
 * @version 1.0
 */
var APP = APP || {};
APP.Dashboard = {
	setUp: function() {

		
	},

	parseIntStrings: function(obj) {
		var newObject = {}, v;
		for(v in obj) {
			if(obj.hasOwnProperty(v) && typeof obj[v] == "string") {
				newObject[v] = parseInt(obj[v]);
			} else {
				newObject[v] = obj[v];
			}
		}
		return newObject;
	},


	parseIntObjectStrings: function(arr) {
		var resultado;

		resultado = arr.map(function(e,i) {
			return APP.Dashboard.parseIntStrings(e);
		});

		return resultado;
	},

	gerarDadosGraficos: function (){
		
		var that = this;
		that.objetoAjax = $.ajax({
			url: 'bibliotecas/php/phplot/graficos.php',
			dataType: 'JSON',
			beforeSend: function() {
				//that.carregando.apply(that,arguments);
				console.debug("antes");
			},
			success: function() {
				console.debug("success");
				
				//parar a animacao inicial dos graficos
				var options = {animation:false}
				
				//dados para o grafico de linha
				var lineChartData = {
						labels : ["9:00-10:00","10:00-11:00","11:00-12:00","12:00-13:00","13:00-14:00"],
						datasets : [
							{
								fillColor : "rgba(220,220,220,0.5)",
								strokeColor : "rgba(220,220,220,1)", 
								pointColor : "rgba(220,220,220,1)",
								pointStrokeColor : "#fff",
								data : [10,59,90,81,50]
							}
						]
						
					}
				
				new Chart(document.getElementById("line").getContext("2d")).Line(lineChartData,options);
				
				//dados grafico de pizza 
				var pieData = [
								{
									value: 30,
									color:"#F38630"
								},
								{
									value : 50,
									color : "#E0E4CC"
								},
								{
									value : 100,
									color : "#69D2E7"
								}
							
							];
				new Chart(document.getElementById("pie").getContext("2d")).Pie(pieData,options);
				
				//dados graficos de barra idade
				var barChartData = {
						labels : ["Maior","Media","Mediana","Moda","Menor"],
						datasets : [
							
							{
								fillColor : "rgba(151,187,205,0.5)",
								data : [28,48,40,19,10]
							}
						]
						
					};
				new Chart(document.getElementById("bar").getContext("2d")).Bar(barChartData,options);
				
				//dados graficos de barra cadastrados
				var barChartData = {
						labels : ["maior","media","mediana","moda","menor"],
						datasets : [
							
							{
								fillColor : "rgba(151,187,205,0.5)",
								data : [28,48,40,19,10]
							}
						]
						
					};
				new Chart(document.getElementById("bar").getContext("2d")).Bar(barChartData,options);
				
			},
			error: function() {
				console.debug("error");
				//that.naoCarregou.apply(that,arguments);
			}
		});
		
	},

	atualizarGraficos: function() {
		this.Assistencia.atualizarGraficos();
		this.Vigilancia.atualizarGraficos();
		this.Geral.atualizarGraficos();
		//$(".pagina .grafico > div").remove();
	},
	
	atualizar: function(dados) {
		$(['geralAssistencia', 'geralVigilancia'])
			.each(function(iId, id){

				var total = 0;
				$(iId == 0 ? dados.assistencia : dados.vigilancia).each(function(iDado, dado) {
					var id = ("#total_x_"+dado.i).replace("x", iId == 0 ? 'assistencia' : 'vigilancia');

					$(id).find('span').text(dado.t);
					total += parseInt(dado.t);
				});
				$("#"+id+" .page-header small").text(total);
				//APP.Dashboard.gerarDadosGraficos();
			});
		
		$(['geralSurto'])
		.each(function(iId, id){

			var total = 0;
			$(dados.surtos).each(function(iDado, dado) {
				var id = ("#total_x_"+dado.i).replace("x",'surtos');

				$(id).find('span').text(dado.t);
				total += parseInt(dado.t);
			});
			$("#"+id+" .page-header small").text(total);
			
		});

		var Assistencia = APP.Dashboard.Assistencia;
			Assistencia.Grafico1._dados = APP.Dashboard.parseIntObjectStrings(dados.graficoAssistencia1);
			Assistencia.Grafico2._dados = APP.Dashboard.parseIntObjectStrings(dados.graficoAssistencia2);
			Assistencia.Grafico3._dados = APP.Dashboard.parseIntObjectStrings(dados.graficoAssistencia3);
			Assistencia.Grafico4._dados = APP.Dashboard.parseIntObjectStrings(dados.graficoAssistencia4);

		var Vigilancia = APP.Dashboard.Vigilancia;
			Vigilancia.Grafico1._dados = APP.Dashboard.parseIntObjectStrings(dados.graficoVigilancia1);
			Vigilancia.Grafico2._dados = APP.Dashboard.parseIntObjectStrings(dados.graficoVigilancia2);
			Vigilancia.Grafico3._dados = APP.Dashboard.parseIntObjectStrings(dados.graficoVigilancia3);
			Vigilancia.Grafico4._dados = APP.Dashboard.parseIntObjectStrings(dados.graficoVigilancia4);
			
			
		var Geral = APP.Dashboard.Geral;
			Geral.Grafico1._dadosAss = APP.Dashboard.parseIntObjectStrings(dados.dados_grafico_1_geral);
			//Geral.Grafico1._dadosVig = APP.Dashboard.parseIntObjectStrings(dados.graficoVigilancia1);
			//Geral.Grafico1._dadosSur = APP.Dashboard.parseIntObjectStrings(dados.totalHoraSurtos);
			
			Geral.Grafico2._dados = APP.Dashboard.parseIntObjectStrings(dados.dados_grafico_2_geral);
			Geral.Grafico3._dados = APP.Dashboard.parseIntObjectStrings(dados.dados_grafico_3_geral);
			
			Geral.Grafico5._dadosAss = APP.Dashboard.parseIntObjectStrings(dados.graficoAssistencia1);
			Geral.Grafico5._dadosVig = APP.Dashboard.parseIntObjectStrings(dados.graficoVigilancia1);
			Geral.Grafico5._dadosSur = APP.Dashboard.parseIntObjectStrings(dados.totalHoraSurtos);
			
			Geral.Grafico4._dados = APP.Dashboard.parseIntObjectStrings(dados.graficoAssistencia4);	
		
		this.atualizarGraficos();

		//ABA SURTO
		$(['abaGeralSurto'])
		.each(function(iId, id){

			//totalFonteContaminacao
			var total = 0;
			$(dados.totalFonteContaminacao).each(function(iDado, dado) {
				var id = ("#total_x_"+dado.i).replace("x",'aba_surtos_fc');
				$(id).find('span').text(dado.t);
				total += parseInt(dado.t);
			});
			//$("#"+id+" .page-header small").text(total);
			$("#total_aba_surtos_fc .page-header small").text(total);
			
			//totalLocalExposicao
			var total = 0;
			$(dados.totalLocalExposicao).each(function(iDado, dado) {
				var id = ("#total_x_"+dado.i).replace("x",'aba_surtos_le');
				$(id).find('span').text(dado.t);
				total += parseInt(dado.t);
			});
			$("#total_aba_surtos_le .page-header small").text(total);
			
			//totalPessoasEnvolvidos
			var total = 0;
			$(dados.totalPessoasEnvolvidos).each(function(iDado, dado) {
				var id = ("#total_x_"+dado.i).replace("x",'aba_surtos_pe');
				$(id).find('span').text(dado.t);
				total += parseInt(dado.t);
			});
			$("#total_aba_surtos_pe .page-header small").text(total);
			
		});
		

	}, 



	Paginas:  {
		setUp: function() {
			$(".menu-paginas [data-pagina]").on("click", function(event) {

				var botaoClicado = $(event.currentTarget);

					botaoClicado
					.addClass('active')
					.siblings().removeClass('active')
					.parents('.menu-paginas').parent()
					.find('.pagina').removeClass('ativa')
					.filter('[data-pagina='+botaoClicado.attr('data-pagina')+']').addClass('ativa');


			});
		}
	},
	
	Detalhamento: {
		_ultimoUrl: "",
		setUp: function() {
			var that = this;
			$("div[data-tipo=assistencia], div[data-tipo=vigilancia], div[data-tipo=surtos]").on('click', function(event) {
				var id = $(event.currentTarget).attr('data-id');
				var tipo = $(event.currentTarget).attr('data-tipo');

				var url = "detalhamento" + (tipo.replace(/^[a-z]/, function(m){ return m.toUpperCase() })) +  '.php?hide=true&id='+id;
				that._ultimoUrl = url;

				$("#detalhamento")
					.find('.modal-body')
					.text("Carregando...")
					.load(url)
					.end()
					.modal('show');
			});

			$(".btn.fecharModal").on("click", function(){
				$("#detalhamento").modal("hide");
			});

			$(".btn.ampliar").on("click", function(){
				window.location.href = that._ultimoUrl+"&async=false";
			});
			
			$('#datetimepicker2').datetimepicker({
				      language: 'pt-BR'
			});
			
			$('#datetimepicker3').datetimepicker({
			      language: 'pt-BR'
			});
			
			$('#datetimepicker4').datetimepicker({
			      language: 'pt-BR'
			});
			$('#datetimepicker5').datetimepicker({
			      language: 'pt-BR'
			});
			
		},
	},

	Consulta: {
		setUp: function() {
			this.Requisicao.iniciar();
		},

		Requisicao: {
			setUp: function() {},

			objetoAjax: null,

			timer: null,

			periodico: true,

			intervalo: 5,

			parar: function() {
				this.periodico = false;
				
				if(this.objetoAjax) 
					this.objetoAjax.abort();
				
				if(this.timer)
					clearInterval(this.timer);
			},

			iniciar: function() {
				this.parar();
				this.periodico = true;
				this.carregar();
			},

			carregarSePeriodico: function() {
				var that = this;
				if(this.periodico) {
					this.timer = setTimeout(function() {
						that.carregar();
					}, this.intervalo*1000);
				}
				/*$("#barraDeCarregamento")
					.animate({width: 0}, this.intervalo*1000, function() {
						$("#barraDeCarregamento").animate({width: "100%"}, 1000)
					}); */
			},

			carregar: function() {
				var that = this;
				that.objetoAjax = $.ajax({
					url: 'dados_dashboard.php',
					dataType: 'JSON',
					beforeSend: function() {
						that.carregando.apply(that,arguments);
					},
					success: function() {
						that.carregou.apply(that,arguments);
					},
					error: function() {
						that.naoCarregou.apply(that,arguments);
					}
				});
			},
			carregando: function() {
				$("#secao-dashboard").addClass('atualizando');
			},
			carregou: function(data, text, xhr) {
				
				APP.Dashboard.atualizar(data);
				
				/*if(data.assistencia) {
					APP.Dashboard.Assistencia.atualizar(data.assistencia);
				}
				*/

				this.carregarSePeriodico();
				$("#secao-dashboard").removeClass('atualizando');
				//$("#barraDeCarregamento").css({width: "100%"})
			},
			naoCarregou: function() {
				this.carregarSePeriodico();
				$("#secao-dashboard").removeClass('atualizando');
				//$("#barraDeCarregamento").css({width: "100%"})
			}
		}
	}
}