<?php

class Sql 
{
    public function dbConnect()
    {
        try {
            $option = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_MULTI_STATEMENTS => false,
            );
            $pdo = new PDO('mysql:charset=UTF8;dbname=employee;host=mysql', 'root', 'password', $option);
        } catch (PDOException $e) {
            echo 'error:' . $e->getMessage();
        }
    
        return $pdo;
    }

    public function select($sql, $params)
    {
        $pdo = $this->dbConnect();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $res = $stmt->fetch();
        return $res;
    }

    public function plural($sql, $params)
    {
        $pdo = $this->dbConnect();
        $stmt = $pdo->prepare($sql);
        $res = $stmt->execute($params);
        return $res;
    }

}