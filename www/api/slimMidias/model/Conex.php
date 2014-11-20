<?php
class Conex{

    private static $instance;
    private $pdo;

    private function __construct()
    {

       require("../settings_db.php");

        try{
            $this->pdo = new PDO($engine.':dbname='.$database.';host='.$host, $user, $pass)or print (mysql_error());
        }catch(PDOException $e){
            echo 'Error: '.$e->getMessage();
        }
        
    }

    public static function getInstance(){
        if(!isset(self::$instance)){
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }

    public function getPDO(){
        return $this->pdo;
    }

}

?>