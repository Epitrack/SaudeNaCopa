<?php

Class Fachada extends FachadaBase {


    /* --------- Usuário --------- */
    public function insertUsuario(UsuarioVO $vo) {

        $dao = new UsuarioDAO();

        try {
            return $dao->insert($vo);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function insertUsuarioByFB(UsuarioVO $vo) {

        $dao = new UsuarioDAO();

        try {
            return $dao->insertFB($vo);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }


    public function countUsuario() {

        $dao = new UsuarioDAO();

        try {
            return $dao->countAll();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }


    public function selectUsuario(UsuarioVO $vo) {

        $dao = new UsuarioDAO();

        try {
            return $dao->selectOne($vo);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function selectUsuarioByFB(UsuarioVO $vo) {

        $dao = new UsuarioDAO();

        try {
            return $dao->selectOneFB($vo);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function selectOneByID(UsuarioVO $vo) {

        $dao = new UsuarioDAO();

        try {
            return $dao->selectOneByID($vo);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function selectOneByEmail(UsuarioVO $vo) {

        $dao = new UsuarioDAO();

        try {
            return $dao->selectOneByEmail($vo);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }


    public function updateUser(UsuarioVO $vo) {

        $dao = new UsuarioDAO();

        try {
            return $dao->update($vo);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /////// D Ú V I D A S //////////


    public function insereDuvida(DuvidaVO $vo) {

        $dao = new DuvidaDAO();

        try {
            return $dao->insert($vo);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }


    public function insereProblema(ProblemaVO $vo) {

        $dao = new ProblemaDAO();

        try {
            return $dao->insert($vo);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }


    ////// SENTIMENTO /////


    public function insereSentimento(SentimentoVO $vo) {

        $dao = new SentimentoDAO();

        try {
            return $dao->insert($vo);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getUltimoSentimento(SentimentoVO $vo){
        $dao = new SentimentoDAO();

        try {
            return $dao->getUltimoNivelByUser($vo);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }


    public function getUltimoNivelByUser(UsuarioVO $vo) {

        $dao = new SentimentoDAO();

        try {
            return $dao->getUltimoNivelByUser($vo);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }


    public function getCalendario(UsuarioVO $vo) {

        $dao = new SentimentoDAO();

        try {
            return $dao->getCalendario($vo);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }


    /////// L O G ///

    public function insereLogGame(LogGameVO $vo) {

        $dao = new LogGameDAO();
        $daoUser = new UsuarioDAO();

        try {

            $daoUser->updatePontuacao($vo->getUsuariosId(), $vo->getPontuacao());
            return $dao->insert($vo);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }


    public function removeLogGame(LogGameVO $vo) {

        $dao = new LogGameDAO();

        try {
            return $dao->remove($vo);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }


    public function getUltimoInsertByUser(UsuarioVO $vo) {

        $dao = new LogGameDAO();

        try {
            return $dao->getUltimoInsertByUser($vo);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    ////////// ///////////////////

    public function getDatasJogo() {

        $datas = array(
            "01_06" => 1,
            "02_06" => 1,
            "03_06" => 1,
            "04_06" => 1,
            "05_06" => 1,
            "06_06" => 1,
            "07_06" => 1,
            "08_06" => 1,
            "09_06" => 1,
            "10_06" => 1,
            "11_06" => 1,
            "27_06" => 1,
            "29_06" => 1,
            "30_06" => 1,

            "02_07" => 1,
            "03_07" => 1,
            "06_07" => 1,
            "07_07" => 1,
            "10_07" => 1,
            "11_07" => 1,
            "14_07" => 1,
            "15_07" => 1,
            "16_07" => 1,
            "17_07" => 1,
            "18_07" => 1,
            "19_07" => 1,
            "20_07" => 1,
            "21_07" => 1,
            "22_07" => 1,
            "23_07" => 1,
            "24_07" => 1,
            "25_07" => 1,
            "26_07" => 1,
            "27_07" => 1,
            "28_07" => 1,
            "29_07" => 1,
            "30_07" => 1,


            "12_06" => 3,
            "13_06" => 3,
            "14_06" => 3,
            "15_06" => 3,
            "16_06" => 3,
            "17_06" => 3,
            "18_06" => 3,
            "19_06" => 3,
            "20_06" => 3,
            "21_06" => 3,
            "22_06" => 3,
            "23_06" => 3,
            "24_06" => 3,
            "25_06" => 3,
            "26_06" => 3,
            "28_06" => 3,

            "01_07" => 3,
            "04_07" => 3,
            "05_07" => 3,
            "08_07" => 3,
            "09_07" => 3,
            "12_07" => 3,
            "13_07" => 3

        );

        return $datas;
    }


    ////// E M A I L //////


    public function sendEmail($to, $reply, $subject, $message) {

        // $to      = 'nobody@example.com';
        // $subject = 'the subject';
        // $message = 'hello';
        //$headers = 'From: app@saudenacopa.epitrack.com.br'. "\r\n" .

        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: saudenacopa@epitrack.com.br' . "\r\n" .
            'Reply-To:  ' . $reply . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        return mail($to, $subject, $message, $headers);
    }


    ///// CRIPTOGRAFIA /////
    public function getKey() {

        return 'ocdEggJlrt_epitrack';

    }

    public function cript($data) {

        $key = $this->getKey();

        $encrypted = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $data, MCRYPT_MODE_ECB);

        return base64_encode($encrypted);
    }

    public function decript($criptData) {

        $key = $this->getKey();

        $data = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, base64_decode($criptData), MCRYPT_MODE_ECB);

        return $data;

    }


}

?>