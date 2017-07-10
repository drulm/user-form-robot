<?php

require_once 'Model.php';
require_once 'View.php';

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
     * The View for User.
     * 
     * @var View                    User View class instance.
     */
    protected $view;

    /**
     * Class constructor
     * 
     * @param array $parameters     Array of string, optional parameters from URL.
     */
    public function __construct($parameters = NULL) 
    {
        $this->user = new Model();
        $this->view = new View();
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
        $validRoute = (strlen($command) > 0 && !empty($query)) | ($command == '' && empty($query));

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
        
        $params = $this->getParams();
        
        if ($routeGood || $params['command'] == '' && empty($params['query'])) {

            switch ($params['command']) {
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
                case '':
                    $results = $this->defaultPage();
                    break;
                default:
                    $this->user->addError(Configuration::CONT_ERROR_MSG . "Not a valid command.");
                    $results = $this->routeError();
            }
        }
        else {
            $this->user->addError(Configuration::CONT_ERROR_MSG . "Not a valid route.");
            $results = $this->routeError();
        }

        if (!$results) {
            $this->user->addError(Configuration::CONT_ERROR_MSG . "Not a valid route.");
        }
        return $results;
    }
    
    /**
     * Controller action when there is a route error.
     * 
     * @return boolean          Returns false since route not successful.
     */
    public function routeError()
    {
        if (Configuration::DEBUG) {
            echo "<pre>routeError Params: "; var_dump($this->getParams()); echo '</pre>';
        }
        $this->view->render($this->getParams(), 'routeError', $this->getErrors());
        return false;
    }
    
    /**
     * Controller action for a default page with no command (action).
     * 
     * @return boolean          Returns true since this is default page.
     */
    public function defaultPage() 
    {
        $this->view->render($this->getParams(), 'defaultPage', $this->getErrors());
        return true;
    }
    
    /**
     * Update a user at an ID key.
     * 
     * @return boolean          Returns true if updated correctly.
     */
    public function updateUser() 
    {   
        $params = $this->getQueryParams();
        $results = false;
        if ($params) {
            $results = $this->user->update($params);
        }
        if (!$results) {
            $this->user->addError(Configuration::CONT_ERROR_MSG . "Could not update, check parameters.");
        }
        $this->view->render(['update' => $results], 'otherAction', $this->getErrors());
        return $results;
    }
    
    /**
     * Create a new user, requires all parameters, except ID.
     * 
     * @return boolean          Returns true if user created correctly.
     */
    public function createUser() 
    {   
        $params = $this->getQueryParams();
        $results = false;
        if ($params) {
            $results = $this->user->create($params);
        }
        if (!$results) {
            $this->user->addError(Configuration::CONT_ERROR_MSG . "Could not create new user, check parameters.");
        }
        $this->view->render(['create' => $results], 'otherAction', $this->getErrors());
        return $results;
    }
    
    /**
     * Delete a user for an ID key.
     * 
     * @return boolean              Returns true if user deleted succesfully.
     */
    public function deleteUser() 
    {
        $id = $this->getID();
        $results = false;
        if ($id) {
            $results = $this->user->delete($id);
        }
        if (!$results) {
            $this->user->addError(Configuration::CONT_ERROR_MSG . "User could not be deleted. Check ID.");
        }
        $this->view->render(['delete' => $results], 'otherAction', $this->getErrors());
        return $results;
    }
    
    /**
     * Read a single user at an ID.
     * 
     * @return mixed            Returns data from read command or false if could not read.
     */
    public function readUser() 
    {   
        $id = $this->getID();
        $results = false;
        if ($id) {
            $results = $this->user->read($id);
            if (!$results) {
                $this->user->addError(Configuration::CONT_ERROR_MSG . "Could not read user from database, check id.");
            }
        }
        if (!$results) {
            $this->user->addError(Configuration::CONT_ERROR_MSG . "Could not read user from database, Id not valid or missing.");
        }
        $this->view->render($results, 'read', $this->getErrors());
        return $results;
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
        $this->view->render($results, 'index', $this->getErrors());
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
        $this->user->addError(Configuration::CONT_ERROR_MSG . "Could not find valid ID.");
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
    public function getErrors() 
    {
        return $this->user->getErrors();
    }
    
}
