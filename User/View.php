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
        if (! \params\Configuration::VIEW_ERRORS) {
            unset($args['errors']);
        }
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
    public function renderJson($data, $other_action = false)
    {
        if (!$other_action) {
              echo json_encode($data, JSON_PRETTY_PRINT);
        } else {
              $outcome = reset($data);
              $action = key($data);
              $jsonOutput = ['result' => [$action => $outcome]];
            echo json_encode($jsonOutput, JSON_PRETTY_PRINT);
        }
    }
}
