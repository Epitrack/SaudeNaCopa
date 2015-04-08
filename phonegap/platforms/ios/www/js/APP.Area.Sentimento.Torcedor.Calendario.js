var APP = APP || {};
APP.Area = APP.Area || {};
APP.Area.Sentimento = APP.Area.Sentimento || {};
APP.Area.Sentimento.Torcedor = APP.Area.Sentimento.Torcedor || {};
APP.Area.Sentimento.Torcedor.Calendario = {
    _cores: ["#e8cc28", "#d78a30", "#d78a30", "#c21e37", "#8c1a20"],
    _dias: "domingo segunda terca quarta quinta sexta sabado".split(" "),
    _meses: "janeiro fevereiro marco abril maio junho julho agosto setembro outubro novembro dezembro".split(" "),

    setUp: function() {
        var that = this;
        $("#tela_calendario").on(APP._tapEvent,"h1 .icone", function(){
            that.navegacaoEventHandler.apply(that, arguments);
        })
    },

    navegacaoEventHandler: function(event) {
        var posicao = $(event.currentTarget).hasClass('anterior') ? "anterior" : "proximo";

        if(posicao == "anterior") {
            this.anterior();
        } else {
            this.proximo();
        }
    },

    anterior: function() {
        var calendarios = $("#calendarios .mes");
        if(calendarios.length > 1){
            var anterior = $("#calendarios .mes.ativo").prev();

            if(anterior.length > 0) {
                calendarios.removeClass("ativo");
                anterior.addClass('ativo');
                $("#tela_calendario h1 .nome").text(anterior.attr('data-nome'));
            }
        }
        this.atualizaNavegacao();
    },

    proximo: function() {
        var calendarios = $("#calendarios .mes");
        if(calendarios.length>0) {
            var proximo = $("#calendarios .mes.ativo").next();

            if(proximo.length > 0) {
                calendarios.removeClass("ativo");
                proximo.addClass('ativo');
                $("#tela_calendario h1 .nome").text(proximo.attr('data-nome'));
            }
        }
        this.atualizaNavegacao();
    },

    atualizaNavegacao: function() {
        var ativo = $("#calendarios .mes.ativo");

        if(ativo.prev().length > 0) {
            $("#tela_calendario h1 .icone.anterior").show();
        } else {
            $("#tela_calendario h1 .icone.anterior").hide();
        }

        if(ativo.next().length > 0) {
            $("#tela_calendario h1 .icone.proximo").show();
        } else {
            $("#tela_calendario h1 .icone.proximo").hide();
        }
    },

    posCarregamento: function() {
        this.Formulario.enviar();
    },

    nomeDoMes: function(mes) {
        return i18n.t("sentimento.calendario.meses."+this._meses[mes-1]);
    },
    nomeDoDiaPeloNumeroNaSemana: function(diaNaSemana) {
        return i18n.t("sentimento.calendario.diasDaSemana."+this._dias[diaNaSemana]);
    },

    quantidadeDeDiasNoMes: function(mes,ano) {
        return new Date(ano,mes,1,-1).getDate();
    },

    diaNaSemana: function(dia,mes,ano) {
        return new Date(ano,mes-1,dia,0).getDay();
    },

    diaNaSemanaDoPrimeiroDiaDoMes: function(mes,ano) {
        return this.diaNaSemana(1, mes, ano);
    },

    montarCalendario: function(mes, ano, dados) {
        var diasNoMes = this.quantidadeDeDiasNoMes(mes,ano);
        var diaNaSemanaDoPrimeiroDiaDoMes = this.diaNaSemanaDoPrimeiroDiaDoMes(mes,ano);
        var nomeDoMes = this.nomeDoMes(mes);
        var eMes = $("<table>")
                            .addClass('mes')
                            .attr('data-mes', mes)
                            .attr('data-ano', ano)
                            .attr('data-nome', nomeDoMes);

            //$("<h2>").text(nomeDoMes).appendTo(eMes);
            //



        var linha0 = $("<tr>").addClass('linha').appendTo(eMes);
        var linha1 = $("<tr>").addClass('linha').appendTo(eMes);
        var linha2 = $("<tr>").addClass('linha').appendTo(eMes);
        var linha3 = $("<tr>").addClass('linha').appendTo(eMes);
        var linha4 = $("<tr>").addClass('linha').appendTo(eMes);
        var linha5 = $("<tr>").addClass('linha').appendTo(eMes);


        for(var dia = 0; dia <= 6; dia++) {

            var nome = this.nomeDoDiaPeloNumeroNaSemana(dia);

            $("<td>")
                .addClass('preOffset')
                .text(nome[0])
                .appendTo(linha0);
        }

        for(var preOffset = 0; preOffset < diaNaSemanaDoPrimeiroDiaDoMes; preOffset++) {
            $("<td>").addClass('preOffset').appendTo(linha1);
        }

        for(var dia = 1; dia <= diasNoMes; dia++) {
            var objDia = dados[dia] || null;
            var linha = Math.floor((dia+(diaNaSemanaDoPrimeiroDiaDoMes-1))/7)
            var sentimento = objDia && objDia.sentimento ? parseFloat(objDia.sentimento) : -1;
            var floor, ceil, corInicial, corFinal, corCalculada, porcentagem;

            var eDia = $("<td>")
                            .addClass('dia')
                            .attr('data-dia', dia)
                            // .attr('data-dianasemana', this.diaNaSemana(dia,mes,ano))
                            // .attr('data-interacoes', objDia && objDia.qtd ? objDia.qtd : 0)
                            // .attr('data-sentimento', sentimento)
                            .appendTo(eMes.find(".linha").eq(linha+1));

            var bg = $("<span>").addClass('bg').appendTo(eDia);

            if(sentimento > -1) {
                porcentagem = sentimento - Math.floor(sentimento);
                floor = Math.floor(sentimento);
                ceil = Math.floor(sentimento);
                corInicial = this._cores[floor];
                corFinal = this._cores[floor];
                corCalculada = pusher.color(corInicial).blend(corFinal, porcentagem).hex6();
                bg.css("background-color", corCalculada);
            }

            $("<span>").addClass('numero').text((dia+"").length == 1 ? "0"+dia:dia).appendTo(eDia);

        }
        for(var posOffset = 0; posOffset < (7-linha5.find('.dia').length); posOffset++) {
            $("<td>").addClass('posOffset').appendTo(linha5);
        }

        return eMes.get(0);
    },

    montarCalendarios: function(datas) {
        var anos = this.calcularMeses(datas);
        var fragmento = document.createDocumentFragment();



        for(ano in anos) {
            if(!anos.hasOwnProperty(ano)) continue;
            var meses = anos[ano];
            for(mes in meses) {
                if(!meses.hasOwnProperty(mes)) continue;
                var calendario = this.montarCalendario(mes, ano, meses[mes]);
                fragmento.appendChild(calendario);
            }
        }

        $("#calendarios").empty().get(0).appendChild(fragmento);

        var calendarios = $("#calendarios .mes");

        if(calendarios.length > 0) {
            $("#tela_calendario h1 .nome").text(calendarios.first().attr('data-nome'));
            calendarios.first().addClass('ativo');
        }
        if(calendarios.length == 1) {
            $("#tela_calendario h1 .icone").hide();
        }



        APP.Area.Rolagem._iScroll.tela_calendario.refresh();
    },

    calcularMeses: function(datas) {
        var objDatas = {};
        $(datas).each(function(iDate, date) {

            var dataCadastro, ano, mes, dia;
                dataCadastro = date.dataCadastro.split("-");
                ano = parseInt(dataCadastro[0]);
                mes = parseInt(dataCadastro[1]);
                dia = parseInt(dataCadastro[2]);

                objDatas[ano] = objDatas[ano] || {};
                objDatas[ano][mes] = objDatas[ano][mes] || {};
                objDatas[ano][mes][dia] = date;

                //objDatas[ano] = $.unique(objDatas[ano]);
        });
        return objDatas;

    },



    Formulario: {


        setUp: function() {


        },



        enviar: function(form) {
            var that = this;


            $.ajax({
                url: "http://saudenacopa.epitrack.com.br/api/rest/calendario",
                dataType: "JSON",
                type: "post",
                data: {
                    idusuario:APP.Area.Acesso.Usuario._dados.userID
                },

                beforeSend: function() {
                    that.enviando.apply(that, arguments);
                },
                success: function() {
                    that.enviou.apply(that, arguments);
                },
                error: function() {
                    that.naoEnviou.apply(that, arguments);
                }
            });
        },



        enviando: function() {
            this.adicionarCarregando();
        },

        adicionarCarregando: function() {
            $("#tela_calendario")
                .addClass('loading');
        },

        removerCarregando: function() {
            $("#tela_calendario")
                .removeClass('loading');
        },

        enviou: function(data) {
            if(data.status) {
                this.pai().montarCalendarios(data.data);
                APP.analytics.trackEvent('torcedor',"calenario", 'realizado');
            } else {
                if(data.mensagem) {
                    this.naoEnviou(data.mensagem);
                } else {
                    this.naoEnviou()
                }
            }
            this.removerCarregando();
        },

        naoEnviou: function(feedback) {
            APP.analytics.trackEvent('torcedor',"calenario", 'erro no envio');
            var mensagem = "Erro.";

            if(feedback) {
                mensagem += "\n "+feedback;
            }

            alert(mensagem);

            this.removerCarregando();
        }
    }
};
