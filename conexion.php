<?php
class Database {
    private static $instance = null; //guarda la instancia
    private $connection;

    
    private function __construct() {//constructor pirvado para que no se pueda crear otra instancia
        try {
            $this->connection = new PDO(
                "mysql:host=185.232.14.52;dbname=u760464709_23005353_bd;charset=utf8",
                "u760464709_23005353_usr",
                "O0h=DgE/"
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    // ceacion de  la   instancia 
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }
}


?>
