<?php
    require_once 'connection.php';
    require_once 'get.model.php';
    class  DeleteModel{

        static public function deleteData($table,$id,$nameId){
            /*===============================================================
            Validar el id
            =================================================================*/
            $response=GetModel::getDataFilter($table,$nameId,$nameId,$id,null,null,null,null);
            if (empty($response)){
              
              return null;
            }
            /*===============================================================
            Petición Delete para eliminar los datos de forma dinámica
            =================================================================*/
          
            $sql="DELETE FROM $table  WHERE $nameId = :$nameId";
            $link=Connection::connect();
            $stmt=$link->prepare($sql);
            $stmt->bindParam(":".$nameId,$id,PDO::PARAM_STR);
            if ($stmt->execute()){
                $response=array(
                    
                  "comment"=>"The process was successful"

                );
                return $response;
           }
           else{
                return $link->errorInfo();
           }
        }
    }