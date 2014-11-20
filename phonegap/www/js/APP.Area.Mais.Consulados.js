var APP = APP || {};
APP.Area = APP.Area || {};
APP.Area.Mais = APP.Area.Mais || {};
APP.Area.Mais.Consulados = {
	setUp: function() {
		var that = this;
		var res = $("#tela_consulados").on(APP._tapEvent, function(event){
			that.paisClickEventHandler.apply(that, arguments);
		});
	},

	posCarregamento: function() {
		this.preencherPaises();
	},

	paisClickEventHandler: function(event) {
		var pais = event.target.parentNode.getAttribute('data-pais');
		this.preencherConsulado(pais);
		APP.GerenciadorDeTelas.exibir("#tela_consulado");
		APP.Area.Rolagem._iScroll.tela_consulado.refresh();
		APP.analytics.trackEvent('consulado', 'pais', pais);
	},

	preencherPaises: function() {
		var pais, i18nPais, arrayPais;
		var lista = $('#lista_paises').empty().get(0);
		var fragmento = document.createDocumentFragment();

		var listaPaises = [];

		for(pais in this._enderecos) {
			if(!this._enderecos.hasOwnProperty(pais) || pais == "pai" || pais == "_nameSpace") { continue; }
			i18nPais = i18n.t("mais.consulados.paises."+pais);

			listaPaises.push([pais,i18nPais]);
		}

		listaPaises.sort(function(a,b) {
			var valorA = a[1].replace("Á", "A").replace("Í", "I");
			var valorB = b[1].replace("Á", "A").replace("Í", "I");
		  if (valorA < valorB)
		     return -1;
		  if (valorA > valorB)
		    return 1;
		  return 0;
		});

		for(var iPais = 0; iPais < listaPaises.length; iPais++) {
			arrayPais = listaPaises[iPais];
			pais = arrayPais[0];
			i18nPais = arrayPais[1];

			var li = document.createElement('li');
				li.setAttribute("data-pais",pais);

			var span = document.createElement('span');
				span.classList.add('celula');
				span.textContent = i18nPais;

			li.appendChild(span);
			fragmento.appendChild(li);
		}


		// for(pais in this._enderecos) {
		// 	if(!this._enderecos.hasOwnProperty(pais) || pais == "pai" || pais == "_nameSpace") { continue; }
			
		// 	var li = document.createElement('li');
		// 		li.setAttribute("data-pais",pais);

		// 	var span = document.createElement('span');
		// 		span.classList.add('celula');
		// 		span.textContent = i18n.t("mais.consulados.paises."+pais);

		// 	li.appendChild(span);
		// 	fragmento.appendChild(li);
		// }
		lista.appendChild(fragmento);
		APP.Area.Rolagem._iScroll.tela_consulados.refresh();
	},

	preencherConsulado: function(pais) {
		var fragmento = document.createDocumentFragment();
		var h1 = document.createElement('h1');
			h1.textContent = i18n.t("mais.consulados.paises."+pais);

		fragmento.appendChild(h1)

		var cidades = this._enderecos[pais];

		for(cidade in cidades) {
			var article = document.createElement('article');
				article.classList.add('cidade');

			var h2 = document.createElement('h2');
				h2.textContent = cidade;

			article.appendChild(h2);

			var p = document.createElement('p');
				p.innerHTML = cidades[cidade].replace(/\n/g,"<br />");

			article.appendChild(p);

			fragmento.appendChild(article);
		}

		$("#conteudo_consulado").empty().get(0).appendChild(fragmento);
	}
}