<?php

//require_once '../params/Configuration.php';
//require_once 'params/Configuration.php';
require_once(dirname(__FILE__) . '/../params/Configuration.php');

//use \PDO;

/**
 *
 * User model
 *
 * PHP version 7.0
 * 
 * @TODO namespace
 * 
 * Note: For parameter names use these conventions:
 * 
 * id = id
 * e = email
 * p = password
 * fn = first name
 * ln = last name
 * 
 * Reference on URI paths: https://tools.ietf.org/html/rfc6570
 * 
 */
class Model
{
    
    /**
     * Error message array.
     *
     * @var array
     */
    public $errorArray = [];
    
    /**
     * Class constructor
     *
     * @param array $d can set optional initial property values.
     *
     * @return void
     */
    public function __construct($d = [])
    {
        foreach ($d as $k => $v) {
            $this->$k = $v;
        }
    }
    
    /**
     * Create a new user in the database
     * 
     * @param aray $params      Contains URL parameters. No id needed, id auto generated by MySQL.
     *
     * @return boolean          If create worked correctly.                      
     */
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
            if ($db instanceof PDO) {

                $stmt = $db->prepare($sql);
                $stmt->bindValue(':email', $params['e'], PDO::PARAM_STR);
                $stmt->bindValue(':first_name', $params['fn'], PDO::PARAM_STR);
                $stmt->bindValue(':last_name', $params['ln'], PDO::PARAM_STR);
                $stmt->bindValue(':passwd', $passwordHash, PDO::PARAM_STR);

                try {
                    $results = $stmt->execute(); 
                    
                    // Return the index ID if valid.
                    if ($results) {
                        $id = $db->lastInsertId();
                        $results = $id;
                    }
         
                } catch (PDOException $e) {
                    $this->addError(Configuration::DB_ERROR_MSG . $e->getMessage());
                    return false;
                }
                return $results;
            }
            else {
                $this->addError(Configuration::DB_ERROR_MSG . $db);
            }
             
        }

        $this->addError(Configuration::DB_ERROR_MSG . "Could not create new user record. Check parameters.");
        return false;
    }
    
    /**
     * Update an existing record, requires ID and at least one other field.
     *
     * @param array $params         Url parameters for the update at ID (primary key).
     * 
     * @return boolean              If the update worked correctly.
     */
    public function update($params)
    {
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

            $db = static::connectDB();      
            if ($db instanceof PDO) {

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
                    $this->addError(Configuration::DB_ERROR_MSG . $e->getMessage());
                    return false;
                }

                $updatedRows = $stmt->rowCount();
                return $updatedRows;
            }
            else {
                $this->addError(Configuration::DB_ERROR_MSG . $db);
            }
            
        }
        
        $this->addError(Configuration::DB_ERROR_MSG . "Could not update existing user record. Check parameters.");
        return false;
    }

    /**
     * Read one user record if ID provided, or all if no ID provided. 
     *
     * @param int $id           Integer id (primary key) of the read.
     * 
     * @return boolean          If the read worked correctly.
     */
    public function read($id = NULL)
    {
        $sql = 'SELECT 
                    id_users, email, first_name, last_name, passwd
                FROM 
                    users AS u';
        // Select only one entry
        if ($id) {
            $sql .= ' WHERE u.id_users = :id_users';
        }

        $db = static::connectDB();
        if ($db instanceof PDO) {
            
            $stmt = $db->prepare($sql);

            if ($id) {
                $stmt->bindValue(':id_users', $id, PDO::PARAM_INT);
            }

            try {
                $results = $stmt->execute();
            } catch (PDOException $e) {
                $this->addError(Configuration::DB_ERROR_MSG . $e->getMessage());
                return false;
            }

            // Return all or one entry depending on if id was passed.
            if ($stmt) {
                if ($id) {
                    $fetch = $stmt->fetch(PDO::FETCH_ASSOC);
                    if (!$fetch) {
                        is_array($fetch);
                        $this->addError(Configuration::DB_ERROR_MSG . "Could not read user row.");
                    }        
                    return is_array($fetch) ? $fetch : false;
                }
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }
        else {
            $this->addError(Configuration::DB_ERROR_MSG . $db);
        }

        $this->addError(Configuration::DB_ERROR_MSG . "Could not read user record. Check id used.");
        return false;
    }

    /**
     * Delete a user from the database for $id.
     * 
     * @param int $id           record id (primary key) to delete.
     *
     * @return boolean          Return if it worked or not.
     */
    public function delete($id)
    {   
        if (filter_var($id, FILTER_VALIDATE_INT)) {
            $sql = 'DELETE FROM users 
                    WHERE id_users = :id_users';

            $db = static::connectDB();
            if ($db instanceof PDO) {
                
                $stmt = $db->prepare($sql);
                $stmt->bindValue(':id_users', $id, PDO::PARAM_INT);
                try {
                    $results = $stmt->execute();
                } catch (PDOException $e) {
                    $this->addError(Configuration::DB_ERROR_MSG . $e->getMessage());
                    return false;
                }

                $deletedRows = $stmt->rowCount();
                return $deletedRows;
            }
            else {
                $this->addError(Configuration::DB_ERROR_MSG . $db);
            }
            
        }

        $this->addError(Configuration::DB_ERROR_MSG . "Could not delete user record. Check id.");
        return false;
    }

    /**
     * Connect to the database using Configuration settings.
     * 
     * @staticvar PDO $db       PDO database variable.
     * 
     * @return PDO              Returns created database if successful.
     */
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
    
    /**
     * Returns the error array.
     * 
     * @return array            Array of strings of error messages.
     */
    public function getErrors() 
    {
        return $this->errorArray;
    }
    
    /**
     * Adds a new string with error message.
     * 
     * @param string $s         String containing error message. 
     */
    public function addError($s) 
    {
        $this->errorArray[] = $s;
    }
    
}
