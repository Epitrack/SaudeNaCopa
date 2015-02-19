<?php
/**
 * Created by PhpStorm.
 * User: teoria
 * Date: 3/12/14
 * Time: 12:50 AM
 */

class DuvidaDAO extends DAO{

    public function insert( DuvidaVO $vo ){


        $sql = "INSERT INTO  duvidas ( `usuarios_id`, `msg`) VALUES ( :idusuario, :texto)";


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