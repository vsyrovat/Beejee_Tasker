<?php

namespace Framework;

use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class Application implements HttpKernelInterface
{
    protected $routes;
    protected $twig;
    protected $urlGenerator;
    protected $formFactory;
    protected $session;

    public function __construct()
    {
        $this->routes = new RouteCollection();
        $this->urlGenerator = new UrlGenerator($this->routes, new RequestContext());
        $this->session = new Session();
    }

    public function registerTwig(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function registerFormFactory(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {
        $context = new RequestContext();
        $context->fromRequest($request);

        $matcher = new UrlMatcher($this->routes, $context);

        $this->urlGenerator->setContext($context);

        try {
            $attributes = $matcher->match($request->getPathInfo());
            $controller = $attributes['controller'];
            unset($attributes['controller'], $attributes['_route']);
            $response = $controller($request, $this, $attributes);
            if (!$response instanceof Response) {
                if (is_scalar($response) || is_null($response)) {
                    $response = new Response($response);
                } else {
                    throw new \InvalidArgumentException(
                        'response should be string or instance of Response, '
                        .gettype($response). ' returned in '.$controller
                    );
                }
            }
        } catch (ResourceNotFoundException $e) {
            $response = new Response('Not found!', Response::HTTP_NOT_FOUND);
        }

        return $response;
    }

    public function run(Request $request = null)
    {
        if ($request === null) {
            $request = Request::createFromGlobals();
        }

        $response = $this->handle($request);
        $response->send();
    }

    public function map($path, $controller, $name)
    {
        $this->routes->add($name, new Route($path, ['controller' => $controller]));
    }

    public function redirect($url, $status = 302)
    {
        return new RedirectResponse($url, $status);
    }

    /**
     * @param string $template
     * @param array $data
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function render($template, $data = [])
    {
        return $this->twig->render($template, $data);
    }

    public function getUrlGenerator(): UrlGenerator
    {
        return $this->urlGenerator;
    }

    public function getFormFactory(): FormFactory
    {
        return $this->formFactory;
    }

    /**
     * @return Session
     */
    public function getSession(): Session
    {
        return $this->session;
    }
}
