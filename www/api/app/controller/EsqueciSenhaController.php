<?php

Class EsqueciSenhaController extends Controller {


    public function getSenha2() {

        $retorno = array(

            "status" => true
        );
        echo json_encode($retorno);
    }


    public function getSenha() {

        $data = $this->request->post();

        $fachada = Fachada::getInstance();
        //$fachada = new Fachada();

        $vo = new UsuarioVO();
        $vo->setEmail($data['email']);

        $userVO = $fachada->selectOneByEmail($vo);

        $validade  = mktime (0, 0, 0, date("m")  , date("d")+1, date("Y"));



        $URL = "http://www.saudenacopa.epitrack.com.br/api/rest/mudarSenha/". base64_encode( $userVO->getIdUsuario() ."#". $validade ."#". md5( $userVO->getEmail()) );


        $msg = "Alguém pediu recentemente que a senha seja redefinido para ".$userVO->getApelido()."
       <br><br> Para redefinir sua senha, por favor clique neste link: <br><a href='$URL'>Redefinir Senha</a> <br><br>
       Se isso é um erro, simplesmente ignorar este e-mail - sua senha não será alterada.";


       // $msg = "Texto falando da senha nova e o site com o link >> <a href='$URL'>Redefinir Senha</a> ";
        $enviado = $fachada->sendEmail( $userVO->getEmail() , 'contato@saudenacopa.epitrack.com.br','Esqueceu sua senha?',$msg);

        $retorno = $this->getResponse($enviado);
        echo $retorno;
    }


   public function trocaSenha($cod){
       $codigo = explode("#", base64_decode($cod) );
       $id = $codigo[0];
       $validade = $codigo[1];
       $email = $codigo[2];


       $valido = (date("d/m/Y",$validade) > date("d/m/Y"));
       //var_dump($codigo);

       $fachada = Fachada::getInstance();
       //$fachada = new Fachada();

       $vo = new UsuarioVO();
       $vo->setIdUsuario($id);

       $vo = $fachada->selectOneByID($vo);

       if($valido && md5( $vo->getEmail() ) == $email ){
           $this->render("senha.php" , array("cod"=>$cod,"nome"=>$vo->getApelido()) );
       }else{
           $this->render("error.php" , array() , 404 );
       }


   }

    public function novaSenha($cod) {
        $codigo = explode("#", base64_decode($cod) );
        $id = $codigo[0];
        $email = $codigo[2];


        $data = $this->request->post();


        $fachada = Fachada::getInstance();

        $vo = new UsuarioVO();
        $vo->setIdUsuario($id);

        $vo = $fachada->selectOneByID($vo);




        if( $vo ){
            $vo->setSenha(md5($data['senha']));


        try{
            $fachada->updateUser($vo);

        }catch (Exception $e){
            var_dump($e);
        }


            $this->render(
                "senhaTrocada.php",
                array("titulo" => "Mudança de Senha", "texto" => "Senha modificada com sucesso.")
            );
        }else{
            $this->redirect("./");
        }

    }


    public function alteraSenhaRest(){

        $data = $this->request->post();
        $fachada = Fachada::getInstance();
        $id = (int)$fachada->decript($data["userID"]);


        if( is_nan($id)){
            throw new InvalidArgumentException;
        }
        $userVO = new UsuarioVO();
        $userVO->setIdUsuario($id);
        /*
         *
          senhaAtual
            novaSenha
            repetirNovaSenha
            userID
         *
         */



        $vo = $fachada->selectOneByID($userVO);

        if( $vo ){
            $md5SenhaAtual = md5( $data['senhaAtual'] );
            $boo = $md5SenhaAtual == $vo->getSenha();

            if(!$boo){
                throw new InvalidArgumentException;
            }
            $vo->setSenha( md5($data['novaSenha']) );

            try{
                $fachada->updateUser($vo);

            }catch (Exception $e){
                var_dump($e);
            }

        }

        echo $this->getResponse($vo);
    }



    private function getResponse($usuario) {

        $retorno = array(

            "status" => ($usuario != false),
            "usuario" =>  ''
        );
        return json_encode($retorno);
    }

}

