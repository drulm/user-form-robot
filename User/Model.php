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
