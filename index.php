<?php

/**
 * @file
 * A User pre controller.
 *
 * PHP version 7.0
 *
 * by: Darrell Ulm
 */

/**
 * Composer.
 */
require_once dirname(__FILE__) . '/vendor/autoload.php';

use params\Configuration;
use User\Controller;

if (Configuration::DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', true);
    ini_set('display_startup_errors', true);
}

// Create a new controller instance.
$controller = new Controller();

// Get the query string.
$url = $_SERVER['REQUEST_URI'];

// Direct the route given the url information.
$results = $controller->directRoute($url);
