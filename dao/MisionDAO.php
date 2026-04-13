<?php
require_once __DIR__ . '/../conexion.php';

class MisionDAO {

    private $db;

    public function __construct() {

        if (class_exists('Database')) {
            $this->db = Database::getInstance()->getConnection();
        } elseif (class_exists('BD_PDO')) {
            $this->db = BD_PDO::getInstance()->getConnection();
        } else {
            global $db;
            $this->db = $db;
        }
    }

   // LISTAR MISIÓNES
   
    public function listarMisiones() {

    $sql = "SELECT m.*, v.nombreJuego
            FROM misiones m
            INNER JOIN videojuegos v ON v.id_juego = m.id_juego
            WHERE m.estado != 'cobrada'";
           

    $stmt = $this->db->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    // OBTENER POR ID
  
    public function obtenerPorId($id) {

        $sql = "SELECT * FROM misiones WHERE id_mision = :id LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // CREAR MISIÓN

    public function crearMision($nombre, $descripcion, $recompensa, $id_juego) {
        $sql = "INSERT INTO misiones (nombreMision, descripcion, recompensa, id_juego, estado)
        VALUES (:nombre, :descripcion, :recompensa, :id_juego, 'pendiente')";


        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':nombre'      => $nombre,
            ':descripcion' => $descripcion,
            ':recompensa'  => $recompensa,
            ':id_juego'    => $id_juego
        ]);
    }

       
    // ELIMINAR MISIÓN
  
    public function eliminar($id) {

        $sql = "DELETE FROM misiones WHERE id_mision = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([':id' => $id]);
    }

 
    // MODIFICAR MISIÓ

    public function modificar($id, $nombre, $descripcion, $recompensa, $id_juego) {

        $sql = "UPDATE misiones
                SET nombreMision = :nombre,
                    descripcion  = :descripcion,
                    recompensa   = :recompensa,
                    id_juego     = :id_juego
                WHERE id_mision = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':nombre'      => $nombre,
            ':descripcion' => $descripcion,
            ':recompensa'  => $recompensa,
            ':id_juego'    => $id_juego,
            ':id'          => $id
        ]);
    }

        //COBRADA

    public function marcarComoCobrada($id_mision) {
    $sql = "UPDATE misiones SET estado = 'cobrada' WHERE id_mision = :id";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute([':id' => $id_mision]);
}

public function obtenerCobradas() {
    $sql = "SELECT m.*, v.nombreJuego
            FROM misiones m
            INNER JOIN videojuegos v ON v.id_juego = m.id_juego
            WHERE m.estado = 'cobrada'";
    
    $stmt = $this->db->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


}
?>
