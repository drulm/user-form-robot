<?php

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
                    break;
                
                case 'read':
                    echo "***  read in model";
                    break;
                
                case 'update':
                    echo "*** update in model";
                    break;
                
                case 'delete':
                    echo "*** delete in model";
                    break;
                
                default:
                    echo "*** no command for string";
            }
        }
        else {
            echo "Some error case here";
        }
        
                
        echo '<pre>';
            echo "RouteParameters: "; var_dump($this->getParams());
        echo '</pre>';
    }
    
    public function getParams()
    {
        return $this->parameters;
    }
    
}