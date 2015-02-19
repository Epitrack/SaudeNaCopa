<?php
/**
 * Created by PhpStorm.
 * User: teoria
 * Date: 3/12/14
 * Time: 3:09 AM
 */

class VO {

    protected $requires;
    function parse($data) {
        foreach ($data as $key => $value) {
            if( property_exists($this, $key ) ) {
                $this->$key = $value;
            }
        }
    }

    function parseArray() {
        $reflect = new ReflectionClass($this);
        $atributos = $reflect->getProperties();
        $array = array();
        foreach( $atributos as $attr ) {
            $nomeAtributo = $attr->name;
            $array[$nomeAtributo] = $this->$nomeAtributo;
        }
        return $array;
    }

    public function isValid(){

        for($i=0;$i<count($this->requires);$i++){
            $nome = $this->requires[$i];

            if(!isset($this->$nome)){
                return false;
            }
        }
        return true;
    }


} 