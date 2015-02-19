<?php

Class FachadaBase{
    protected  static $instance;
    protected function __construct(){}


    final public static function getInstance() {
        if (!isset(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }



}

?>