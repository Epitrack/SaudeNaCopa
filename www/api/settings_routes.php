<?php
/**
 * Created by PhpStorm.
 * User: teoria
 * Date: 3/12/14
 * Time: 5:41 PM
 */


$routes = array(
    '/' => '',

    '/cript' => "LoginController:cript@get",
    '/login' => "LoginController:logaUsuario@post",
    '/cadastraUsuario'=> "CadastroController:cadastraUsuario@post",
    '/updateGcm'=> "CadastroController:updateGcmid@post",
    '/updateUserArena'=> "CadastroController:updateArena@post",

    '/esqueciSenha'=> "EsqueciSenhaController:getSenha@post",
    '/mudarSenha/:codigo'=>"EsqueciSenhaController:trocaSenha@get",
    '/novaSenha/:codigo'=>"EsqueciSenhaController:novaSenha@post",

    '/duvida'=> "DuvidaController:enviaDuvida@post",
    '/problema'=> "ProblemaController:enviaProblema@post",


    '/sentimento'=> "SentimentoController:enviaSentimento@post",
    '/sentimento2'=> "SentimentoController:enviaSentimento2@post",

    '/calendario'=> "CalendarioController:getCalendario@post",

    '/alterarSenha'=> "EsqueciSenhaController:alteraSenhaRest@post"




    /*'/demo' => array(
        "get" =>  Main:test2 ,
        "post" => "Main:test3"
    ) */
);
$baseClass = "MainController";  // Método index da classe MainController será invocado quando o route "/" for chamado

$erroHandler = "MainController:notFound";
$basePath = "http://www.saudenacopa.epitrack.com.br/api/rest";

$debug = true;