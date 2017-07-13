<?php
/**
 * User view
 *
 * PHP version 7.0
 *
 * @TODO namespace
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
	 * Main render method, selects the render based on type.
	 *
	 * @param array $data               Data used for the render.
	 * @param string $type              Type of render to generate.
	 * @param array $errors				Error strings in array.
	 *
	 * @return void
	 */
	public function render($data, $type, $errors) {
		switch ($type) {
			case 'index':
				$html = $this->renderIndex($data);
	   break;

			case 'read':
				$html = $this->renderRead($data);
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

		$errorMarkup = Configuration::VIEW_ERRORS ? $this->renderErrors($errors) : '';

		$this->renderTemplate($html, $errorMarkup);
	}

	/**
	 * Render HTML for a simple default page.
	 *
	 * @return string               The HTML to display.
	 */
	public function renderDefaultPage() {
$markup = <<<HTML
<h1>User MVC site</h1>
<h2>Default Page</h2>
<h3>Command Examples:</h3>
<ul>
<li>CREATE: /create/e/ignacy@prtl.dev/fn/Ignacy/ln/T./p/PrtlGames</li>
<li>CREATE: /index.php?command=create&e=ignacy@prtl.dev&fn=Ignacy&ln=T.&p=PrtlGames</li>
<li>READ: read/id/100</li>
<li>READ: index.php?command=read&id=100</li>
<li>READ json output: /read/id/6/type/json</li>
<li>READ json output: /index.php?command=read&id=6&type=json</li>
<li>UPDATE: /update/e/ignacy@prtl.dev/fn/Ignacy/ln/T./p/PrtlGames/id/100</li>
<li>UPDATE: /index.php?command=update&e=ignacy@prtl.dev&fn=Ignacy&ln=T.&p=PrtlGames&id=100</li>
<li>DELETE: /delete/id/100</li>
<li>DELETE: /index.php?command=delete&id=100</li>
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

	/**
	 * Generate html for when a route error occurs.
	 *
	 * @param array $data           Array of one user from a read.
	 * @return string               The HTML to display.
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
	 * Generate html for reading a single user.
	 *
	 * @param array $data           Array of one user from a read.
	 * @return string               The HTML to display.
	 */
	public function renderRead($data) {
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

	/**
	 * Render simple page based on status of an action (command).
	 *
	 * @param array $data           The data for this render
	 *      $data[0] = action (string) => success (boolean)
	 *      $data[1..n] = additional data
	 * @return string               HTML generated.
	 */
	public function renderOtherAction($data) {
		$value = reset($data);
		$action = key($data);

		$outcome = $value ? 'true' : 'false';

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
	public function renderJson($data) {
		echo json_encode($data, JSON_PRETTY_PRINT);
	}

	/**
	 * Create basic page based on the data and echo it.
	 *
	 * @param string $html			The HTML to echo to the screen.
	 * @param array $errors			String array of error messages.
	 *
	 * @return void
	 */
	public function renderTemplate($html, $errors) {
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
	 * @param array $errors			Array of error strings.
	 * @return string               Returns HTML.
	 */
	public function renderErrors($errors) {
		$markup = ''; foreach ($errors as $errString) {
			$markup .= '<pre><h4>' . $errString . '</h4></pre>';
		}
		return $markup;
	}

}
