<?php
header('Content-Type: text/cache-manifest');
//header("Content-type: text/plain; charset=utf8");

$meusHashes = "";

$styles= "bibliotecas/bootstrap/css/bootstrap.css
bibliotecas/bootstrap/css/bootstrap.css.map
bibliotecas/jquery-mobile/jquery.mobile.custom.structure.min.css
bibliotecas/jquery-mobile/jquery.mobile.custom.theme.min.css
css/APP.css";
$arrayStyles = explode("\n", $styles);


#style sheet images";
//$cache[] = "data:image/gif;base64,R0lGODlhAQABAID/AMDAwAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==";
$imagens = "imagens/acesso/botao_voltar.png
imagens/acesso/icone_cadastreSe.png
imagens/acesso/icone_cadeado.png
imagens/acesso/icone_entrar.png
imagens/acesso/icone_facebook.png
imagens/acesso/marca.png
imagens/apresentacao/abertura/marca.png
imagens/apresentacao/abertura/skoll.png
imagens/apresentacao/abertura/sus.png
imagens/apresentacao/abertura/tephinet.png
imagens/apresentacao/apresentacao/botao_avancar.png
imagens/apresentacao/apresentacao/marca.png
imagens/apresentacao/idioma/en.png
imagens/apresentacao/idioma/es.png
imagens/apresentacao/idioma/pt.png
imagens/barra/menu/icone_jogador_off.png
imagens/barra/menu/icone_jogador_on.png
imagens/barra/menu/icone_jogos_cinza.png
imagens/barra/menu/icone_jogos_on.png
imagens/barra/menu/icone_maisOpcoes.png
imagens/barra/menu/icone_mais_off.png
imagens/barra/menu/icone_mais_on.png
imagens/barra/menu/icone_saude_off.png
imagens/barra/menu/icone_saude_on.png
imagens/barra/titulo/botao_calendario.png
imagens/barra/titulo/botao_informacoes.png
imagens/barra/titulo/botao_lista.png
imagens/barra/titulo/botao_mapa.png
imagens/barra/titulo/botao_ok.png
imagens/barra/titulo/botao_voltar.png
imagens/loader.gif
imagens/mais/itensDoJogo/icone_arena.png
imagens/mais/itensDoJogo/icone_barraDeEngajamento.png
imagens/mais/itensDoJogo/icone_categoria.png
imagens/mais/itensDoJogo/icone_ok.png
imagens/mais/itensDoJogo/icone_pontos.png
imagens/mais/itensDoJogo/icone_selecao.png
imagens/mais/mais/icone_arenas.png
imagens/mais/mais/icone_categorias.png
imagens/mais/mais/icone_consulados.png
imagens/mais/mais/icone_itensDoJogo.png
imagens/mais/mais/icone_linksUteis.png
imagens/mais/mais/icone_malaLegal.png
imagens/menu/marca.png
imagens/menu/menu/icone_alterarSenha.png
imagens/menu/menu/icone_denunciarUmProblema.png
imagens/menu/menu/icone_idiomas.png
imagens/menu/menu/icone_sair.png
imagens/menu/menu/icone_sobre.png
imagens/menu/menu/icone_termosDeUso.png
imagens/saude/icone_brasil.png
imagens/saude/icone_farmacias.png
imagens/saude/icone_higieneECuidados.png
imagens/saude/icone_hospitais.png
imagens/saude/icone_protejaOGol.png
imagens/saude/icone_telefone.png
imagens/saude/icone_vacinas.png
imagens/sentimento/arenas/bh.png
imagens/sentimento/arenas/brasilia.png
imagens/sentimento/arenas/cuiaba.png
imagens/sentimento/arenas/curitiba.png
imagens/sentimento/arenas/fortaleza.png
imagens/sentimento/arenas/manaus.png
imagens/sentimento/arenas/natal.png
imagens/sentimento/arenas/pernambuco.png
imagens/sentimento/arenas/portoAlegre.png
imagens/sentimento/arenas/rioDeJaneiro.png
imagens/sentimento/arenas/salvador.png
imagens/sentimento/arenas/saoPaulo.png
imagens/sentimento/avatar/feminino/denteDeLeite/bem.png
imagens/sentimento/avatar/feminino/denteDeLeite/mal.png
imagens/sentimento/avatar/feminino/denteDeLeite/muitoBem.png
imagens/sentimento/avatar/feminino/denteDeLeite/muitoMal.png
imagens/sentimento/avatar/feminino/denteDeLeite/normal.png
imagens/sentimento/avatar/feminino/infantil/bem.png
imagens/sentimento/avatar/feminino/infantil/mal.png
imagens/sentimento/avatar/feminino/infantil/muitoBem.png
imagens/sentimento/avatar/feminino/infantil/muitoMal.png
imagens/sentimento/avatar/feminino/infantil/normal.png
imagens/sentimento/avatar/feminino/junior/bem.png
imagens/sentimento/avatar/feminino/junior/mal.png
imagens/sentimento/avatar/feminino/junior/muitoBem.png
imagens/sentimento/avatar/feminino/junior/muitoMal.png
imagens/sentimento/avatar/feminino/junior/normal.png
imagens/sentimento/avatar/feminino/juvenil/bem.png
imagens/sentimento/avatar/feminino/juvenil/mal.png
imagens/sentimento/avatar/feminino/juvenil/muitoBem.png
imagens/sentimento/avatar/feminino/juvenil/muitoMal.png
imagens/sentimento/avatar/feminino/juvenil/normal.png
imagens/sentimento/avatar/feminino/mirim/bem.png
imagens/sentimento/avatar/feminino/mirim/mal.png
imagens/sentimento/avatar/feminino/mirim/muitoBem.png
imagens/sentimento/avatar/feminino/mirim/muitoMal.png
imagens/sentimento/avatar/feminino/mirim/normal.png
imagens/sentimento/avatar/feminino/profissional/bem.png
imagens/sentimento/avatar/feminino/profissional/mal.png
imagens/sentimento/avatar/feminino/profissional/muitoBem.png
imagens/sentimento/avatar/feminino/profissional/muitoMal.png
imagens/sentimento/avatar/feminino/profissional/normal.png
imagens/sentimento/avatar/masculino/denteDeLeite/bem.png
imagens/sentimento/avatar/masculino/denteDeLeite/mal.png
imagens/sentimento/avatar/masculino/denteDeLeite/muitoBem.png
imagens/sentimento/avatar/masculino/denteDeLeite/muitoMal.png
imagens/sentimento/avatar/masculino/denteDeLeite/normal.png
imagens/sentimento/avatar/masculino/infantil/bem.png
imagens/sentimento/avatar/masculino/infantil/mal.png
imagens/sentimento/avatar/masculino/infantil/muitoBem.png
imagens/sentimento/avatar/masculino/infantil/muitoMal.png
imagens/sentimento/avatar/masculino/infantil/normal.png
imagens/sentimento/avatar/masculino/junior/bem.png
imagens/sentimento/avatar/masculino/junior/mal.png
imagens/sentimento/avatar/masculino/junior/muitoBem.png
imagens/sentimento/avatar/masculino/junior/muitoMal.png
imagens/sentimento/avatar/masculino/junior/normal.png
imagens/sentimento/avatar/masculino/juvenil/bem.png
imagens/sentimento/avatar/masculino/juvenil/mal.png
imagens/sentimento/avatar/masculino/juvenil/muitoBem.png
imagens/sentimento/avatar/masculino/juvenil/muitoMal.png
imagens/sentimento/avatar/masculino/juvenil/normal.png
imagens/sentimento/avatar/masculino/mirim/bem.png
imagens/sentimento/avatar/masculino/mirim/mal.png
imagens/sentimento/avatar/masculino/mirim/muitoBem.png
imagens/sentimento/avatar/masculino/mirim/muitoMal.png
imagens/sentimento/avatar/masculino/mirim/normal.png
imagens/sentimento/avatar/masculino/profissional/bem.png
imagens/sentimento/avatar/masculino/profissional/mal.png
imagens/sentimento/avatar/masculino/profissional/muitoBem.png
imagens/sentimento/avatar/masculino/profissional/muitoMal.png
imagens/sentimento/avatar/masculino/profissional/normal.png
imagens/sentimento/informacoes/ajuda/icone_ok.png
imagens/sentimento/informacoes/ajuda/icone_selecao.png
imagens/sentimento/informacoes/ajuda/icone_selecaoArena.png
imagens/sentimento/torcedor/botao_ok.png
imagens/sentimento/torcedor/categorias/denteDeLeite/categoria_pt.png
imagens/sentimento/torcedor/categorias/denteDeLeite/trofeu.png
imagens/sentimento/torcedor/categorias/infantil/categoria_pt.png
imagens/sentimento/torcedor/categorias/infantil/trofeu.png
imagens/sentimento/torcedor/categorias/junior/categoria_pt.png
imagens/sentimento/torcedor/categorias/junior/trofeu.png
imagens/sentimento/torcedor/categorias/juvenil/categoria_pt.png
imagens/sentimento/torcedor/categorias/juvenil/trofeu.png
imagens/sentimento/torcedor/categorias/mirim/categoria_pt.png
imagens/sentimento/torcedor/categorias/mirim/trofeu.png
imagens/sentimento/torcedor/categorias/profissional/categoria_pt.png
imagens/sentimento/torcedor/categorias/profissional/trofeu.png
imagens/sentimento/torcedor/confirmacao/botao_voltar.png
imagens/sentimento/torcedor/confirmacao/icone_checkbox.png
imagens/sentimento/torcedor/confirmacao/icone_marcacao.png
imagens/sentimento/torcedor/icone_farmacias.png
imagens/sentimento/torcedor/icone_hospitais.png
imagens/sentimento/torcedor/luzAvatar.png";
$arrayImagens = explode("\n",$imagens);

#javascript files
$scripts = "http://saude.dcc:8888/locales/pt/translation.json
http://saude.dcc:8888/bibliotecas/jquery-validation/localization/messages_pt_BR.js
http://saude.dcc:8888/locales/en/translation.json
http://saude.dcc:8888/locales/es/translation.json
http://saude.dcc:8888/js/init.js
http://saude.dcc:8888/bibliotecas/js/head.min.js
bibliotecas/iscroll-master/build/iscroll.js
bibliotecas/js/jquery-2.1.0.js
bibliotecas/js/jquery.serializeJSON.min.js
bibliotecas/js/jquery.geolocation.min.js
bibliotecas/js/jquery.ui.map.full.min.js
bibliotecas/jquery-mobile/jquery.mobile.custom.min.js
bibliotecas/jquery-validation/dist/jquery.validate.js
bibliotecas/jquery-validation/localization/messages_pt_BR.js
bibliotecas/js/jquery.maskedinput.js
bibliotecas/i18next-1.7.2/i18next-1.7.2.js
bibliotecas/APP/APP.js
bibliotecas/APP/APP.GerenciadorDeTelas.js
js/APP.js
js/APP.GerenciadorDeTelas.js
js/APP.Barra.js
js/APP.Barra.Titulo.js
js/APP.Barra.Menu.js
js/APP.Area.js
js/APP.Area.Apresentacao.js
js/APP.Area.Acesso.js
js/APP.Area.Acesso.Login.js
js/APP.Area.Acesso.CadastreSe.js
js/APP.Area.Acesso.EsqueciASenha.js
js/APP.Area.Acesso.AlterarSenha.js
js/APP.Area.Sentimento.js
js/APP.Area.Sentimento.Torcedor.js
js/APP.Area.Sentimento.Torcedor.Avatar.js
js/APP.Area.Sentimento.Torcedor.Sintomas.js
js/APP.Area.Sentimento.Torcedor.Arenas.js
js/APP.Area.Saude.js
js/APP.Area.Saude.Hospitais.js
js/APP.Area.Saude.Hospitais._enderecos.js
js/APP.Area.Saude.Farmacias.js
js/APP.Area.Mais.js
js/APP.Area.Mais.Consulados.js
js/APP.Area.Mais.Consulados._enderecos.js
js/APP.Area.Menu.js
js/APP.Offline.js";
$arrayScripts = explode("\n", $scripts);



$cache = array_merge($arrayStyles, $arrayImagens, $arrayScripts);

// $cache[] = "index.html";
// $cache[] = "js/jquery-1.7.2.min.js";
// $cache[] = "js/APP.js";
?>CACHE MANIFEST

CACHE:
<?php
foreach($cache as $arquivo) {
	$meusHashes .= md5_file($arquivo);	
	echo $arquivo."\n";
}
?>

NETWORK:


FALLBACK:

#hash <?php echo md5($meusHashes); ?>