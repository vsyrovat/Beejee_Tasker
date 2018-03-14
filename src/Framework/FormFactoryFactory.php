<?php

namespace Framework;

use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Component\Form\Extension\Csrf\CsrfExtension;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormRenderer;
use Symfony\Component\Form\Forms;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Csrf\TokenGenerator\UriSafeTokenGenerator;
use Symfony\Component\Security\Csrf\TokenStorage\SessionTokenStorage;
use Symfony\Component\Translation\Translator;

class FormFactoryFactory
{
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function createFormFactory(\Twig_Environment $twig): FormFactory
    {
        $csrfGenerator = new UriSafeTokenGenerator();
        $csrfStorage = new SessionTokenStorage($this->app->getSession());
        $csrfManager = new CsrfTokenManager($csrfGenerator, $csrfStorage);

        $translator = new Translator('en');
        $twig->addExtension(new TranslationExtension($translator));

        $formEngine = new TwigRendererEngine(['bootstrap_4_layout.html.twig'], $twig);

        $twig->addRuntimeLoader(new \Twig_FactoryRuntimeLoader([
            FormRenderer::class => function() use ($formEngine, $csrfManager) {
                return new FormRenderer($formEngine, $csrfManager);
            }
        ]));

        $twig->addExtension(new FormExtension());

        $formFactory = Forms::createFormFactoryBuilder()
            ->addExtension(new HttpFoundationExtension())
            ->addExtension(new CsrfExtension($csrfManager))
            ->getFormFactory();

        return $formFactory;
    }
}
