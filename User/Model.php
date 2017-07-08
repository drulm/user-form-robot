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
 * Reference: https://tools.ietf.org/html/rfc6570
 */

require_once '../params/Configuration.php';

//use \PDO;

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
            
            $passwordHash = password_hash($params['p'], PASSWORD_DEFAULT);
            
            $sql = 'INSERT INTO users (email, first_name, last_name, passwd)
                    VALUES (:email, :first_name, :last_name, :passwd)';

            $db = static::connectDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':email', $params['e'], PDO::PARAM_STR);
            $stmt->bindValue(':first_name', $params['fn'], PDO::PARAM_STR);
            $stmt->bindValue(':last_name', $params['ln'], PDO::PARAM_STR);
            $stmt->bindValue(':passwd', $passwordHash, PDO::PARAM_STR);

            try {
                $results = $stmt->execute();
            } catch (PDOException $e) {
                return $e->getMessage();
            }

            return $results;
        }
        
        return false;
    }
    
    public function update($params)
    {
        echo '<pre>';
        echo "update PARAMS: "; var_dump($params);
        echo '</pre>';
        
        if (
            isset($params['id']) &&
                (
                    isset($params['e']) ||
                    isset($params['fn']) ||
                    isset($params['ln']) ||
                    isset($params['p'])
                )
            ) {
            
            $q = [];
            if (isset($params['e'])) {
                $q[] = "email = :email";
            }
            if (isset($params['fn'])) {
                $q[] = "first_name = :first_name";
            }
            if (isset($params['ln'])) {
                $q[] = "last_name = :last_name";
            }
            if (isset($params['p'])) {
                $q[] = "passwd = :passwd";
            }
            $sql = 'UPDATE users SET ' . implode(", ", $q) . ' WHERE id_users = :id_users';
            
            echo '<pre>';
            echo "Update SQL: "; var_dump($sql);
            echo '</pre>';

            $db = static::connectDB();
            $stmt = $db->prepare($sql);

            if (isset($params['e'])) {
                $stmt->bindValue(':email', $params['e'], PDO::PARAM_STR);
            }
            if (isset($params['fn'])) {
                $stmt->bindValue(':first_name', $params['fn'], PDO::PARAM_STR);
            }
            if (isset($params['ln'])) {
                $stmt->bindValue(':last_name', $params['ln'], PDO::PARAM_STR);
            }
            if (isset($params['p'])) {
                $passwordHash = password_hash($params['p'], PASSWORD_DEFAULT);
                $stmt->bindValue(':passwd', $passwordHash, PDO::PARAM_STR);
            }
            $stmt->bindValue(':id_users', $params['id'], PDO::PARAM_INT);

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
