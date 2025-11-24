<?php


    class BBDD {
        /*
        Clase bbdd2 que implementa el patrón singleton para gestionar la conexión a la base de datos MySQL
        Por qué usar singleton para la conexión a la bbdd2?
        En una aplicación web, es común que múltiples partes del código necesiten acceder a la base de datos.
        Si cada parte del código crea su propia conexión a la base de datos, esto puede llevar a varios problemas:
        - Consumo excesivo de recursos: Cada conexión a la base de datos consume memoria y otros recursos del servidor.
          Tener múltiples conexiones abiertas simultáneamente puede agotar estos recursos rápidamente.
        - Gestión de conexiones: Manejar múltiples conexiones puede complicar el código y hacerlo más difícil de mantener.
        - Consistencia de datos: Si diferentes partes del código usan diferentes conexiones, puede ser difícil
            garantizar la consistencia de los datos.
        Usar el patrón singleton para la conexión a la base de datos ayuda a mitigar estos problemas al garantizar
        que solo haya una única instancia de la conexión a la base de datos en toda la aplicación
        
        Atributo estático que guarda la instancia única de la clase bbdd2
        Constructor privado para evitar crear nuevas instancias desde fuera de la clase
        Método público y estático que devuelve la instancia única de la clase bbdd2


        */
        private static $instancia = null;

        private mysqli $conexion; //Conexión a la BBDD, propiedad que contendrá el objeto mysqli

        private string $host     = '127.0.0.1';
        private string $usuario  = 'zonzamas';
        private string $password = 'Csas1234!';
        public static string $baseDatos = 'gestion_usuarios';

        //Constructor privado para evitar crear nuevas instancias desde fuera de la clase
        private function __construct(){
            /*
            Creación del objeto mysqli para la conexión a la BBDD
            */
            $this->conexion = new mysqli(
                 $this->host,
                 $this->usuario,
                 $this->password,
                 self::$baseDatos
            );

            //Si hay error de conexión, mostramos el error y detenemos la ejecución (die)
            if($this->conexion->connect_error){
                die("Error de conexión: " . $this->conexion->connect_error);
            }

            //Opcional: establecer el conjunto de caracteres a utf8mb4
            $this->conexion->set_charset("utf8mb4");
        }

        //Método público y estático que devuelve la instancia única de la clase bbdd2
        public static function getInstancia(): bbdd {

            //Si es null (no existe la instancia), la creamos y la devuelvo con return
            if (self::$instancia == null){
                self::$instancia = new bbdd();
            }

            return self::$instancia;
        }

        //Método que devuelve la conexión mysqli creada en el constructor, para usarla en otras clases, query por ejemplo
        public function getConexion(): mysqli {
            return $this->conexion;
        }
        //Evitar la clonación de la instancia poniéndolo vacío 
        public function __clone(){}
        
    }

    


    #$bbdd::$instancia;