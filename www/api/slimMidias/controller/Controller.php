<?php

Class Controller extends \Slim\Slim
{
	protected $data;

	public function __construct()
	{

        $settings = array(
            'view' => new \Slim\Views\Twig(),
            'debug' => true,
            'templates.path' => '../app/views'

        );

		parent::__construct($settings);
	}


    public function render($name, $data = array(), $status = null) {

        if (strpos($name, ".php") === false) {
            $name = $name . ".php";
        }

        if ( isset( $_SESSION['slim.flash'] ) ) {
            $data['flash'] = $_SESSION['slim.flash'];
            $_SESSION['slim.flash'] = null;
        }
        $data['path'] = $_SESSION['path'];

        parent::render($name, $data, $status);
    }


    public function redirect($target = '/') {

        header("Location: $target");
    }


    public function flash($key, $value) {

        if (! isset( $_SESSION['slim.flash'] ) ) {
            $_SESSION['slim.flash'] = array();
        }
        $_SESSION['slim.flash'][$key] = $value;


    }




}

