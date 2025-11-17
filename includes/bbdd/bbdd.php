<?php

    class BBDD{
        private static $instancia = null;

        private mysqli $conexion;

        private string $host     = '127.0.0.1';
        private string $usuario  = 'zonzamas';
        private string $password = 'Csas1234!';
        private string $baseDatos = 'gestion_usuarios';

        private function __construct()
        {
            $this->conexion = new mysqli(
                 $this->host
                ,$this->usuario
                ,$this->password
                ,$this->baseDatos
            );

            if($this->conexion->connect_error){
                die("Error de conexión: " . $this->conexion->connect_error);
            }

            $this->conexion->set_charset("utf8mb4");
        }

        public static function getInstancia(): BBDD {

            if (self::$instancia == null){
                self::$instancia = new BBDD();
            }

            return self::$instancia;
        }

        public function getConexion(): mysqli {
            return $this->conexion;
        }

        public function __clone(){}
        
    }

    


    #$bbdd::$instancia;