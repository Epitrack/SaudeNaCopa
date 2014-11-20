<?php
/**
 * Created by PhpStorm.
 * User: teoria
 * Date: 4/9/14
 * Time: 5:47 PM
 */

class CalendarioController extends Controller{

    public function getCalendario(){

        $data = $this->request->post();
        $fachada = Fachada::getInstance();

        $id = (int)$fachada->decript($data["idusuario"]);


        if( is_nan($id)){
            throw new InvalidArgumentException;
        }
        $userVO = new UsuarioVO();
        $userVO->setIdUsuario($id);



        $result = $fachada->getCalendario($userVO);

        $objRetorno = array();
        for($i=0;$i<count($result);$i++){
            $objRetorno[] = $result[$i]->parseArray();
        }

        $retorno = array(
            "status" => ($objRetorno != false),
            "data"=>$objRetorno
        );

        echo json_encode($retorno);

    }

} 