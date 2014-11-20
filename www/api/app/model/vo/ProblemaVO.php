<?php
/**
 * Created by PhpStorm.
 * User: teoria
 * Date: 3/16/14
 * Time: 7:40 PM
 */



class ProblemaVO extends VO{
    private $idUsuario;
    private $msg;

    /**
     * @param mixed $idUsuario
     */
    public function setIdUsuario($idUsuario) {

        $this->idUsuario = $idUsuario;
    }

    /**
     * @return mixed
     */
    public function getIdUsuario() {

        return $this->idUsuario;
    }

    /**
     * @param mixed $msg
     */
    public function setMsg($msg) {

        $this->msg = $msg;
    }

    /**
     * @return mixed
     */
    public function getMsg() {

        return $this->msg;
    }


} 