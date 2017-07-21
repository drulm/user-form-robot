<?php
/**
 * User view
 *
 * PHP version 7.0
 *
 * by: Darrell Ulm
 */
namespace User;

use params\Configuration;

class View {
	/**
	 * Class constructor
	 */
	public function __construct() {
		// Add if needed for future.
	}
  
  /**
   * Render a view template using Twig
   *
   * @param string $template The template file
   * @param array $args Associative array of data to display in the view (optional)
   *
   * @return void
   */
  public static function renderTemplate($template, $args = [])
  {
      echo static::getTemplate($template, $args);
  }

  /**
   * Get the contents of a view template using Twig
   *
   * @param string $template The template filename
   * @param array $args Data to display, optional
   *
   * @return string
   */
  public static function getTemplate($template, $args = [])
  {
      static $twig = null;

      if ($twig === null) {
          $loader = new \Twig_Loader_Filesystem(dirname(__DIR__) . '/Views');
          $twig = new \Twig_Environment($loader);
      }

      return $twig->render($template, $args);
  }
  
	/**
	 * Generate JSON based on data.
	 *
	 * @param array $data The data to render.
	 * @return string The generated HTML.
	 */
	public function renderJson($data, $other_action = FALSE) {
    if (!$other_action) {
      echo json_encode($data, JSON_PRETTY_PRINT);
    }
    else {
      $outcome = reset($data);
		  $action = key($data);
      $jsonOutput = ['result' => [$action => $outcome]];
			echo json_encode($jsonOutput, JSON_PRETTY_PRINT);
    }
	}
  
  /**
	 * Generate html for when a route error occurs.
	 *
	 * @param array $data Array of one user from a read.
	 * @return string The HTML to display.
	 */
	public function renderRouteError($data) {
		ob_start();
		print_r($data['query']);
		$query = ob_get_clean();

$markup = <<<HTML
<h1>Route Error:</h1>
<h2>Command: {$data['command']}</h2>
<h2>Query: {$query}</h2>
HTML;

		return $markup;
	}
  
  /**
	 * Generates HTML for errors if shown.
	 *
	 * @param array $errors Array of error strings.
	 * @return string Returns HTML.
	 */
	public function renderErrors($errors) {
		$markup = ''; foreach ($errors as $errString) {
			$markup .= '<pre><h4>' . $errString . '</h4></pre>';
		}
		return $markup;
	}
  
  	/**
	 * Render simple page based on status of an action (command).
	 *
	 * @param array $data The data for this render
	 *      $data[0] = action (string) => success (boolean)
	 *      $data[1..n] = additional data
	 * @return string HTML generated.
	 */
	/*public function renderOtherAction($data) {
		$outcome = reset($data);
		$action = key($data);
		
		if ($data['json']) {
			$jsonOutput = ['result' => [$action => $outcome]];
			$markup = json_encode($jsonOutput, JSON_PRETTY_PRINT);
		}
		else {
      $results['errors'] = $this->getErrors();
      $this->view->renderTemplate('read.twig', $results);

      $markup = <<<HTML
<h1>Action: {$action}</h1>
<h2>Outcome: {$outcome}</h2>
HTML;

		}

		//return $markup;
	}
   * 
   */
  


	/**
	 * Main render method, selects the render based on type.
	 *
	 * @param array $data Data used for the render.
	 * @param string $type Type of render to generate.
	 * @param array $errors Error strings in array.
	 *
	 * @return void
	 */
  /*
	public function render($data, $type, $errors) {
		switch ($type) {
			case 'index':
				$html = $this->renderIndex($data);
        break;
			case 'read':
				$html = $this->renderRead($data);
        $this->renderTemplate($template, $args = []);
        break;
			case 'otherAction':
				$html = $this->renderOtherAction($data);
        break;
			case 'routeError':
				$html = $this->renderRouteError($data);
        break;
			case 'defaultPage':
				$html = $this->renderDefaultPage();
        break;
			case 'json':
				$this->renderJson($data);
        return;
			default:
				$html = $this->renderRouteError($data);
	   break;
		}

// @TODO Add error output to Twig
		$errorMarkup = '';
		if (isset($data['json']) && !$data['json']) {
			$errorMarkup = Configuration::VIEW_ERRORS ? $this->renderErrors($errors) : '';
		}
		
		$this->renderTemplateOld($html, $errorMarkup);
	}
   * 
   */

	/**
	 * Render HTML for a simple default page.
	 *
	 * @return string The HTML to display.
	 */
  /*
	public function renderDefaultPage() {
$markup = <<<HTML
<h1>User MVC site</h1>
<h2>Default Page</h2>
<h3>Command Examples:</h3>
<ul>
<li>CREATE: /create/e/ignacy@prtl.dev/fn/Ignacy/ln/T./p/PrtlGames</li>
<li>CREATE: /index.php?command=create&e=ignacy@prtl.dev&fn=Ignacy&ln=T.&p=PrtlGames</li>
<li>CREATE: /index.php?command=create&e=email6.dev&fn=first1&ln=last1&p=oassword1&type=json</li>
<li>CREATE: /create/e/ignacy7@prtl.dev/fn/Ignacy/ln/T./p/PrtlGames/type/json</li>
<li>READ: read/id/100</li>
<li>READ: index.php?command=read&id=100</li>
<li>READ json output: /read/id/6/type/json</li>
<li>READ json output: /index.php?command=read&id=6&type=json</li>
<li>UPDATE: /update/e/ignacy@prtl.dev/fn/Ignacy/ln/T./p/PrtlGames/id/100</li>
<li>UPDATE: /index.php?command=update&e=ignacy@prtl.dev&fn=Ignacy&ln=T.&p=PrtlGames&id=100</li>
<li>UPDATE: /index.php?command=update&e=ignacy8@prtl.dev&fn=Ignacy&ln=T.&p=PrtlGames&id=6&type=json</li>
<li>UPDATE: /update/e/ignacy@prtl.dev/fn/Ignacy/ln/T./p/PrtlGames/id/6/type/json</li>
<li>DELETE: /delete/id/100</li>
<li>DELETE: /index.php?command=delete&id=100</li>
<li>DELETE: /index.php?command=delete&id=4&type=json</li>
<li>DELETE: /delete/id/13/type/json</li>
<li>INDEX (list all): /index</li>
<li>INDEX (list all) json output: /index/type/json</li>
<li>INDEX (list all): /index.php?command=index</li>
<li>INDEX (list all): /index.php?command=index&type=json</li>
<li>NON VALID ROUTE EXAMPLE: /AnythingElse</li>
<li>NON VALID ROUTE EXAMPLE: /index.php?command=AnythingElse</li>
<li>DEFAULT PAGE / HOST: example: localhost</li>
<li>DEFAULT PAGE / HOST: example: localhost/index.php</li>
</ul>
HTML;

		return $markup;
	}
   * 
   */



	/**
	 * Generate html for reading a single user.
	 *
	 * @param array $data Array of one user from a read.
	 * @return string The HTML to display.
	 */
/*	public function renderRead($data) {
$markup = <<<HTML
<h1>User Id: {$data['id_users']}</h1>
<h2>Email: {$data['email']}</h2>
<h2>First name: {$data['first_name']}</h2>
<h2>Last name: {$data['last_name']}</h2>
<h2>Password (hashed): {$data['passwd']}</h2>
HTML;

		return $markup;
	}
 * 
 */

	/**
	 * Generate HTML for rendering all users.
	 *
	 * @param array $data Array of all users.
	 * @return string HTML generated.
	 */
  /**
	public function renderIndex($data) {
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
   * 
   */

	/**
	 * Create basic page based on the data and echo it.
	 *
	 * @param string $html The HTML to echo to the screen.
	 * @param array $errors String array of error messages.
	 *
	 * @return void
	 */
  /**
	public function renderTemplateOld($html, $errors) {
$markup = <<<HTML
<!DOCTYPE html>
<html>
    <head>
        <title>php-user-robot</title>
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
   * 
   */



}
