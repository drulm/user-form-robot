<?php

require_once 'Model.php';

//namespace User;

Class Controller
{
    
    protected $parameters = [];

    public function __construct($parameters = NULL) 
    {
        $this->parameters = $parameters;
    }

    public function analyseRoute($url) 
    {
        parse_str($url, $query);

        reset($query);
        $path = key($query);
        
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
        
        $this->parameters = [
            'command' => $command,
            'query' => $query
            ];
        
        echo '<pre>';
            echo "path: "; var_dump($path);
            echo "path_elements: "; var_dump($path_elements);
            echo "query: "; var_dump($query);
            echo 'count($path_elements)'; var_dump(count($path_elements));
            echo "command: "; var_dump($command);
        echo '</pre>';
        
        return strlen($command) > 0 && !empty($query);
    }

    public function directRoute($url)
    {
        echo "in directRoute [" . $url . "]<br />";
        
        $routeGood = $this->analyseRoute($url);
        echo '<pre>';
            echo "routeGood: "; var_dump($routeGood);
        echo '</pre>';
        
        if ($routeGood) {

            switch ($this->parameters['command']) {
                case 'create':
                    echo "*** create in model";
                    $this->createUser();
                    break;
                
                case 'read':
                    echo "***  read in model";
                    $this->readUser();
                    break;
                
                case 'index':
                    echo "***  index in model";
                    $this->indexUser();
                    break;
                
                case 'update':
                    echo "*** update in model";
                    $this->updateUser();
                    break;
                
                case 'delete':
                    echo "*** delete in model";
                    $this->deleteUser();
                    break;
                
                default:
                    echo "*** no command for string";
            }
        }
        else {
            echo "Some error case here";
        }

    }
    
    public function updateUser() 
    {
        $user = new Model();
        
        $params = $this->getQueryParams();
        
        if ($params) {

            $results = $user->update($params);

            if ($results === true) {
                echo '<pre>';
                echo "update: "; var_dump($results);
                echo '</pre>';
                return $results;
            }
        }
        echo '<pre>';
        echo "Could not update: "; var_dump($results);
        echo '</pre>';
        return false;
    }
    
    public function createUser() 
    {
        $user = new Model();
        
        $params = $this->getQueryParams();
        
        if ($params) {

            $results = $user->create($params);

            if ($results === true) {
                echo '<pre>';
                echo "create: "; var_dump($results);
                echo '</pre>';
                return $results;
            }
        }
        echo '<pre>';
        echo "Could not create: "; var_dump($results);
        echo '</pre>';
        return false;
    }
    
    public function deleteUser() 
    {
        $user = new Model();
        
        $id = $this->getID();
        
        $results = false;

        if ($id) {

            $results = $user->delete($id);

            if ($results and $results > 0) {
                echo '<pre>';
                echo "delete: "; var_dump($results);
                echo '</pre>';
                return $results;
            }
        }
        echo '<pre>';
        echo "Could not delete: "; var_dump($results);
        echo '</pre>';
        return false;
    }
    
    public function readUser() 
    {
        $user = new Model();
        
        $id = $this->getID();
        
        if ($id) {

            $results = $user->read($id);

            if ($results !== false) {
                echo '<pre>';
                echo "read: "; var_dump($results);
                echo '</pre>';
                return $results;
            }
        }
        echo '<pre>';
        echo "Could not read: "; var_dump($results);
        echo '</pre>';
        return false;
    }

    public function indexUser() 
    {
        $user = new Model();

        $results = $user->read();

        if ($results) {
            echo '<pre>';
            echo "index: "; var_dump($results);
            echo '</pre>';
            return $results;
        }
        echo '<pre>';
        echo "Could not index-read: "; var_dump($this->getParams());
        echo '</pre>';
        return false;
    }

    public function getID()
    {
        $params = $this->getQueryParams();
        if (isset($params['id']) && is_numeric($params['id'])) {
            return $params['id'];
        }
        return false;
    }
    
    public function getParams()
    {
        return $this->parameters;
    }
    
    
    public function getQueryParams()
    {
        return $this->getParams()['query'];
    }
    
}