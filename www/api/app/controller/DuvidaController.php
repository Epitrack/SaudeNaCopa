<?php

Class DuvidaController extends Controller {

/*
    public function enviaDuvida() {

        $retorno = array(

            "status" => true,
            "mensagem"=>""
        );
        echo json_encode($retorno);
    }*/


    public function enviaDuvida() {

        $data = $this->request->post();
        $fachada = Fachada::getInstance();

        $id = (int)$fachada->decript($data["idusuario"]);

        if( is_nan($id)){
            throw new InvalidArgumentException;
        }

       // $id = $data['idusuario'];
        $msg = $data['msg'];

        $vo = new DuvidaVO();
        $vo->setIdUsuario($id);
        $vo->setMsg($msg);



        $fachada->insereDuvida($vo);

        $userVO = new UsuarioVO();
        $userVO->setIdUsuario($id);
        $userVO = $fachada->selectOneByID($userVO);


        $enviado = $fachada->sendEmail('contato@saudenacopa.com',$userVO->getEmail() , 'Dúvida Saúde na Copa',$msg);

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

