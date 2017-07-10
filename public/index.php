<?php

// @TODO Error reporting, remove when done.
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

// @TODO Clean up.
require_once '../params/Configuration.php';
require_once '../User/Controller.php';

// Create a new controller instance.
$controller = new Controller();

// Get the query string.
// @TODO Do not Access Superglobal $_SERVER Array Directly.
// @TODO Use some filtering functions instead (e.g. filter_input(), conditions with is_*() functions, etc.).
$url = $_SERVER['QUERY_STRING'];

// Direct the route given the url information.
$results = $controller->directRoute($url);

echo '<pre>';
echo "Results: "; var_dump($results);
echo '</pre>';

echo '<pre>';
echo "Errors: "; var_dump($controller->getErrors());
echo '</pre>';
