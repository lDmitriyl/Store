<?php


namespace core\base\model;

use core\base\exceptions\DbException;
use PDO;
use PDOException;

abstract class BaseModel
{

    protected $db;

    protected function connect(){

        $opt = array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES   => false);
        $host = HOST;
        $dbname = DB_NAME;
        $user= USER;
        $password = PASS;
        $charset = 'utf8';

        $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
        try {
            $this->db = new PDO($dsn, $user, $password, $opt);
        }catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
            die();
        }
    }

    function multi_insert($db, $table, $fields, $data){
        $i = 0;

        foreach($data as $d){

            $keys = [];

            foreach($fields as $fn){

                $key = ':' . $i . $fn;
                $keys[] = $key;
                $param[$key] = $d[$fn];
            }

            $stmt_data[] = '('.implode(',',$keys).')';
            $i++;
        }

        $stm_text = 'insert into '.$table.' ('.implode(',',$fields).') values '.implode(',',$stmt_data);
        $stmt = $db->prepare($stm_text);

        return $stmt->execute($param);
    }

    public function dataTieTable($array, $id, $fieldName){
        $data = [];
        foreach ($array as $arr){
            $data[] = [$fieldName[0] => $id, $fieldName[1] => $arr];
        }

        return $data;

    }

    public function getFiles($table, $column, $id){

        $stmt = $this->db->prepare("SELECT $column FROM $table WHERE id = ?");
        $stmt->execute([$id]);

        $result = $stmt->fetchAll();

        return $result;
    }

}