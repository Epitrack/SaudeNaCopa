<?php
/**
 * Created by PhpStorm.
 * User: teoria
 * Date: 3/12/14
 * Time: 12:50 AM
 */

class ProblemaDAO extends DAO{

    public function insert( ProblemaVO $vo ){


        $sql = "INSERT INTO  problemas ( `usuarios_id`, `msg`) VALUES ( :idusuario, :texto)";


        $arrParams = array(
            ':idusuario'=>$vo->getIdUsuario(),
            ':texto'=>$vo->getMsg()
        );



        try{
            $stm = $this->conex->prepare($sql);
            $stm->execute($arrParams);
        }catch (Exception $e){
            throw new Exception("Não foi possível realizar o cadastro.");
        }

    }








} 