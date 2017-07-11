<?php

require_once(dirname(__FILE__) . '/../../User/View.php');

/**
 * Generated by PHPUnit_SkeletonGenerator on 2017-07-11 at 11:34:18.
 */
class ViewTest extends PHPUnit_Framework_TestCase {

    /**
     * @var View
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new View;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    public function testRender() {
        $this->assertEquals(1, 1);
    }

    public function testRenderDefaultPage() {
        $this->assertEquals(1, 1);
    }

    public function testRenderRouteError() {
        $this->assertEquals(1, 1);
    }

    public function testRenderRead() {
        $this->assertEquals(1, 1);
    }

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

    public function testRenderOtherAction() {
        $data = ['action' => false];
        $result = $this->object->renderOtherAction($data);

$expected = <<<HTML
<h1>Action: action</h1>
<h2>Outcome: false</h2>
HTML;
        
        $this->assertEquals($expected, $result);
    }

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
     * 
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
     * 
     */
    public function testRenderErrors() {
        $errors = ['err1', 'err2', 'err3'];
        $result = $this->object->renderErrors($errors);
        $expected = '<pre><h4>err1</h4></pre><pre><h4>err2</h4></pre><pre><h4>err3</h4></pre>';
        $this->assertEquals($expected, $result);
    }

}
