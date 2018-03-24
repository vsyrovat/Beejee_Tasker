<?php

declare(strict_types=1);

namespace Framework\Form;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Component\Form\Extension\Csrf\CsrfExtension;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
use Symfony\Component\Form\FormRenderer;
use Symfony\Component\Form\Forms;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Csrf\TokenGenerator\UriSafeTokenGenerator;
use Symfony\Component\Security\Csrf\TokenStorage\SessionTokenStorage;
use Symfony\Component\Translation\Translator;

class FormServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        if (class_exists('\Symfony\Bridge\Twig\AppVariable')) {
            $appVariableReflection = new \ReflectionClass('\Symfony\Bridge\Twig\AppVariable');
            $vendorTwigBridgeDirectory = dirname($appVariableReflection->getFileName());
            $app['twig.loader.filesystem']->addPath($vendorTwigBridgeDirectory.'/Resources/views/Form');
        }

        $app['twig']->addExtension(new FormExtension());

        $app['twig']->addExtension(new TranslationExtension($app['translator']));

        $app['form.factory'] = function ($app) {
            $csrfGenerator = new UriSafeTokenGenerator();
            $csrfStorage = new SessionTokenStorage($app['session']);
            $csrfManager = new CsrfTokenManager($csrfGenerator, $csrfStorage);

            $formEngine = new TwigRendererEngine(['bootstrap_4_layout.html.twig'], $app['twig']);

            $app['twig']->addRuntimeLoader(new \Twig_FactoryRuntimeLoader([
                FormRenderer::class => function() use ($formEngine, $csrfManager) {
                    return new FormRenderer($formEngine, $csrfManager);
                }
            ]));

            return Forms::createFormFactoryBuilder()
                ->addExtension(new HttpFoundationExtension())
                ->addExtension(new CsrfExtension($csrfManager))
                ->getFormFactory();
        };
    }
}
