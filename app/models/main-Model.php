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
    //evitar inyeciones SQL
    //palabras que no quiero permitir en mi sistema crud;
    protected function limpiarCadena($cadena){
        $palabras=["<script>","</script>","<script src","<script type=","SELECT * FROM","SELECT "," SELECT ","DELETE FROM",
            "INSERT INTO","DROP TABLE","DROP DATABASE","TRUNCATE TABLE","SHOW TABLES","SHOW DATABASES","<?php","?>",
                "--","^","<",">","==","=",";","::"];
         //eliminar espacios en blanco al inicio y al final de la cadena    
        $cadena=trim($cadena);
        //eliminar las barras invertidas
        $cadena=stripslashes($cadena);
        //se utiliza para para recorrer el array de palabras y eliminar cada una de ellas de la cadena
        foreach ($palabras as $palabra) {
            //se utiliza para eliminar cada una de las palabras del array de la cadena utilizando la función str_ireplace.
            $cadena=str_ireplace($palabra, "", $cadena);
            
        }
        //
        $cadena=trim($cadena);
        $cadena=stripslashes($cadena);
        //devolver la cadena limpia
        return $cadena;
    }
    //verificar si el string coincide con el filtro de expresion regular
    protected function verificarDatos($filtro, $cadena){
        if(preg_match("/^".$filtro."$/",$cadena)){
            return false;
        }else{
            return true;
        }
    }

    //funcion para guardar o insetar datos en la base de datos.
    protected function guardarDatos($tabla, $datos){
    $query="INSERT INTO $tabla (";
    $c=0;
        foreach($datos as $clave){
            if($c>=1) {$query.=",";}
            $query.=$clave["campo_nombre"];
            $c++;
        }
    $query.=") VALUES (";
    $c=0;
        foreach($datos as $clave){
            if($c>=1) {$query.=",";}
            $query.="'".$clave["campo_marcador"]."'";
            $c++;
        }
    $query.=")";
    //consulta para poder ejecutar la consulta de insercion a la base de datos
    $sql=$this->conectar()->prepare($query);
    foreach($datos as $clave){
        $sql->bindParam(":".$clave["campo_marcador"], $clave["campo_valor"]);
    }
    //ejecuta la consulta de insercion a la base de datos
    $sql->execute();
    //devolver el resultado de la consulta de insercion a la base de datos
    return $sql;
    }
    //select para mostrar datos de la base de datos
    public function seleccionarDatos($tipo, $tabla, $campo, $id){
        $tipo=$this->limpiarCadena($tipo);
        $tabla=$this->limpiarCadena($tabla);
        $campo=$this->limpiarCadena($campo);
        $id=$this->limpiarCadena($id);
    //seleccion de datos para un usuario en especifico
        if ($tipo=="Unico") {
            $sql=$this->conectar()->prepare("SELECT * FROM $tabla WHERE $campo='$id'");
            $sql->bindParam(":ID", $id);
    //cuando sea una consulta normal sin condicion
        }elseif($tipo=="normal"){
            $sql=$this->conectar()->prepare("SELECT $campo FROM $tabla");
        }
        //ejecutar consulta
        $sql->execute();
        //devolver el valor de la consulta
        return $sql;
    }
    
    //function para actualizar datos en la base de datos
    protected function actualizarDatos($tabla, $datos, $condicion){
        $query="UPDATE $tabla SET";

        $c=0;
        foreach($datos as $clave){
            if($c>=1) {$query.=",";}
            $query.=$clave["campo_nombre"]."=". $clave["campo_marcador"];
            $c++;
    }

    $query.=" WHERE " .$condicion["condicion_campo"]."=".$condicion["condicion_marcador"];

    //funcion conectar
    $sql=$this->conectar()->prepare($query);

        foreach($datos as $clave){
            //bindParam cambia el marcador por su valor real
            $sql->bindParam($clave["condicion_marcador"],$clave["campo_valor"]);
        }
        $sql->bindParam($condicion["condicion_marcador"],$condicion["condicion_valor"]);
       //ejecutar consulta
        $sql->execute();
        //devolver el valor de la consulta
        return $sql;
    }
    //function para eliminar registros
    protected function eliminarRegistro($tabla, $campo, $id){
        $sql=$this->conectar()->prepare("DELETE FROM $tabla WHERE $campo=:id");
        //
        $sql->bindParam(":id",$id);
        //ejecutar consulta
        $sql->execute();
        //devolver el valor de la consulta
        return $sql;
    }

    //paginador de tablas, sirve para navegar entre las tablas de registros de usuarios
    protected function paginadorTablas($pagina, $numeroPaginas, $url, $botones){
    //generar la botonera principal
    $tabla='<nav class="pagination is-centered is-rounded" role="navigation" aria-label="pagination">';
    //desanilitar el volver atras desde la pagina 1 
    if ($pagina<=1) {
            $tabla.='
                <a class="pagination-previous is-disabled" disabled>Anterior</a>
                <ul class="pagination-list">
        ';
    //volver una pagina anterior cada vez que el usuario lo requiera     
    } else {
            $tabla.='
                <a class="pagination-previous" href="'.$url.($pagina-1).'/">Anterior</a>
                <ul class="pagination-list">
                <li><a class="pagination-link" href="'.$url.'1/">1</a></li>
                <li><span class="pagination-ellipsis">&hellip;<spam></li>
            ';
    }
    //ci=Contador de interacion
    $ci=0;
    for(){
        
    }


    }
}
?>