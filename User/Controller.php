<?php

//namespace User;

Class Controller 
{
    
    protected $parameters = [];
    
    protected $commands = ['create', 'read', 'update', 'delete', 'list'];

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
        }
        else {
            $command = isset($query['command']) ? $query['command'] : '';
        }
        
        $returnRoutes = [
            'command' => $command
            ];
        
        echo '<pre>';
            echo "path: "; var_dump($path);
            echo "path_elements: "; var_dump($path_elements);
            echo "query: "; var_dump($query);
            echo 'count($path_elements)'; var_dump(count($path_elements));
            echo "command: "; var_dump($command);
        echo '</pre>';
        
        return $returnRoutes;
    }

    public function directRoute($url)
    {
        echo "in directRoute [" . $url . "]<br />";
        
        $routeInfo = $this->analyseRoute($url);
        
                
        echo '<pre>';
            echo "RouteInfo: "; var_dump($routeInfo);
        echo '</pre>';
    }
    
    public function getParams()
    {
        return $this->params;
    }
    
    public function match($url)
    {
        foreach ($this->routes as $route => $params) {
            if ($url == $route) {
                $this->params = $params;
                return true;
            }
        }

        return false;
    }
    
}