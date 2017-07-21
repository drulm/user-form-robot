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

}
