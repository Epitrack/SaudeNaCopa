<?php

Class ProblemaController extends Controller {

/*
    public function enviaDuvida() {

        $retorno = array(

            "status" => true,
            "mensagem"=>""
        );
        echo json_encode($retorno);
    }*/


    public function enviaProblema() {

        $data = $this->request->post();
        $fachada = Fachada::getInstance();
        //$id = $data['idusuario'];

        $id = (int)$fachada->decript($data["idusuario"]);
        if( is_nan($id)){
            throw new InvalidArgumentException;
        }


        $msg = $data['msg'];

        $vo = new ProblemaVO();
        $vo->setIdUsuario($id);
        $vo->setMsg($msg);



        $fachada->insereProblema($vo);

        $userVO = new UsuarioVO();
        $userVO->setIdUsuario($id);
        $userVO = $fachada->selectOneByID($userVO);


        $enviado = $fachada->sendEmail('contato@saudenacopa.com',$userVO->getEmail() , '[PROBLEMA] Saúde na Copa',$msg);

        $retorno = $this->getResponse($enviado);
        echo $retorno;
    }




    private function getResponse($enviado) {

        $retorno = array(

            "status" => ($enviado != false),
            "mensagem"=>""
        );
        return json_encode($retorno);
    }






}

