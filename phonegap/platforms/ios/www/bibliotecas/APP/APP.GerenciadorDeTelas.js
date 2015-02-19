var APP = APP || {};
APP.GerenciadorDeTelas = {

	//Id do elemento que contém todo o app
	_contexto: "#app",

	/**
	 * nomes usados como classes e prefixos de elementos
	 * section.area#area_home>div.tela#tela_home
	 */
	_area: "area", _tela: "tela",

	//Classe que indica em exibição
	_ativa: "ativa",

	//Classe que indica que o elemento está posicionado antes
	_antes: "antes",

	//Classe que indica que o elemento está posicionado depois
	_depois: "depois",

	_idAreaAnterior: "",	
	_idTelaAnterior: "",	
	_idAreaAtual: "",
	_idTelaAtual: "",
	_idTelaInicial: "",

	setUp: function() {
		this.linksEscutar();
		//this.exibirTelaInicial();

		if(this.setUpCallback !== null && typeof this.setUpCallback == 'function') {
			this.setUpCallback.call(this);
		}
	},

	setUpCallback: null,

	Historico: {
		_pagina: 0,
		_dados: [],
		setUp: function() {

		},

		voltar: function() {
			if(this._dados.length>1) {
				this._dados.pop();
				this.pai().exibir("#"+this._dados.pop());
			}
		},

		adicionar: function(idTela) {
			if(this._dados[this._dados.length-1] != idTela) {
				this._dados.push(idTela);
				this._pagina++;
			}
		}
	},

	linksEscutar: function() {
		var that = this, 
			query = "a[href^='#tela'], a[href='#voltar']".replace('tela', this._tela);

		$(document.body)
			.on(APP._tapEvent, query, function(event) {
				that.linksTratarEventos.apply(that, arguments);
			});

	},

	linksTratarEventos: function(event) {


		event.preventDefault();

		var href, expressaoRegular;
			
			href = event.currentTarget.getAttribute("href");
			expressaoRegular = new RegExp("^\#"+this._tela);

		if(href == '#voltar') {
			this.Historico.voltar();
		} else if(href.match(expressaoRegular)) {
			this.exibir(href);	
		} 
	},

	voltar: function() {
		this.exibir("#"+this._idTelaAnterior)
	},


	exibirTelaInicial: function() {
		if(!this._idTelaInicial) 
			this._idTelaInicial = document.querySelector("."+this._tela).id;

		this.exibir("#"+this._idTelaInicial);
	},

	exibirCallback: null,

	exibir: function(idTela) {

		var area, tela;

			tela = document.querySelector(idTela);
			area = tela.parentNode;

		this.redefinirClasses(area, tela);

		var idTelaAtual = this._idTelaAtual;
		var idAreaAtual = this._idAreaAtual;

		this._idAreaAnterior = idAreaAtual;
		this._idTelaAnterior = idTelaAtual;

		this._idTelaAtual = tela.id;
		this._idAreaAtual = area.id;

		this.Historico.adicionar(this._idTelaAtual);

		if(this.exibirCallback !== null && typeof this.exibirCallback == 'function') {
			this.exibirCallback.call(this, tela);
		}
	},

	/*
	
		Escreve a classe "antes" nas telas anteriores a em exibição, 
		bem como "depois" nas telas posteriores a em exibição. 

	 */
	redefinirClasses: function(area, tela) {

		var classesParaRemover, queryGrupoParte;

			 queryGrupoParte= ".area, .tela"
						.replace('area', this._area)
						.replace('tela', this._tela);

			classesParaRemover = [this._ativa,this._antes,this._depois].join(" ");
		
		$(queryGrupoParte).removeClass(classesParaRemover);

		$(area)
			.addClass(this._ativa)
			.prevAll('.'+this._area).addClass(this._antes).end()
			.nextAll('.'+this._tela).addClass(this._depois).end();

		$(tela).addClass(this._ativa);
	}
}