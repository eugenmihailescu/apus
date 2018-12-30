<?php
namespace Apus\Controller;

use Apus\Utils\HtmlBeautifier;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseController;

/**
 * The common controller functions
 *
 * @author Eugen Mihailescu
 *
 */
abstract class AbstractController extends BaseController
{

    private $kernel;

    /**
     * Renders a view.
     *
     * @param string $view
     *            The TWIG template name
     * @param array $parameters
     *            [optional] The TWIG template parameters
     * @param Response $response
     *            [optional] When specified reuse this instance, otherwise create a new one.
     * @return Response
     */
    protected function render(string $view, array $parameters = array(), Response $response = null): Response
    {
        $response = parent::render($view, $parameters, $response);

        $html = $response->getContent();

        $html_doc = new HtmlBeautifier($html, $this->isDebug() ? HtmlBeautifier::HTML_PRETTY : HtmlBeautifier::HTML_COMPACT);

        $response->setContent($html_doc->getHTML());

        return $response;
    }

    /**
     * The constructor
     *
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * Get the HTTP kernel
     *
     * @return KernelInterface
     */
    public function getKernel(): KernelInterface
    {
        return $this->kernel;
    }

    /**
     * Checks if debug mode is enabled.
     *
     * @return bool true if debug mode is enabled, false otherwise
     */
    public function isDebug(): bool
    {
        return $this->kernel->isDebug();
    }
}
