<?php
/**
 * A User pre controller
 *
 * PHP version 7.0
 */

// @TODO Clean up.
require_once '../params/Configuration.php';
require_once '../User/Controller.php';

if (Configuration::DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', TRUE);
    ini_set('display_startup_errors', TRUE);
}

// Create a new controller instance.
$controller = new Controller();

// Get the query string.
// @TODO Do not Access Superglobal $_SERVER Array Directly.
// @TODO Use some filtering functions instead (e.g. filter_input(), conditions with is_*() functions, etc.).
$url = $_SERVER['QUERY_STRING'];

// Direct the route given the url information.
$results = $controller->directRoute($url);

echo '<pre>';
echo "Errors: "; var_dump($controller->getErrors());
echo '</pre>';
