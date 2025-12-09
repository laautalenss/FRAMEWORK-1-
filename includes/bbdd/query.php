<?php


    class Query{


        private mysqli $conexion; //Guarda la conexión a la BBDD que obtiene de la clase singleton bbdd2
        /*
        Almacena el resultado de $this->conexion->query($sql);  para luego poder recuperar los registros
        SELECT: será un objeto mysqli_result
        INSERT/UPDATE/DELETE: será true o false
        */
        public $resultado = null; 
        /*
        Número de filas/rows obtenidas en la consulta SELECT o número de filas afectadas en INSERT/UPDATE/DELETE
        */
        public int $total = 0; 



        //Constructor que recibe la consulta SQL
        public function __construct(string $sql){

            $this->conexion = BBDD::getInstancia()->getConexion(); //Obtenemos la conexión a la bbdd2

            $this->resultado = $this->conexion->query($sql); //Ejecutamos la consulta

            //Si falla la consulta, lanzamos una excepción
            if($this->resultado === false){
                throw new Exception("Error en la consulta" . $this->conexion->error);
            }

            //Si es un Select, guardamos el número de registros

            if(gettype($this->resultado) == "boolean")
                $this->total = 1;
            elseif($this->resultado instanceof mysqli_result)
                $this->total = $this->resultado->num_rows;
            else
                $this->total = $this->resultado->affected_rows;

            return $this;
        }

        /*
        Si $this->resultado es un objeto mysqli_result (consulta SELECT), devuelve el siguiente registro como un array asociativo
        Si no hay más registros (es decir, no es un objeto mysqli_result), libera el resultado y devuelve null
        */
        public function recuperar(){

            if (!$this->resultado instanceof mysqli_result){
                return null;
            }

            /*
            Obtenemos el siguiente registro como un array asociativo (fetch_assoc)
            Si no hay más registros, liberamos el resultado y lo ponemos a null para evitar futuros erroes 
            */
            $registro = $this->resultado->fetch_assoc();
            if ($registro == null){
                $this->resultado->free();
                $this->resultado = null;
            }

            return $registro;
            
        }


        //Método que devuelve el id generado en la última consulta INSERT
        public function getLastInsertId(){
            return $this->conexion->insert_id;
        }


    }


    /*
    $query = new Query("
        SELECT *
        FROM   usuarios
        WHERE nombre = 'Jaime'
    ");

    $registro = $query->recuperar();
    
    $query->total;
    */