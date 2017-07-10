<?php

/**
 * 
 * User view
 *
 * PHP version 7.0
 */
class View
{
    
    public static function render($data)
    {
        echo '<pre>';
        echo "*** Data: "; var_dump($data);
        echo '</pre>';
    }
    
}
