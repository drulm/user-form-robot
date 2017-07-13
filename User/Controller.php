<?php
/**
 * User controller
 *
 * PHP version 7.0
 *
 * @TODO namespace
 */
namespace User;

use params\Configuration;
use User\Model;
use User\View;

class Controller {

	/**
	 * Array of parameters from url, either by path or query parameters.
	 *
	 * @var array           Paramater array.
	 */
	protected $parameters = [];

	/**
	 * The Model / database class for User.
	 *
	 * @var \User\Model                   User Model class instance.
	 */
	protected $user;

	/**
	 * The View for User.
	 *
	 * @var \User\View                    User View class instance.
	 */
	protected $view;

	/**
	 * Class constructor
	 *
	 * @param array|null $parameters     Array of string, optional parameters from URL.
	 */
	public function __construct($parameters = null) {
		$this->user = new Model();
		$this->view = new View();
		$this->parameters = $parameters;
	}

	/**
	 * Analyse the URL route for User.
	 *
	 * @param string $url           The URL string.
	 *
	 * @return bool              True if route is valid for user, false otherwise.
	 */
	public function analyseRoute($url) {
		// Parse url into path and query parameters.
		$parsed_url = parse_url($url);

		// If the query is preset, parse it.
		$query = isset($parsed_url['query']) ? $parsed_url['query'] : '';
		parse_str($query, $query);

		// Save the path, and remove everything up to the ? if needed.
		$path = $parsed_url['path'];
		$path = strtok($path, '?');

		// Path elements holds each of the path-strings separated by '/'
		// Explode the path into each word in the path.
		$path_elements = [];
		if ($path && is_int(strpos($path, '/'))) {
			$path_elements = array_filter( explode('/', $path), 'strlen');
		}

		// If we have path elements that are not just index.php, then
		// convert path elements to equivalent of query parameters.
		if (count($path_elements) != 0 && $path_elements[1] != 'index.php') {
			$command = array_shift($path_elements);
			$query = [];
			while (count($path_elements) > 0) {
				$paramKey = array_shift($path_elements);
				$value = array_shift($path_elements);
				$query[$paramKey] = $value;
			}
		}
		// Otherwise set the query command if it is set.
		else {
			$command = isset($query['command']) ? $query['command'] : '';
		}

		// Special case when path is just 'index' for the default page.
		if ($path == 'index' || $path == 'index/') {
			$command = 'index';
			$query = ['index' => ''];
		}

		// Controller parameters are the command and the query elements.
		$this->parameters = ['command' => $command, 'query' => $query];

		if (Configuration::DEBUG) {
			echo '<pre>path: ';
var_dump($path);
			echo 'path_elements: ';
var_dump($path_elements);
			echo 'query: ';
var_dump($query);
			echo 'command: ';
var_dump($command);
echo '</pre>';
		}

		// Return the validity of the route.
		$validRoute = strlen($command) > 0 || ($command == '' && empty($query));
		if (!$validRoute) {
			$this->user->addError(Configuration::CONT_ERROR_MSG . 'Not a valid route. Check path and parameters.');
		}
		return $validRoute;
	}

	/**
	 * A dispatcher to direct the route by action (command) type.
	 * Create, Read, Update, Delete, and Index (Read All).
	 *
	 * @param string $url          Path and query parameters from URL.
	 *
	 * @return bool             True if command successful.
	 */
	public function directRoute($url) { $routeGood = $this->analyseRoute($url);

		$results = false;

		$params = $this->getParams();

		if ($routeGood || $params['command'] == '' && empty($params['query'])) {

			switch ($params['command']) {
				case 'create':
					$results = $this->createUser();
	    break; case 'read':
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
					$this->user->addError(Configuration::CONT_ERROR_MSG . 'Not a valid command.');
					$results = $this->routeError();
			}
		}
		else {
			$this->user->addError(Configuration::CONT_ERROR_MSG . 'Not a valid route.');
			$results = $this->routeError();
		}

		if (!$results) {
			$this->user->addError(Configuration::CONT_ERROR_MSG . 'Not a valid route.');
		}
		return $results;
	}

	/**
	 * Controller action when there is a route error.
	 *
	 * @return bool          Returns false since route not successful.
	 */
	public function routeError() {
		if (Configuration::DEBUG) {
			echo '<pre>routeError Params: ';
var_dump($this->getParams());
echo '</pre>';
		}
		$this->view->render($this->getParams(), 'routeError', $this->getErrors());
		return false;
	}

	/**
	 * Controller action for a default page with no command (action).
	 *
	 * @return bool          Returns true since this is default page.
	 */
	public function defaultPage() {
		$this->view->render($this->getParams(), 'defaultPage', $this->getErrors());
		return true;
	}

	/**
	 * Update a user at an ID key.
	 *
	 * @return bool          Returns true if updated correctly.
	 */
	public function updateUser() { $params = $this->getQueryParams();
		$results = false;
		if ($params) {
			$results = $this->user->update($params);
		}
		if (!$results) {
			$this->user->addError(Configuration::CONT_ERROR_MSG . 'Could not update, check parameters.');
		}
		$query = $this->getQueryParams();
		$json = isset($query['type']) && $query['type'] == 'json'; 
		$this->view->render(['update' => $results, 'json' => $json], 
			'otherAction', $this->getErrors());
		return $results;
	}

	/**
	 * Create a new user, requires all parameters, except ID.
	 *
	 * @return bool          Returns true if user created correctly.
	 */
	public function createUser() { $params = $this->getQueryParams();
		$results = false;
		if ($params) {
			$results = $this->user->create($params);
		}
		if (!$results) {
			$this->user->addError(Configuration::CONT_ERROR_MSG . 'Could not create new user, check parameters.');
		}
		$query = $this->getQueryParams();
		$json = isset($query['type']) && $query['type'] == 'json'; 
		$this->view->render(['create' => $results, 'json' => $json], 
			'otherAction', $this->getErrors());

		return $results;
	}

	/**
	 * Delete a user for an ID key.
	 *
	 * @return bool              Returns true if user deleted successfully.
	 */
	public function deleteUser() {
		$id = $this->getID();
		$results = false;
		if ($id) {
			$results = $this->user->delete($id);
		}
		if (!$results) {
			$this->user->addError(Configuration::CONT_ERROR_MSG . 'User could not be deleted. Check ID.');
		}
		$query = $this->getQueryParams();
		$json = isset($query['type']) && $query['type'] == 'json'; 
		$this->view->render(['delete' => $results, 'json' => $json], 
			'otherAction', $this->getErrors());
		return $results;
	}

	/**
	 * Read a single user at an ID.
	 *
	 * @return mixed            Returns data from read command or false if could not read.
	 */
	public function readUser() { $id = $this->getID();
		$results = false;
		if ($id) {
			$results = $this->user->read($id);
			if (!$results) {
				$this->user->addError(Configuration::CONT_ERROR_MSG . 'Could not read user from database, check id.');
			}
		}
		if (!$results) {
			$this->user->addError(Configuration::CONT_ERROR_MSG . 'Could not read user from database, Id not valid or missing.');
		}

		$query = $this->getQueryParams();
		if (isset($query['type']) && $query['type'] == 'json') {
			$this->view->render($results, 'json', $this->getErrors());
		}
		else {
			$this->view->render($results, 'read', $this->getErrors());
		}
		return $results;
	}

	/**
	 * Read and return all user data.
	 *
	 * @return mixed            Returns all user data, or false if could not read users from database.
	 */
	public function indexUser() {
		$results = $this->user->read();
		if (!$results) {
			$this->user->addError(Configuration::CONT_ERROR_MSG . 'Could not index (read all) users from database.');
		}

		$query = $this->getQueryParams();
		if (isset($query['type']) && $query['type'] == 'json') {
			$this->view->render($results, 'json', $this->getErrors());
		}
		else {
			$this->view->render($results, 'index', $this->getErrors());
		}
		return $results;
	}

	/**
	 * Returns ID for current URL parameters.
	 *
	 * @return mixed            Returns ID for 'id' parameter, or false if could not find ID.
	 */
	public function getID() {
		$params = $this->getQueryParams();
		if (isset($params['id']) && is_numeric($params['id'])) {
			return $params['id'];
		}
		$this->user->addError(Configuration::CONT_ERROR_MSG . 'Could not find valid ID.');
		return false;
	}

	/**
	 * Returns parameters of path / query.
	 *
	 * @return array
	 */
	public function getParams() {
		return $this->parameters;
	}

	/**
	 * Gets and returns only the query parameters, not the path.
	 *
	 * @return array            Query parameters array of string.
	 */
	public function getQueryParams() {
		return $this->getParams()['query'];
	}

	/**
	 * Returns the User model instance.
	 *
	 * @return \User\Model
	 */
	public function getUser() {
		return $this->user;
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
