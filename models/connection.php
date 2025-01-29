<?php

    

    class Connection{
        /*===============================================================
        Información de la Base de datos
        =================================================================*/

        static public function infoDatabase(){
            $infoDB=array(
                "database"=>"database1",
                "user"=>"root",
                "pass"=>"admin"
            );
            return $infoDB;
        }
        /*===============================================================
        Conexión de la Base de datos
        =================================================================*/
        static public function connect(){
            try {
                $link=new PDO(
                        "mysql:host=localhost;dbname=".Connection::infoDatabase()["database"],
                        Connection::infoDatabase()["user"],
                        Connection::infoDatabase()["pass"],

                );
                $link->exec("set names utf8");
            } 
            catch (PDOException $e) {
                die("Error: ".$e->getMessage());
            }
            return $link;
        }
        /*===============================================================
        Validar existencia de una tabla en la BD
        =================================================================*/
        static public function getColumnData($table,$columns){
            /*===============================================================
            Traer el nombre de la BD
            =================================================================*/
            $database=Connection::infoDatabase()["database"];
            
            /*===============================================================
            Traer Todas las columnas de una tabla
            =================================================================*/
            $validate= Connection::connect()
                   ->query("SELECT COLUMN_NAME AS item FROM information_schema.columns WHERE table_schema='$database' AND table_name='$table'")
                   ->fetchAll(PDO::FETCH_OBJ);
            
            /*===============================================================
            Validamos la existencia de la tabla
            =================================================================*/
            if (empty($validate)){
                return null;
            }
            else{
                /*===============================================================
                Ajuste a solicitud de columnas globales
                =================================================================*/
                if ($columns[0]=="*"){
                    array_shift($columns);
                }
                /*===============================================================
                Validamos la existencia de columnas
                =================================================================*/
                $sum=0;
                foreach ($validate as $key => $value) {
                    
                    $sum+=(in_array($value->item,$columns));                    
                        
                }
                return $sum==count($columns) ? $validate:null;
                
            }

        }

        /*===============================================================
        Generar Token de autenticación
        =================================================================*/
        static public function jwt($id,$email){
           $time=time();
           $token=array(
                "iat"=>$time,  //Tiempo que inicia el token
                "exp"=>$time+(60*60*24),
                "data"=>[
                    "id"=>$id,
                    "email"=>$email,
                ]

           );
           
           return $token;
        }
    }