<?php
/**
 * Created by PhpStorm.
 * User: teoria
 * Date: 4/9/14
 * Time: 5:56 PM
 */

class CalendarioVO extends VO{

    protected  $dataCadastro;
    protected  $qtd;
    protected  $sentimento;

    /**
     * @param mixed $dataCadastro
     */
    public function setDataCadastro($dataCadastro) {

        $this->dataCadastro = $dataCadastro;
    }

    /**
     * @return mixed
     */
    public function getDataCadastro() {

        return $this->dataCadastro;
    }

    /**
     * @param mixed $qtd
     */
    public function setQtd($qtd) {

        $this->qtd = $qtd;
    }

    /**
     * @return mixed
     */
    public function getQtd() {

        return $this->qtd;
    }

    /**
     * @param mixed $sentimento
     */
    public function setSentimento($sentimento) {

        $this->sentimento = $sentimento;
    }

    /**
     * @return mixed
     */
    public function getSentimento() {

        return $this->sentimento;
    }



} 