<?php
/**
 * Created by PhpStorm.
 * User: teoria
 * Date: 3/12/14
 * Time: 12:50 AM
 */

class UsuarioDAO extends DAO{

    public function insert( UsuarioVO $vo ){


        $sql = "INSERT INTO usuarios (email,senha,idade,apelido,sexo,gcmid,idioma,totalPontosPossiveis,device)
              VALUES (:email,:senha,:idade,:apelido,:sexo,:gcmid,:idioma,:totalPontosPossiveis,:device)";



        $arrParams = array(
            ':apelido'=>$vo->getApelido(),
            ':email'=>$vo->getEmail(),
            ':senha'=>$vo->getSenha(),
            ':idade'=>$vo->getIdade(),
            ':sexo'=>$vo->getSexo(),
            ':gcmid'=>$vo->getGcmid(),
            ':idioma'=>$vo->getIdioma(),
            ':totalPontosPossiveis'=>$vo->getTotalPontosPossiveis(),
            ':device'=>$vo->getDevice()
        );



        try{
            $stm = $this->conex->prepare($sql);
            $stm->execute($arrParams);
        }catch (Exception $e){
            throw new Exception("Não foi possível realizar o cadastro.");
        }

    }
    public function insertFB( UsuarioVO $vo ){


        $sql = "INSERT INTO usuarios (email,apelido,sexo)
              VALUES (:idFB,:apelido, :sexo )";


        $arrParams = array(
            ':idFB'=>$vo->getEmail(),
            ':apelido'=>$vo->getApelido(),
            ':sexo'=> $vo->getSexo()
        );



        try{
            $stm = $this->conex->prepare($sql);
            $stm->execute($arrParams);
        }catch (Exception $e){
            throw new Exception("Não foi possível realizar o cadastro.");
        }

    }

    public function countAll(){

        $sql = 'SELECT count(*) as num FROM usuarios';

        $qtd = $this->conex->query($sql);
        $qtd = $qtd->fetch(PDO::FETCH_OBJ);

        return $qtd->num;

    }


    public function selectOne( UsuarioVO $vo ){

        $sql = "SELECT * FROM usuarios WHERE email = :login and senha = :senha";

        $login = $vo->getEmail();
        $senha = $vo->getSenha();

        $stm = $this->conex->prepare($sql);
        $stm->bindParam(':login', $login, PDO::PARAM_STR);
        $stm->bindParam(':senha', $senha, PDO::PARAM_STR);

        try{
            $stm->execute();
        }catch (Exception $e){
            throw new Exception("Não foi possível selecionar o Usuário.");
        }

        $stm->setFetchMode(PDO::FETCH_CLASS, 'UsuarioVO');
        $vo = $stm->fetch(PDO::FETCH_CLASS);
        return $vo;

    }

    public function selectOneFB( UsuarioVO $vo ){

        $sql = "SELECT * FROM usuarios WHERE email = :login";

        $login = $vo->getEmail();

        $stm = $this->conex->prepare($sql);
        $stm->bindParam(':login', $login, PDO::PARAM_STR);

        try{
            $stm->execute();
        }catch (Exception $e){
            throw new Exception("Não foi possível selecionar o Usuário.");
        }

        $stm->setFetchMode(PDO::FETCH_CLASS, 'UsuarioVO');
        $vo = $stm->fetch(PDO::FETCH_CLASS);
        return $vo;

    }

    public function selectOneByID( UsuarioVO $vo ){

        $sql = "SELECT * FROM usuarios WHERE id = :id";

        $id = $vo->getIdUsuario();

        $stm = $this->conex->prepare($sql);
        $stm->bindParam(':id', $id, PDO::PARAM_STR);

        try{
            $stm->execute();
        }catch (Exception $e){
            throw new Exception("Não foi possível selecionar o Usuário.");
        }

        $stm->setFetchMode(PDO::FETCH_CLASS, 'UsuarioVO');
        $vo = $stm->fetch(PDO::FETCH_CLASS);
        return $vo;

    }


    public function  selectOneByEmail( UsuarioVO $vo ){

        $sql = "SELECT * FROM usuarios WHERE email = :email";

        $email = $vo->getEmail();

        $stm = $this->conex->prepare($sql);
        $stm->bindParam(':email', $email, PDO::PARAM_STR);

        try{
            $stm->execute();
        }catch (Exception $e){
            throw new Exception("Não foi possível selecionar o Usuário.");
        }

        $stm->setFetchMode(PDO::FETCH_CLASS, 'UsuarioVO');
        $vo = $stm->fetch(PDO::FETCH_CLASS);
        return $vo;

    }


    public function remove( UsuarioVO $vo ){

        $id = $vo->getIdUsuario();

        try{
            $this->conex->exec("DELETE FROM usuarios WHERE id = '$id'");
        }catch( Exception $e ){
            throw new Exception("Não foi possível excluir o usuário.");
        }

    }

    public function update( UsuarioVO $vo ){

        $sql = "UPDATE usuarios
                SET
                    senha = :senha,
                    apelido =  :apelido,
                    idade =  :idade,
                    sexo =  :sexo,
                    gcmid = :gcmid,
                    idioma= :idioma,
                    pontuacao = :pontuacao,
                    arena = :arena
                WHERE id=:id";



//PARAMS
        $arrParams = array(
            ':id'=>$vo->getIdUsuario(),
            ':senha'=>$vo->getSenha(),
            ":apelido"=>$vo->getApelido(),
            ":idade"=>$vo->getIdade(),
            ":sexo"=>$vo->getSexo(),
            ':gcmid'=>$vo->getGcmid(),
            ':idioma'=>$vo->getIdioma(),
            ':arena'=>$vo->getArena(),
            ':pontuacao'=>$vo->getPontuacao()
        );


        try{
            $stm = $this->conex->prepare($sql);
            $stm->execute($arrParams);
        }catch (Exception $e){
            throw new Exception("Não foi possível atualizar o usuário.");
        }

    }


    public function updatePontuacao($idUser, $pontuacao){

        $sql = "UPDATE usuarios
                SET
                    pontuacao = pontuacao + :pontuacao
                WHERE id=:id";




//PARAMS
        $arrParams = array(
            ':id'=>$idUser,
            ':pontuacao'=>$pontuacao
        );

        try{
            $stm = $this->conex->prepare($sql);
            $stm->execute($arrParams);
        }catch (Exception $e){
            throw new Exception("Não foi possível atualizar o usuário.");
        }
    }

} 