<?php
class Plantilla{
    static $instancia = null;
    public static function aplicar(){
        if(self::$instancia == null){
            self::$instancia = new Plantilla();
        }
        return self::$instancia;
    }
    public function __construct(){
        require("Plantilla/Header.php");
    }
    public function __destruct(){
        require("Plantilla/Footer.php");
    }
}
?>