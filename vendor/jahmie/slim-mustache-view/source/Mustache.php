<?php
/**
 * Slim Mustache View - a Mustache view class for Slim
 *
 * @author      Jamie Telin
 * @link        https://github.com/jahmie/slim-mustache-view
 * @copyright   2016 Jamie Telin
 * @version     1.0
 */
namespace Slim\Mustache;

use \InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;

/**
 * Mustache view
 *
 * The Mustache view is a custom View class that renders templates using the Mustache
 * template language (https://github.com/bobthecow/mustache.php) in Slim.
 *
 */
class Mustache
{

    /**
     * @var array The options for the Mustache engine, see
     * @link https://github.com/bobthecow/mustache.php/wiki
     */
    private $options = array();

    /**
     * @var Mustache_Engine The Mustache engine for rendering templates.
     */
    private $instance = null;

    /**
     * @var array
     */
    private $loaderOptions = array();

    /**
     * Constructor
     *
     * @param string $templatePath
     * @param array $options Options for \Mustache_Engine
     * @param array $loaderOptions Options for \Mustache_Loader_FilesystemLoader
     */
    public function __construct($templatePath = "", $options = array(), $loaderOptions = array())
    {
        $this->setTemplatePath($templatePath);
        $this->setOptions($options);
        $this->setLoaderOptions($loaderOptions);
    }

    /**
     * Render Mustache template
     *
     * This method will write the rendered template content to the response
     *
     * @param ResponseInterface $response Psr Response used by Slim
     * @param string $templateName The mustache template name
     * @param array $data
     * @return void
     */
    public function render(ResponseInterface $response, $templateName, $data = array())
    {
        $output = $this->getRenderedMarkup($templateName, $data);
        $response->getBody()->write($output);
    }

    /**
     * Get rendered Mustache template
     *
     * Get the raw html from the rendered markup
     *
     * @param string $templateName The mustache template name
     * @param array $data
     * @return void
     */
    public function getRenderedMarkup($templateName, $data = array())
    {
        $m = $this->getInstance();
        return $m->render($templateName, $data);
    }

    /**
     * Creates new Mustache_Engine if it doesn't already exist, and returns it.
     *
     * @return \Mustache_Engine
     */
    public function getInstance()
    {
        if (!$this->instance) {

            $options = array();
            if (is_dir($this->getTemplatePath())) {
                $options['loader'] = new \Mustache_Loader_FilesystemLoader($this->getTemplatePath(), $this->getLoaderOptions());
            }
            // Check if the partials directory exists, otherwise Mustache will throw a exception
            if (is_dir($this->getTemplatePath().'/partials')) {
                $options['partials_loader'] = new \Mustache_Loader_FilesystemLoader($this->getTemplatePath().'/partials', $this->getLoaderOptions());
            }

            $options = array_merge((array)$options, (array)$this->options);

            $this->instance = new \Mustache_Engine($options);
        }

        return $this->instance;
    }

    /**
     * Get the template path
     *
     * @return string
     */
    public function getTemplatePath()
    {
        return $this->templatePath;
    }

    /**
     * Set the template path
     *
     * @param string $templatePath
     */
    public function setTemplatePath($templatePath)
    {
        $this->templatePath = $templatePath;
    }

    /**
     * Get the loader options for Mustache filesystem loader
     *
     * @link https://github.com/bobthecow/mustache.php/wiki/Template-Loading
     * @return array
     */
    public function getLoaderOptions()
    {
        return $this->loaderOptions;
    }

    /**
     * Set the loader options for Mustache filesystem loader
     *
     * @link https://github.com/bobthecow/mustache.php/wiki/Template-Loading
     * @param array $loaderOptions
     */
    public function setLoaderOptions($loaderOptions)
    {
        $this->loaderOptions = $loaderOptions;
    }

    /**
     * Get the Mustache options
     *
     * @link https://github.com/bobthecow/mustache.php/wiki
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set the Mustache options
     *
     * @link https://github.com/bobthecow/mustache.php/wiki
     * @param array $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }
}
