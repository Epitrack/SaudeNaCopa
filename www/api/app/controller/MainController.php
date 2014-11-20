<?php

Class MainController extends Controller {


    public function index() {

        $this->render(
            "index.php",
            array("title" => "Rest API", "texto" => "API rest desenvolvida para o aplicativo Saúde na Copa.")
        );

    }


    public function flashLink() {

        $this->render(
            "test",
            array("title" => "redi API", "name" => "Home")
        );
    }

    public function notFound() {

        $this->render("error.php", array(), 404);
    }
}

