<?php
/**
 * Composer
 */
require_once dirname(__FILE__) . '/../../vendor/autoload.php';

use User\View;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2017-07-11 at 11:34:18.
 */
class ViewTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var \User\View
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @return void
	 */
	protected function setUp() {
		$this->object = new View();
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @return void
	 */
	protected function tearDown() {
	}

	/**
	 * View test for render
	 *
	 * @todo Improve test to check for valid HTML or structure.
	 * @return void
	 */
	public function testRender() {
		$data = [
			'command' => 'testCommand',
			'query' =>
				[
					'q1' => 'v1',
					'q2' => 'v2',
				],
			];

		ob_start();
		$this->object->render($data, 'noCommand', ['err1', 'err2']);
		$result = ob_get_clean();
		$result = substr($result, 0, 15);

$expected = <<<HTML
<!DOCTYPE html>
HTML;

		$this->assertEquals($expected, $result);
	}

	/**
	 * View test for defaultPage
	 *
	 * @todo Improve test to check for valid HTML or structure.
	 * @return void
	 */
	public function testRenderDefaultPage() {
		$result = substr($this->object->renderDefaultPage(), 0, 22);

$expected = <<<HTML
<h1>User MVC site</h1>
HTML;

		$this->assertEquals($expected, $result);
	}

	/**
	 * View test for routeError
	 *
	 * @todo Improve test to check for valid HTML or structure.
	 * @return void
	 */
	public function testRenderRouteError() {
		$data = [
			'command' => 'testCommand',
			'query' =>
				[
					'q1' => 'v1',
					'q2' => 'v2',
				],
			];
		$result = $this->object->renderRouteError($data);

$expected = <<<HTML
<h1>Route Error:</h1>
<h2>Command: testCommand</h2>
<h2>Query: Array
(
    [q1] => v1
    [q2] => v2
)
</h2>
HTML;

		$this->assertEquals($expected, $result);
	}

	/**
	 * View test for renderRead
	 *
	 * @todo Improve test to check for valid HTML or structure.
	 * @return void
	 */
	public function testRenderRead() {
		$data = [
			'id_users' => 1,
			'email' => 'foo@test.dev',
			'first_name' => 'First',
			'last_name' => 'Last',
			'passwd' => 'password',
			];
		$result = $this->object->renderRead($data);

$expected = <<<HTML
<h1>User Id: 1</h1>
<h2>Email: foo@test.dev</h2>
<h2>First name: First</h2>
<h2>Last name: Last</h2>
<h2>Password (hashed): password</h2>
HTML;

		$this->assertEquals($expected, $result);
	}

	/**
	 * View test for renderIndex
	 *
	 * @todo Improve test to check for valid HTML or structure.
	 * @return void
	 */
	public function testRenderIndex() {
		$data = [
			0 => ['key1' => 'value1', 'key2' => 'value2'],
			1 => ['key3' => 'value3', 'key4' => 'value4']
			];
		$result = $this->object->renderIndex($data);

$expected = <<<HTML
<table><thead><tr><th>key1</th><th>key2</tr></thead><tbody><tr><td>value1</td><td>value2</td></tr><tr><td>value3</td><td>value4</td></tr></tbody></table>
HTML;

		$this->assertEquals($expected, $result);
	}

	/**
	 * @todo Improve test to check for valid HTML or structure.
	 * @return void
	 */
	public function testRenderOtherAction() {
		$data = ['action' => false, 'json' => false];
		$result = $this->object->renderOtherAction($data);

$expected = <<<HTML
<h1>Action: action</h1>
<h2>Outcome: </h2>
HTML;

		$this->assertEquals($expected, $result);
	}

	/**
	 * View test for renderJson
	 *
	 * @todo Improve test to check for valid HTML or structure.
	 * @return void
	 */
	public function testRenderJson() {
		$data = ['item1', 'item2', 'item2'];

		ob_start();
		$this->object->renderJson($data);
		$result = ob_get_clean();

$expected = <<<HTML
[
    "item1",
    "item2",
    "item2"
]
HTML;

		$this->assertEquals($expected, $result);
	}

	/**
	 * View test for renderTemplate
	 *
	 * @todo Improve test to check for valid HTML or structure.
	 * @return void
	 */
	public function testRenderTemplate() {
		$html = 'html';
		$errors = 'error';

		ob_start();
		$this->object->renderTemplate($html, $errors);
		$result = ob_get_clean();

$expected = <<<HTML
<!DOCTYPE html>
<html>
    <head>
        <title>TODO supply a title</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        html
        error
    </body>
</html>
HTML;

		$this->assertEquals($expected, $result);
	}

	/**
	 * View test for renderErrors
	 *
	 * @todo Improve test to check for valid HTML or structure.
	 * @return void
	 */
	public function testRenderErrors() {
		$errors = ['err1', 'err2', 'err3'];
		$result = $this->object->renderErrors($errors);
		$expected = '<pre><h4>err1</h4></pre><pre><h4>err2</h4></pre><pre><h4>err3</h4></pre>';
		$this->assertEquals($expected, $result);
	}

}
