<?php

/*
 * Note: For parameter names use this convention:
 * 
 * id = id
 * e = email
 * p = password
 * fn = first name
 * ln = last name
 * 
 * https://tools.ietf.org/html/rfc6570
 */

require_once '../params/Configuration.php';

use \PDO;

class Model
{
    
    public $errors = [];
    
    public function __construct($d = [])
    {
        foreach ($d as $k => $v) {
            $this->$k = $v;
        }
    }
    
    public function create($params)
    {
        if (isset($params['e']) &&
            isset($params['fn']) &&
            isset($params['ln']) &&
            isset($params['p'])
            ) {
            
            $sql = 'INSERT INTO users (email, first_name, last_name, passwd)
                    VALUES (:email, :first_name, :last_name, :passwd)';

            $db = static::connectDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':email', $params['e'], PDO::PARAM_STR);
            $stmt->bindValue(':first_name', $params['fn'], PDO::PARAM_STR);
            $stmt->bindValue(':last_name', $params['ln'], PDO::PARAM_STR);
            $stmt->bindValue(':passwd', $params['p'], PDO::PARAM_STR);

            try {
                $results = $stmt->execute();
            } catch (PDOException $e) {
                return $e->getMessage();
            }

            return $results;
        }
        
        return false;
    }

    public function read($id = NULL)
    {
        $sql = 'SELECT 
                    id_users, email, first_name, last_name, passwd
                FROM 
                    users AS u
                ';
        // Select only one entry
        if ($id) {
            $sql .= ' WHERE u.id_users = :id_users';
        }

        $db = static::connectDB();
        $stmt = $db->prepare($sql);
        
        if ($id) {
                $stmt->bindValue(':id_users', $id, PDO::PARAM_INT);
            }
        
        try {
            $results = $stmt->execute();
        } catch (PDOException $e) {
            return $e->getMessage();
        }

        // Return all or one entry depending on if id was passed.
        if ($stmt) {
            if ($id) {
                $fetch = $stmt->fetch(PDO::FETCH_ASSOC);
                return is_array($fetch) ? $fetch : false;
            }
            return $stmt->fetchAll();
        }

        return false;
    }

    public function delete($id)
    {   
        if (filter_var($id, FILTER_VALIDATE_INT)) {
            $sql = 'DELETE FROM users 
                    WHERE id_users = :id_users';

            $db = static::connectDB();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id_users', $id, PDO::PARAM_INT);

            try {
                $results = $stmt->execute();
            } catch (PDOException $e) {
                return $e->getMessage();
            }

            $deletedRows = $stmt->rowCount();

            return $deletedRows;
        }

        return false;
    }

    protected static function connectDB()
    {
        // Initial 
        static $db = null;

        if ($db === null) {

            $dsn = 
                'mysql:host=' . Configuration::DB_HOST .
                ';dbname=' . Configuration::DB_SCHEMA .
                ';port=' . Configuration::DB_MYSQL_PORT . 
                ';charset=utf8';

            try {
                $db = new PDO($dsn, Configuration::DB_USER, Configuration::DB_PASSWORD);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                return $e->getMessage();
            }
        }

        return $db;
    }
    
}
