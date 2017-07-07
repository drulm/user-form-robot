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

class Model {
    
    public $errors = [];
    
    public function __construct($d = [])
    {
        foreach ($d as $k => $v) {
            $this->$k = $v;
        }
    }

    public function read($id = NULL)
    {
        $sql = 'SELECT 
                    id_users, email, first_name, last_name, passwd
                FROM 
                    users as u
                ';
        // Select only one entry
        if ($id) {
            $sql .= ' WHERE u.id_users = :id_users';
        }

        // Prepare sql and bind blog id if used.
        $db = static::connectDB();
        $stmt = $db->prepare($sql);

        if ($id) {
            $stmt->bindValue(':id_users', $id, PDO::PARAM_INT);
        }

        $stmt->execute();

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

            $db = new PDO($dsn, Configuration::DB_USER, Configuration::DB_PASSWORD);

            // Error should throw exception.
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return $db;
    }
    
}
