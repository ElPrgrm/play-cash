<?php
class Usuario {
    public $id;
    public $correo;
    public $nombreUsuario;
    public $contrasena;
    public $rol;//definicion de roles admin o usuario

    public function __construct($correo, $nombreUsuario, $contrasena,  $rol = 'usuario') {
        $this->correo = $correo;
        $this->nombreUsuario = $nombreUsuario;
         $this->contrasena = $contrasena;
         $this->rol = $rol;
         
       // $this->contrasena = password_hash($contrasena, PASSWORD_DEFAULT); se usara para encriptar las contraseñas despues
    }
}
?>
