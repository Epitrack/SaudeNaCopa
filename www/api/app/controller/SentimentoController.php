<?php

Class SentimentoController extends Controller {


    public function enviaSentimento() {

        $data = $this->request->post();

        $fachada = Fachada::getInstance();

        $usuario_id = (int)$fachada->decript($data["usuario_id"]);

        if( is_nan($usuario_id)){
            throw new InvalidArgumentException;
        }

        $latitude   =  $data["latitude"];
        $longitude   =  $data["longitude"];
        $sentimento   =  $data["sentimento"];


        $campo1   =  $data["campo1"];
        $campo2   =  $data["campo2"];
        $campo3   =  $data["campo3"];
        $campo4   =  $data["campo4"];
        $campo5   =  $data["campo5"];
        $campo6   =  $data["campo6"];
        $campo7   =  $data["campo7"];
        $campo8   =  $data["campo8"];
        $campo9   =  $data["campo9"];
        $campo10  =  $data["campo10"];
        $campo11  =  $data["campo11"];
        $campo12  =  $data["campo12"];



        $vo = new SentimentoVO();

        $vo->setUsuarioId($usuario_id);
        $vo->setLatitude($latitude);
        $vo->setLongitude($longitude);
        $vo->setSentimento($sentimento);

        $vo->setCampo1($campo1);
        $vo->setCampo2($campo2);
        $vo->setCampo3($campo3);
        $vo->setCampo4($campo4);
        $vo->setCampo5($campo5);
        $vo->setCampo6($campo6);
        $vo->setCampo7($campo7);
        $vo->setCampo8($campo8);
        $vo->setCampo9($campo9);
        $vo->setCampo10($campo10);
        $vo->setCampo11($campo11);
        $vo->setCampo12($campo12);

        $user = new UsuarioVO();
        $user->setIdUsuario($usuario_id);

        $result = $fachada->insereSentimento($vo);




        if( $this->validaInsertSentimento($user) ){

            $datas = $fachada->getDatasJogo();

            $timeZone = new DateTimeZone('America/Recife');

            $dateAtual = new DateTime();
           // $dateAtual->setTimezone($timeZone);

            $ponto = ($datas[ $dateAtual->format("d_m") ])? $datas[ $dateAtual->format("d_m") ] : 1 ;


            //  if ($usuario && $usuario->getIdUsuario()) {
            $logVO = new LogGameVO();
            $logVO->setAcao("INSERT SENTIM");
            $logVO->setUsuariosId($usuario_id);
            $logVO->setPontuacao($ponto);

            $fachada->insereLogGame($logVO);
            //   }

            $vo = new UsuarioVO();
            $vo->setIdUsuario($usuario_id);

            $usuario = $fachada->selectOneByID($vo);
            $retorno = $this->getResponse($usuario);
            echo $retorno;
        }else{


            $retorno = $this->getResponseErro($user);
            echo $retorno;
        }



    }

    public function mudouSentimento(UsuarioVO $vo , SentimentoVO $sentimentoVO){
        $fachada = Fachada::getInstance();
        $sentVO = $fachada->getUltimoNivelByUser($vo);

        if(!$sentVO){
            return true;
        }else{
            $sentimento = $sentVO->getSentimento();
            $ultimoSentimento = $sentimentoVO->getSentimento();

            //echo $sentimento . " - " . $ultimoSentimento;
           // die();
            return $sentimento != $ultimoSentimento;
        }

    }

    public function validaInsertSentimento(UsuarioVO $vo) {
        //return true;
        $fachada = Fachada::getInstance();
        $sentVO = $fachada->getUltimoInsertByUser($vo);
        $boo = true;


        if( $sentVO ){
            $dataUltima = $sentVO->getDataHora();

            //$timeZone = new DateTimeZone('America/Recife');

            $dateAtual = new DateTime();
            //$dateAtual->setTimezone($timeZone);

            $dateantiga = new DateTime($dataUltima);
            // $dateantiga->setTimezone($timeZone);

            $tempoMinimo = 2;//( $sentVO->isBad() )? 2 : 4 ;

            $tempo = $this->differenceInhours($dateAtual,$dateantiga);

            $boo = $tempo > $tempoMinimo;
        }

        return $boo;
    }

    public function differenceInhours (DateTime $firstDate, DateTime  $secondDate){
        $firstDateTimeStamp = $firstDate->format("U");
        $secondDateTimeStamp = $secondDate->format("U");
        $rv = round( (100* (($firstDateTimeStamp - $secondDateTimeStamp))/3600) )/100;
        return $rv;
    }

    public function getResponseErro(UsuarioVO $vo) {

        $fachada = Fachada::getInstance();
        //$sentVO = $fachada->getUltimoNivelByUser($vo);

        $sentVO = $fachada->getUltimoInsertByUser($vo);

      //  $dataUltima = $sentVO->getDataCadastro();

        $dataUltima = $sentVO->getDataHora();

       // $timeZone = new DateTimeZone('America/Recife');

        $dateAtual = new DateTime();
        //$dateAtual->setTimezone($timeZone);

        $dateantiga = new DateTime($dataUltima);
       // $dateantiga->setTimezone($timeZone);


        $tempoMinimo = 2;//( $sentVO->isBad() )? 2 : 4 ;

        $tempo = $this->differenceInhours($dateAtual,$dateantiga);

        $tempo = $tempoMinimo - $tempo;


        //$tempo = 124325;

        $retorno = array(

            "status" => false,
            "tempo" => "$tempo",
            "erro" => 1
        );
        echo json_encode($retorno);
    }
    public function enviaSentimento2() {

        $retorno = array(

            "status" => false,
            "tempo" => "12341451134",
            "erro" => 1
        );
        echo json_encode($retorno);
    }

    private function getResponse($usuario) {

        $retorno = array(

            "status" => ($usuario != false),
            "usuario" => ($usuario != false) ? $this->getUser($usuario) : ''
        );
        return json_encode($retorno);
    }



    private function getUser(UsuarioVO $data) {


        $fachada = Fachada::getInstance();

        return array(
            "userID" => $fachada->cript( $data->getIdUsuario() ),
            "nome" => $data->getApelido(),
            "sexo" => $data->getSexo(),
            "pontos" => $data->getPontuacao(),
            "engajamento" => $this->getEngajamento($data),
            "categoria" => $this->getCategoria($data),
            "arena"=>$data->getArena()
        );
    }

    private function getEngajamento(UsuarioVO $data) {

        $porcentagem = $data->getPontuacao() / $data->getTotalPontosPossiveis() * 100;
        if ($porcentagem > 100) {
            $porcentagem = 100;
        }
        return $porcentagem;
    }

    private function getCategoria(UsuarioVO $data) {

        /*

            ---------
            Dente de leite 0 - 5

            Infantil 5 - 15

            Juvenil 15 - 30

            Junior 30 - 50

            Profissional 50 - 80

            Premio  80 - 100

         */

        $porcentagem = $this->getEngajamento($data);
        $categoria = 0;

        if ($porcentagem <= 5) {
            $categoria = 0;
        } elseif ($porcentagem > 5 && $porcentagem <= 15) {
            $categoria = 1;
        } elseif ($porcentagem > 15 && $porcentagem <= 30) {
            $categoria = 2;
        } elseif ($porcentagem > 30 && $porcentagem <= 50) {
            $categoria = 3;
        } elseif ($porcentagem > 50 && $porcentagem <= 80) {
            $categoria = 4;
        } elseif ($porcentagem > 80) {
            $categoria = 5;
        }

        return $categoria;
    }

}