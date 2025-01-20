<?php

use FTP\Connection as FTPConnection;

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
        static public function getColumnData($table){
            $database=Connection::infoDatabase()["database"];
            return Connection::connect()
                   ->query("SELECT COLUMN_NAME AS item FROM information_schema.columns WHERE table_schema='$database' AND table_name='$table'")
                   ->fetchAll(PDO::FETCH_OBJ);
        }
    }