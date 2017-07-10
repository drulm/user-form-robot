<?php

require_once 'Model.php';

//namespace User;

/**
 *
 * User controller
 *
 * PHP version 7.0
 *
 */
Class Controller
{
    
    /**
     * Array of parameters from url, either by path or query parameters.
     * 
     * @var array           Paramater array.
     */
    protected $parameters = [];
    
    /**
     * The Model / database class for User.
     * 
     * @var Model                   User Model class instance.
     */
    protected $user;

    /**
     * Class constructor
     * 
     * @param array $parameters     Array of string, optional parameters from URL.
     */
    public function __construct($parameters = NULL) 
    {
        $this->user = new Model();
        $this->parameters = $parameters;
    }

    /**
     * Analyse the URL route for User.
     * 
     * @param string $url           The URL string.          
     * 
     * @return boolean              True if route is valid for user, false otherwise.
     */
    public function analyseRoute($url) 
    {
        parse_str($url, $query);
        
        $path = strtok($url, '?');
        
        $path_elements = [];
        if ($path && strpos($path, '/') == true) {
            $path_elements = array_filter( explode('/', $path), 'strlen');
        }

        if (count($path_elements) != 0) {
            $command = array_shift($path_elements);
            $query = [];
            while (count($path_elements) > 0) {
                $paramKey = array_shift($path_elements);
                $value = array_shift($path_elements);
                $query[$paramKey] = $value;
            }
        }
        else {
            $command = isset($query['command']) ? $query['command'] : '';
        }
        
        // Spceial case when path is just 'index'
        if ($path == 'index' || $path == 'index/') {
            $command = 'index';
            $query = ['index' => ''];
        }
        
        $this->parameters = ['command' => $command, 'query' => $query];
        
        if (Configuration::DEBUG) {
            echo "<pre>path: "; var_dump($path);
            echo "path_elements: "; var_dump($path_elements);
            echo "query: "; var_dump($query);
            echo "command: "; var_dump($command); echo '</pre>';
        }
        
        $validRoute = strlen($command) > 0 && !empty($query);
        
        if (!$validRoute) {
            $this->user->addError(Configuration::CONT_ERROR_MSG . "Not a valid route. Check path and parameters.");
        }
        
        return $validRoute;
    }

    /**
     * A dispatcher to direct the route by action (command) type.
     * Create, Read, Update, Delete, and Index (Read All).
     * 
     * @param string $url          Path and query parameters from URL.
     * 
     * @return boolean             True if command successful.
     */
    public function directRoute($url)
    {   
        $routeGood = $this->analyseRoute($url);
        
        $results = false;
        
        if ($routeGood) {

            switch ($this->parameters['command']) {
                case 'create':
                    $results = $this->createUser();
                    break;
                
                case 'read':
                    $results = $this->readUser();
                    break;
                
                case 'index':
                    $results = $this->indexUser();
                    break;
                
                case 'update':
                    $results = $this->updateUser();
                    break;
                
                case 'delete':
                    $results = $this->deleteUser();
                    break;
                
                default:
                    $this->user->addError(Configuration::CONT_ERROR_MSG . "Not a valid command.");
            }
        }
        else {
            $this->user->addError(Configuration::CONT_ERROR_MSG . "Not a valid route.");
        }

        return $results;
    }
    
    /**
     * Update a user at an ID key.
     * 
     * @return boolean          Returns true if updated correctly.
     */
    public function updateUser() 
    {
        //$user = new Model();
        
        $params = $this->getQueryParams();
        
        if ($params) {
            $results = $this->user->update($params);
            return $results;
        }
        $this->user->addError(Configuration::CONT_ERROR_MSG . "Could not update, check parameters.");
        return false;
    }
    
    /**
     * Create a new user, requires all parameters, except ID.
     * 
     * @return boolean          Returns true if user created correctly.
     */
    public function createUser() 
    {   
        $params = $this->getQueryParams();
        
        if ($params) {
            $results = $this->user->create($params);
            return $results;
            }

        $this->user->addError(Configuration::CONT_ERROR_MSG . "Could not create new user, check parameters.");
        return false;
    }
    
    /**
     * Delete a user for an ID key.
     * 
     * @return boolean              Returns true if user deleted succesfully.
     */
    public function deleteUser() 
    {
        //$user = new Model();
        
        $id = $this->getID();
        
        $results = false;

        if ($id) {

            $results = $this->user->delete($id);

            if ($results == 0) {
                $this->user->addError(Configuration::CONT_ERROR_MSG . "User could not be deleted.");
                return false;
            }
            
            return $results;
        }

        $this->user->addError(Configuration::CONT_ERROR_MSG . "User could not be deleted. Check ID.");
        return false;
    }
    
    /**
     * Read a single user at an ID.
     * 
     * @return mixed            Returns data from read command or false if could not read.
     */
    public function readUser() 
    {   
        $id = $this->getID();
        
        if ($id) {
            $results = $this->user->read($id);
            if (!$results) {
                $this->user->addError(Configuration::CONT_ERROR_MSG . "Could not read user from database, check id.");
            }
            return $results;
        }
        
        $this->user->addError(Configuration::CONT_ERROR_MSG . "Could not read user from database for this id.");
        return false;
    }

    /**
     * Read and return all user data.
     * 
     * @return mixed            Returns all user data, or false if could not read users from database.  
     */
    public function indexUser() 
    {
        $results = $this->user->read();

        if (!$results) {
            $this->user->addError(Configuration::CONT_ERROR_MSG . "Could not index (read all) users from database.");
        }
        
        return $results;
    }

    /**
     * Returns ID for current URL parameters.
     * 
     * @return mixed            Returns ID for 'id' parameter, or false if could not find ID.
     */
    public function getID()
    {
        $params = $this->getQueryParams();
        if (isset($params['id']) && is_numeric($params['id'])) {
            return $params['id'];
        }
        $this->user->addError(Configuration::CONT_ERROR_MSG . "Could not find id.");
        return false;
    }
    
    /**
     * 
     * @return type
     */
    public function getParams()
    {
        return $this->parameters;
    }
    
    /**
     * Gets and returns only the query parameters, not the path.
     * 
     * @return array            Query parameters array of string.
     */
    public function getQueryParams()
    {
        return $this->getParams()['query'];
    }
    
    /**
     * Returns the error array, Controller version.
     * 
     * @return array            Array of strings of error messages.
     */
    public function getErrors() {
        return $this->user->getErrors();
    }
    
}
