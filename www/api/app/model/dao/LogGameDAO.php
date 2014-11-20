<?php
/**
 * Created by PhpStorm.
 * User: teoria
 * Date: 3/20/14
 * Time: 7:47 PM
 */

class LogGameDAO extends DAO{

    public function insert(LogGameVO $vo) {


        $sql = " INSERT INTO logGame ( `usuarios_id`, `acao`, `pontuacao`)
                VALUES
                (:usuario_id, :acao, :pontuacao);";



        $arrParams = array(
            ':usuario_id'=>$vo->getUsuariosId(),
            ':acao'=>$vo->getAcao(),
            ':pontuacao'=>$vo->getPontuacao()

        );



        try{
            $stm = $this->conex->prepare($sql);
            return $stm->execute($arrParams);
        }catch (Exception $e){
            throw new Exception("Não foi possível realizar o cadastro.");
        }
    }




    public function getUltimoInsertByUser(UsuarioVO $vo) {

        $sql = "SELECT *  FROM `logGame` WHERE `usuarios_id` = :id order by`idLogGame` desc limit 0 , 1";

        $id = $vo->getIdUsuario();

        $stm = $this->conex->prepare($sql);
        $stm->bindParam(':id', $id, PDO::PARAM_STR);

        try{
            $stm->execute();
        }catch (Exception $e){
            throw new Exception("Não foi possível selecionar o Usuário.");
        }

        $stm->setFetchMode(PDO::FETCH_CLASS, 'LogGameVO');
        $vo = $stm->fetch(PDO::FETCH_CLASS);
        return $vo;
    }




    public function remove(LogGameVO $vo) {

        $sql = " DELETE FROM `logGame` WHERE `idLogGame` = :id";

        $arrParams = array(
            ':idLogGame'=>$vo->getIdLogGame()

        );

        try{
            $stm = $this->conex->prepare($sql);
            return $stm->execute($arrParams);
        }catch (Exception $e){
            throw new Exception("Não foi possível realizar a exclusão.");
        }
    }



} 