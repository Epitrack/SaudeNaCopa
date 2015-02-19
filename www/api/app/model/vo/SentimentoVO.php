<?php
/**
 * Created by PhpStorm.
 * User: teoria
 * Date: 3/16/14
 * Time: 9:06 PM
 */

class SentimentoVO extends VO {

//INSERT INTO saudenacopa.usuario_sentimento (id; usuario_id;   jogo_ok; pontuacao; latitude; longitude; cidade_id; atualizado; ativo; campo1; campo2; campo3; campo4; campo5; campo6; campo7; campo8; campo9; campo10; campo11; campo12) VALUES (NULL; '9';  '1'; '12'; '1142435'; '1142435'; '1'; '1'; '1'; '1'; '0'; '1'; '1'; '1'; '1'; '1'; '0'; '0'; '1'; '1'; '0')

    private $id;
    private $usuario_id;
    private $jogo_ok;
    private $pontuacao;
    private $latitude;
    private $longitude;
    private $cidade_id;
    private $atualizado;
    private $ativo;
    private $sentimento;
    private $data_cadastro;

    private $campo1;
    private $campo2;
    private $campo3;
    private $campo4;
    private $campo5;
    private $campo6;
    private $campo7;
    private $campo8;
    private $campo9;
    private $campo10;
    private $campo11;
    private $campo12;

    function __construct() {
        $this->jogo_ok = 1;
        $this->pontuacao = 1;
        $this->ativo = 1;
        $this->atualizado = 1;
        $this->cidade_id = 1;
        //$this->sentimento =0;

        /*$this->campo1 = 0;
        $this->campo2 = 0;
        $this->campo3 = 0;
        $this->campo4 = 0;
        $this->campo5 = 0;
        $this->campo6 = 0;
        $this->campo7 = 0;
        $this->campo8 = 0;
        $this->campo9 = 0;
        $this->campo10 = 0;
        $this->campo11 = 0;
        $this->campo12 = 0;*/

    }

    /**
     * @param mixed $data_cadastro
     */
    public function setDataCadastro($data_cadastro) {

        $this->data_cadastro = $data_cadastro;
    }

    /**
     * @return mixed
     */
    public function getDataCadastro() {

        return $this->data_cadastro;
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




    /**
     * @param mixed $ativo
     */
    public function setAtivo($ativo) {

        $this->ativo = $ativo;
    }

    /**
     * @return mixed
     */
    public function getAtivo() {

        return $this->ativo;
    }

    /**
     * @param mixed $atualizado
     */
    public function setAtualizado($atualizado) {

        $this->atualizado = $atualizado;
    }

    /**
     * @return mixed
     */
    public function getAtualizado() {

        return $this->atualizado;
    }

    /**
     * @param mixed $campo1
     */
    public function setCampo1($campo1) {

        $this->campo1 = $campo1;
    }

    /**
     * @return mixed
     */
    public function getCampo1() {

        return $this->campo1;
    }

    /**
     * @param mixed $campo10
     */
    public function setCampo10($campo10) {

        $this->campo10 = $campo10;
    }

    /**
     * @return mixed
     */
    public function getCampo10() {

        return $this->campo10;
    }

    /**
     * @param mixed $campo11
     */
    public function setCampo11($campo11) {

        $this->campo11 = $campo11;
    }

    /**
     * @return mixed
     */
    public function getCampo11() {

        return $this->campo11;
    }

    /**
     * @param mixed $campo12
     */
    public function setCampo12($campo12) {

        $this->campo12 = $campo12;
    }

    /**
     * @return mixed
     */
    public function getCampo12() {

        return $this->campo12;
    }

    /**
     * @param mixed $campo2
     */
    public function setCampo2($campo2) {

        $this->campo2 = $campo2;
    }

    /**
     * @return mixed
     */
    public function getCampo2() {

        return $this->campo2;
    }

    /**
     * @param mixed $campo3
     */
    public function setCampo3($campo3) {

        $this->campo3 = $campo3;
    }

    /**
     * @return mixed
     */
    public function getCampo3() {

        return $this->campo3;
    }

    /**
     * @param mixed $campo4
     */
    public function setCampo4($campo4) {

        $this->campo4 = $campo4;
    }

    /**
     * @return mixed
     */
    public function getCampo4() {

        return $this->campo4;
    }

    /**
     * @param mixed $campo5
     */
    public function setCampo5($campo5) {

        $this->campo5 = $campo5;
    }

    /**
     * @return mixed
     */
    public function getCampo5() {

        return $this->campo5;
    }

    /**
     * @param mixed $campo6
     */
    public function setCampo6($campo6) {

        $this->campo6 = $campo6;
    }

    /**
     * @return mixed
     */
    public function getCampo6() {

        return $this->campo6;
    }

    /**
     * @param mixed $campo7
     */
    public function setCampo7($campo7) {

        $this->campo7 = $campo7;
    }

    /**
     * @return mixed
     */
    public function getCampo7() {

        return $this->campo7;
    }

    /**
     * @param mixed $campo8
     */
    public function setCampo8($campo8) {

        $this->campo8 = $campo8;
    }

    /**
     * @return mixed
     */
    public function getCampo8() {

        return $this->campo8;
    }

    /**
     * @param mixed $campo9
     */
    public function setCampo9($campo9) {

        $this->campo9 = $campo9;
    }

    /**
     * @return mixed
     */
    public function getCampo9() {

        return $this->campo9;
    }

    /**
     * @param mixed $cidade_id
     */
    public function setCidadeId($cidade_id) {

        $this->cidade_id = $cidade_id;
    }

    /**
     * @return mixed
     */
    public function getCidadeId() {

        return $this->cidade_id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id) {

        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId() {

        return $this->id;
    }

    /**
     * @param mixed $jogo_ok
     */
    public function setJogoOk($jogo_ok) {

        $this->jogo_ok = $jogo_ok;
    }

    /**
     * @return mixed
     */
    public function getJogoOk() {

        return $this->jogo_ok;
    }

    /**
     * @param mixed $latitude
     */
    public function setLatitude($latitude) {

        $this->latitude = $latitude;
    }

    /**
     * @return mixed
     */
    public function getLatitude() {

        return $this->latitude;
    }

    /**
     * @param mixed $longitude
     */
    public function setLongitude($longitude) {

        $this->longitude = $longitude;
    }

    /**
     * @return mixed
     */
    public function getLongitude() {

        return $this->longitude;
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
     * @param mixed $usuario_id
     */
    public function setUsuarioId($usuario_id) {

        $this->usuario_id = $usuario_id;
    }

    /**
     * @return mixed
     */
    public function getUsuarioId() {

        return $this->usuario_id;
    }



    public function isBad(){

        return (
            $this->campo1 == 1 ||
            $this->campo2 == 1 ||
            $this->campo3 == 1 ||
            $this->campo4 == 1 ||
            $this->campo5 == 1 ||
            $this->campo6 == 1 ||
            $this->campo7 == 1 ||
            $this->campo8 == 1 ||
            $this->campo9 == 1 ||
            $this->campo10 == 1 
        );

    }



} 