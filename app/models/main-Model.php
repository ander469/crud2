<?php
	
	namespace app\models;
    use \PDO;

    if (file_exists(__DIR__."/../../config/server.php")) {
        require_once __DIR__."/../../config/server.php";
    }
 /*---------- Modelo conectar ----------*/
    class mainModel{
        private $server=DB_SERVER;
        private $db=DB_NAME;
        private $user=DB_USERNAME;
        private $pass=DB_PASS;

//function para conectar a la base de datos
       protected function conectar(){
            $conexion = new PDO ("mysql:host=". $this->server ." ;dbname=". $this->db, $this->user, $this->pass);
            $conexion->exec("set names utf8");
            return $conexion;
         }

    //consultas a la base de datos 
    protected function ejecutarConsulta($consulta){
        //conectar a la base de datos y preparar la consulta
        $sql=$this->conectar()->prepare($consulta);
        //ejecutar la consulta
        $sql->execute();
        //devolver el resultado de la consulta
        return $sql;

    }
     }
?>