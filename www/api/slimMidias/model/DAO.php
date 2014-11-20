<?php
/**
 * Created by PhpStorm.
 * User: teoria
 * Date: 3/12/14
 * Time: 3:10 AM
 */

class DAO {
    protected  $conex;
    function __construct(){
        $this->conex = Conex::getInstance()->getPDO();

    }

} 