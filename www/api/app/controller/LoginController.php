<?php
/**
 * Created by PhpStorm.
 * User: teoria
 * Date: 3/12/14
 * Time: 1:42 AM
 */

class LoginController extends Controller {

    public function logaUsuario() {

        $data = $this->request->post();

        $fachada = Fachada::getInstance();

        $vo = new UsuarioVO();

        $vo->setEmail($data['login']);
        $vo->setSenha(MD5($data['senha']));

        $usuario = $fachada->selectUsuario($vo);

        $retorno = $this->getResponse($usuario);
        echo $retorno;
    }

    public function logaUsuarioFB() {

        $data = json_decode($this->request->getBody());
        /*
        [id] => 789369499
        [name] => Rodrigo Carneiro
        [gender] => male
        */


        $data = (array)$data;
        $fachada = Fachada::getInstance();
        //$fachada = new Fachada();
        $vo = new UsuarioVO();
        $vo->setEmail($data['id']);
        $vo->setApelido($data['name']);
        $vo->setSexo($data['gender']);

        try {
            $usuario = $fachada->selectUsuarioByFB($vo);

            if (!$usuario) {
                $fachada->insertUsuarioByFB($vo);
                $usuario = $fachada->selectUsuarioByFB($vo);

            }
        } catch (Exception $e) {
            $retorno = $this->getResponse($usuario);
        }


        $retorno = $this->getResponse($usuario);


        echo $retorno;


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
            "nivel" => $this->getNivel($data),
            "arena" => $data->getArena()
        );


    }

    private function getNivel(UsuarioVO $data){
        $fachada = Fachada::getInstance();
        $vo = $fachada->getUltimoNivelByUser($data);

        return ( $vo )? $vo->getSentimento() : 0 ;
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


    public function cript() {

        $data = $this->request->get('cript');

        var_dump($data);

        $fachada = Fachada::getInstance();

        echo $fachada->cript(33);

        if($data){
            var_dump((int)$fachada->decript($data));
        }


    }

} 