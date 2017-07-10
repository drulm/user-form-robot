<?php

/**
 * 
 * User view
 *
 * PHP version 7.0
 */
class View
{
   
   /**
     * Class constructor
     */
    public function __construct() 
    {
        // Add if needed for future.
    }
   
    /**
     * Main render method, selects the render based on type.
     * 
     * @param array $data               Data used for the render.
     * @param string $type              Type of render to generate.    
     */
    public function render($data, $type, $errors)
    {
        if ($type == 'index') {
            $html = $this->renderIndex($data);
        }
        else if ($type == 'json') {
            $html = $this->renderJson($data);
        }
        else if ($type == 'read') {
            $html = $this->renderRead($data);
        }
        else if ($type == 'otherAction') {
            $html = $this->renderOtherAction($data);
        }
        
        $errorMarkup = Configuration::VIEW_ERRORS ? $this->renderErrors($errors) : '';
        
        $this->renderTemplate($html, $errorMarkup);
    }
    
    /**
     * Generate html for reading a single user.
     * 
     * @param array $data           Array of one user from a read.
     * @return string               The HTML to display.
     */
    public function renderRead($data) 
    {

$markup = <<<HTML
    <h1>User Id: {$data['id_users']}</h1>
    <h2>Email: {$data['email']}</h2>
    <h2>First name: {$data['first_name']}</h2>
    <h2>Last name: {$data['last_name']}</h2>
    <h2>Password (hashed): {$data['passwd']}</h2>
HTML;
        
        return $markup;
    }
    
    /**
     * Generate HTML for rendering all users.
     * 
     * @param array $data           Array of all users.
     * @return string               HTML generated.
     */
    public function renderIndex($data) 
    {
        ob_start();
        echo '<table><thead><tr><th>';
        echo implode('</th><th>', array_keys(current($data)));
        echo '</tr></thead><tbody>';
        
        foreach ($data as $row) {
            array_map('htmlentities', $row);
            echo '<tr><td>' . implode('</td><td>', $row) . '</td></tr>'; 
        }
        echo '</tbody></table>';
        $html = ob_get_clean();
        return $html;
    }
    
    /**
     * Render simple page based on status of an action (command).
     * 
     * @param array $data           The data for this render
     *      $data[0] = action (string) => success (boolean)
     *      $data[1..n] = additional data
     * @return string               HTML generated.
     */
    public function renderOtherAction($data) 
    {
        
        $value = reset($data);
        $action = key($data);
        
        $outcome = $value ? "true" : "false";
        
$markup = <<<HTML
    <h1>Action: {$action}</h1>
    <h2>Outcome: {$outcome}</h2>
HTML;
        
        return $markup;
    }
    
    /**
     * Generate JSON based on data.
     * 
     * @param array $data           The data to render.
     * @return string               The generated HTML.
     */
    public function renderJson($data) 
    {
        return json_encode($data, JSON_PRETTY_PRINT);
    }
    
    /**
     * Create basic page based on the data and echo it.
     * 
     * @param type $html            The HTML to echo to the screen.
     */
    public function renderTemplate($html, $errors) 
    {

$markup = <<<HTML
<!DOCTYPE html>
<html>
    <head>
        <title>TODO supply a title</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        {$html}
        {$errors}
    </body>
</html>
HTML;
    
    echo $markup;
    }
    
    /**
     * Generates HTML for errors if shown.
     * 
     * @param array $data           Array of error strings.
     * @return string               Returns HTML.
     */
    public function renderErrors($errors) 
    {
        
        $markup = '';   
        foreach ($errors as $errString) {
            $markup .= '<h4>' . $errString . '</h4>';
        }

        return $markup;
    }
    
}
