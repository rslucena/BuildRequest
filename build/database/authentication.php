<?php

namespace Build\DataBase\Component;

use PDO;
use PDOException;

trait authentication
{

    //CONFIG
    private $username = "root";
    private $servername = "127.0.0.1";
    private $password = "";
    private $nameDb = 'psico';

    //on
    private function connect( $debug ) {

        $op = null;

        if($debug)
            $op = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
        try{
            return new PDO("mysql:host=$this->servername;dbname=$this->nameDb", $this->username, $this->password, $op);
        }catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    //close conn
    private function disconnect( $conn ){
        unset($conn);
    }

    //exQuery
    public function exQuery( $query, $parans, $debug ){

        $results = null;

        $conn = $this->connect( $debug );

        if(!is_object($conn) || empty($query))
            return null;

        try{
            $stmt = $conn->prepare($query);

            $results['query'] = $stmt->execute($parans);

            $results['vals'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if(empty($results['vals']))
                $results['exe'] = false;

        }catch ( PDOException $e ){
            $results['exe'] = false;
            $results['Message'] = $e->getMessage();
        }

        $this->disconnect( $conn );

        return $results;
    }

}