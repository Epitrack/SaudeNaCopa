<?php
/**
 * Created by PhpStorm.
 * User: teoria
 * Date: 3/20/14
 * Time: 7:45 PM
 */

class LogGameVO extends VO {

    private $idLogGame;
    private $usuarios_id;
    private $acao;
    private $pontuacao;
    private $dataHora;

    /**
     * @param mixed $acao
     */
    public function setAcao($acao) {

        $this->acao = $acao;
    }

    /**
     * @return mixed
     */
    public function getAcao() {

        return $this->acao;
    }

    /**
     * @param mixed $dataHora
     */
    public function setDataHora($dataHora) {

        $this->dataHora = $dataHora;
    }

    /**
     * @return mixed
     */
    public function getDataHora() {

        return $this->dataHora;
    }

    /**
     * @param mixed $idLogGame
     */
    public function setIdLogGame($idLogGame) {

        $this->idLogGame = $idLogGame;
    }

    /**
     * @return mixed
     */
    public function getIdLogGame() {

        return $this->idLogGame;
    }

    /**
     * @param mixed $pontuacao
     */
    public function setPontuacao($pontuacao) {

        $this->pontuacao = $pontuacao;
    }

    /**
     * @return mixed
     */
    public function getPontuacao() {

        return $this->pontuacao;
    }

    /**
     * @param mixed $usuarios_id
     */
    public function setUsuariosId($usuarios_id) {

        $this->usuarios_id = $usuarios_id;
    }

    /**
     * @return mixed
     */
    public function getUsuariosId() {

        return $this->usuarios_id;
    }




} 